<?php

namespace Modules\UserProfile\Http\Controllers;

use App\Models\Training\Content;
use App\Models\Training\Exam;
use App\Models\Training\Question;
use App\Models\Training\UserAnswer;
use App\Models\Training\UserContent;
use App\Models\Training\UserExam;
use App\User;
use App\Constant;
use App\Http\Requests\ProfileRequest;
use App\Models\Admin\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Training\Course;
use App\Models\Training\Social;
use Illuminate\Routing\Controller;
use App\Models\Training\Experience;
use App\Models\Training\Payment;
use App\Profile;
use App\ProfileAnswer;
use App\ProfileQuestion;
use App\ProfileQuestionUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'login', 'register', 'loginSubmit', 'registerSubmit', 'passwordReset', 'resetSubmit']]);

        $this->middleware('verified', ['except' => [
            'login', 'register', 'loginSubmit', 'registerSubmit', 'logout'
        ]]);
    }

    public function exam($exam_id){
        $exam = Content::whereId($exam_id)
            ->with(['exam' => function($q){
                return $q->with(['users_exams']);
            }])->first();

        if (!$exam->exam) abort(404);


        UserContent::firstOrCreate([
            'user_id' => \auth()->id(),
            'content_id' => $exam_id,
        ],[
            'user_id'  => \auth()->id(),
            'content_id' => $exam_id,
        ]);

//        return $exam;
//        $user_exams_count = count($exam->exam->users_exams);
//
//        if ($exam->exam->users_exams[$user_exams_count-1]->status == 0){
//            return view('userprofile::users.exam_preview',compact('exam'));
//        }
        return view('userprofile::users.exam',compact('exam'));
    }

    public function add_answers(){
      $user_exam =  UserExam::whereId(\request()->user_exam_id)
          ->where('user_id',\auth()->id())->where('status',0)->first();
      if (!$user_exam) abort(404);

        foreach (\request()->answers as $key => $value){
            if (!is_null($value)){
                 if(is_array($value)){
                     UserAnswer::where( 'user_exam_id' , \request()->user_exam_id)->
                           where('question_id',$key)->delete();
                     foreach ($value as $answer){
                         UserAnswer::create([
                             'answer_id' => $answer,
                             'question_id' => $key,
                             'user_exam_id' => \request()->user_exam_id,
                         ]);
                     }
                 }
                 else{
                     UserAnswer::updateOrCreate([
                         'question_id' => $key,
                         'user_exam_id' => \request()->user_exam_id,
                     ],[
                         'answer_id' => $value,
                         'question_id' => $key,
                         'user_exam_id' => \request()->user_exam_id,
                     ]);
                 }
            }
        }

        if (\request()->status == 'save'){
            $user_exam->update([
                'status' => 1
            ]);
            $user_exam = UserExam::whereId(\request()->user_exam_id)
                ->select('id','exam_id')->with('exam')->first();

            // mark
            $user_exam_id = \request()->user_exam_id;
            $grade =  DB::select( DB::raw("SELECT SUM(questions.mark) as grade
                                FROM `user_answers`
                                    INNER JOIN answers ON user_answers.answer_id = answers.id
                                    INNER JOIN questions ON questions.id = answers.question_id
                                WHERE user_exam_id = ". $user_exam_id ."
                                AND answers.check_correct = 1"));

            UserExam::where('id',$user_exam_id)->update([
                'end_attempt' => Carbon::now(),
                'mark' => $grade[0]->grade??0
            ]);

/*
       SELECT SUM(questions.mark) as grade
    FROM `user_answers`
        INNER JOIN answers ON user_answers.answer_id = answers.id
        INNER JOIN questions ON questions.id = answers.question_id
    WHERE user_exam_id = 41
    AND answers.check_correct = 1

*/
            return response(['status' => 'success' , 'redirect_route' => route('user.exam',$user_exam->exam->content_id)]);
        }
    }


    public function preview_exam($exam_id){
        $page_type = 'exam';
        $exam = Content::whereId($exam_id)
            ->with(['exam' => function($q){
                return $q->with(['users_exams' => function($query){
                    return $query->where('user_id',\auth()->id())->with('user_answers');
                }])->where('start_date','<=',Carbon::now())
                    ->where('end_date','>',Carbon::now());
            },'questions.answers'])->first();

        if (!$exam->exam || (count($exam->questions) == 0) ) abort(404);
//        return $exam;

        $user_exams_count = count($exam->exam->users_exams);
        if (count($exam->exam->users_exams) > 0 && $exam->exam->users_exams[$user_exams_count-1]->status == 0){
            // duration time calc
            $start_user_attepmt = Carbon::now();

            $d = Carbon::parse($start_user_attepmt)
                ->addSeconds($exam->exam->duration * 60)
                ->format('Y-m-d H:i:s');;
            $d1 = strtotime($d);
            $d2 = strtotime($exam->exam->end_date);
            if( ($d1 - $d2) > 0){
                $exam->exam->duration =   $exam->exam->duration * 60 -  ($d1 - $d2);
            }else{
                $d = Carbon::parse($start_user_attepmt)
                    ->format('Y-m-d H:i:s');;
                $d1 = strtotime($d);
                $d2 = strtotime($exam->exam->users_exams[$user_exams_count-1]->time);
                $exam->exam->duration = ($exam->exam->duration * 60) -  ($d1 - $d2);
            }

            return view('userprofile::users.exam_preview',compact('exam','start_user_attepmt','page_type'));
        }

        if ( $user_exams_count < $exam->exam->attempt_count ||  $exam->exam->attempt_count == 0){
            $start_user_attepmt = Carbon::now();
            $data = UserExam::create([
                    'user_id' => \auth()->id() ,
                    'exam_id' => $exam->exam->id,
                    'status' => 0,
                    'time' => $start_user_attepmt,
                ]);
            $exam->exam->users_exams->push($data);

            // duration time calc
           $d = Carbon::parse($start_user_attepmt)
               ->addSeconds($exam->exam->duration * 60)
               ->format('Y-m-d H:i:s');;
           $d1 = strtotime($d);
           $d2 = strtotime($exam->exam->end_date);
            if( ($d1 - $d2) > 0){
                $exam->exam->duration =   $exam->exam->duration * 60 -  ($d1 - $d2);
            }else{
                $exam->exam->duration *= 60;
            }
            return view('userprofile::users.exam_preview',compact('exam','start_user_attepmt','page_type'));

        }else{
            abort(404);
        }
    }


    public function review_exam($exam_id){
        $page_type = 'review';

        $exam = UserExam::whereId($exam_id)
            ->where('user_id',\auth()->id())
            ->where('status',1)
            ->with(['exam.content.questions.answers','user_answers'])
            ->first();

//        return $exam;

//        return $exam;
//        $exam = Content::whereId($exam_id)
//            ->with(['exam' => function($q){
//                return $q->with(['users_exams' => function($query){
//                    return $query->where('user_id',\auth()->id())->with('user_answers');
//                }]);
//            },'questions.answers'])->first();


        if ( !$exam  ) abort(404);


//         return $exam;

        return view('userprofile::users.exam_preview',compact('exam','page_type'));

    }



    public function dashboard() {
        //$user = User::find(auth()->user()->id);
        $user = auth()->user();
        //$latestCart = Cart
//        dd($user->upload->file??null);
        return view('userprofile::users.index',compact('user'));
    }

    public function info() {
        $user = User::with(['upload', 'socials','experiences'])->findOrFail(auth()->user()->id);
        $genders = Constant::where('parent_id', 42)->get();
        $countries = Constant::where('post_type', 'countries')->get();
        // return $user;
        return view('userprofile::users.info',compact('user', 'genders', 'countries'));
    }

    public function home() {
        $courses =  User::where('id',\auth()->id())->with(['courses.upload' => function($q){
            return $q->where('post_type','image')->where('locale',app()->getLocale());
        }])->first();
        return view('userprofile::users.home',compact('courses'));
    }

    public function referral() {
        return view('userprofile::users.referral');
    }

    public function complaintView($type) {
        $user = Auth::user();
        $courses = [];
        // return $type;
        if($user->carts->count() > 0) {
            foreach ($user->carts as $cart){
                $courses = Cart::where('course_id','!=',$cart->course->id)->with('course.uploads','session.trainingOption')->get();
            }
        }
        if($type == 'my_complaints'){
            $questions = ProfileQuestion::where('post_type','learn_complaints')->with('profile_answers.profile_question_users')->get();
        }else{
            $questions = ProfileQuestion::where('post_type','learn_refunds')->with('profile_answers.profile_question_users')->get();
        }

        return view('userprofile::users.complaint',compact('questions','user','type'));
    }

    public function complaintStore(ProfileRequest $request){
        $post_type = '';
        ($request->post_type == 'my_complaints') ? $post_type = 'learn_complaints' : $post_type = 'learn_refunds' ;
        $profile = ProfileQuestionUser::updateOrCreate([
            'user_id' => auth()->user()->id,
            'course_id' => $request->courses,
            'profile_answer_id' => $request->answer,
            'profile_answer_text' => $request->reason,
            'profile_question_id' => $request->question_id,
            'post_type' => $post_type,
        ]);

        if($profile) {
            session()->flash('success', __('education.Complaint has been sent successfully.'));
        }else {
            session()->flash('error', __('education.Failed to send complaint. Please send again.'));
        }
        return redirect()->route('user.my_complaints',$request->post_type);
    }

    public function myComplaints($type){
        $user = Auth::user();
        if($type == 'my_complaints'){
            $complaints = ProfileQuestionUser::where('post_type','learn_complaints')->with('products')->get();
        }else{
            $complaints = ProfileQuestionUser::where('post_type','learn_refunds')->with('products')->get();
        }
        return view('userprofile::users.my_complaints',compact('complaints','user','type'));
    }

    public function change_password() {
        return view('userprofile::users.change_password');
    }

    public function save_password() {

        request()->validate([
            'old_password' => 'required|password|min:7|max:16',
            'new_password' => 'required|min:7|max:16',
            'password_confirmation' => 'required|min:7|max:16|same:new_password',
        ]);
        $user = User::find(auth()->user()->id);
        $password = $user->password;

        if(Hash::check(request()->old_password, $password) &&(request()->new_password == request()->password_confirmation)){
            $password = Hash::make(request()->new_password);
        }

        $user->update([
            'password' => $password,
        ]);
        return view('userprofile::users.home');
    }

    public function update($id,Request $request){

        $request->validate([
            'new_password' => 'nullable|min:8|confirmed',
            'socials.*' => 'nullable',
            'experience' => 'nullable'
        ]);

        $user = User::findOrFail($id);
        $password = $user->password;

        if (Hash::check($request->password, $password)) {
            $password = Hash::make($request->new_password);
        }

        // $social->updateOrCreate([
        //     'link' => $request->socials->twitter,
        //     'user_id' => auth()->user()->id,
        // ]);
            // dd($request->experience);
        // $request->experience =  array_filter($request->experience, 'strlen');

        $user->update([
            'email'     => $request->email,
            'name'      => json_encode(["en" => $request->en_name, "ar" => $request->ar_name]),
            'headline'  => $request->headline,
            'lang'      => $request->language,
            'bio'       => $request->bio,
            'mobile'    => $request->mobile,
            'gender_id' => $request->gender_id,
            'password'  => $password
        ]);

        User::UploadFile($user, ['method'=>'update']);

        foreach($request->socials as $type => $link) {
            if($link) {
                Social::updateOrCreate([
                    'user_id'=>$id,
                    'type' => $type
                ], [
                    'user_id' => $id,
                    'type' => $type,
                    'link' => $link
                ]);
            }
        }

        if($request->experience != '')
        {
            foreach($request->experience as $experience) {
                Experience::updateOrCreate([
                    'user_id'=>$id,
                    'name' => $experience
                ], [
                    'user_id' => $id,
                    'name' => $experience,
                ]);
        }
        }


        return redirect()->back();
    }



    public function my_courses() {
        $courses =  User::where('id',\auth()->id())->with(['courses.upload' => function($q){
            return $q->where('post_type','image')->where('locale',app()->getLocale());
        }])->first();
        return view('userprofile::users.my_courses',compact('courses'));
    }

    public function exercise() {
        return view('userprofile::users.exercise');
    }



    public function course_details($course_id){
          $course = Course::where('id',$course_id)->whereHas('users',function ($q){
               $q->where('users.id',\auth()->id());
          })->with(['uploads' => function($query){
              return $query->where(function ($q){
                  $q->where('post_type','intro_video')->orWhere('post_type','image');
              });
          },'contents' => function($query){
              $query->where('post_type','section')->with(['contents.user_contents' => function($q){
                  return $q->where('user_id',\auth()->id());
              }]);
          }])->first();
          if (!$course){
              abort(404);
          }

//        return $course->uploads;


//          return $course;


        return view('userprofile::users.my_courses',compact('course'));
//        return view('userprofile::users.course_details',compact('course'));

    }

    public function course_preview($content_id){

        $content = Content::whereId($content_id)
            ->with(['upload','course.users' => function($q){
            $q->where('users.id',\auth()->id());
        }])->first();

        if (!$content){
            abort(404);
        }


        UserContent::firstOrCreate([
            'user_id' => \auth()->id(),
            'content_id' => $content_id,
        ],[
            'user_id'  => \auth()->id(),
            'content_id' => $content_id,
        ]);


        /*
           SELECT * FROM `contents`
               INNER JOIN contents AS sections ON contents.parent_id = sections.id
                WHERE contents.course_id = 1
               ORDER BY contents.parent_id
        *******************

        SELECT * FROM `contents`
           INNER JOIN contents AS sections ON contents.parent_id = sections.id
        WHERE contents.course_id = 1
              AND (contents.parent_id >= 203 AND contents.id != 210)
        ORDER BY contents.parent_id
        LIMIT 1

         */


      $next =  DB::select(DB::raw("
                        SELECT contents.id , contents.title,contents.post_type FROM `contents`
                          INNER JOIN contents AS sections ON contents.parent_id = sections.id
                            WHERE contents.course_id = $content->course_id
                              AND contents.id !=  $content->id
                              AND contents.deleted_at IS NULL
                              AND  (
                                  (contents.order > $content->order AND contents.parent_id = $content->parent_id)
                                    OR
                                  (contents.parent_id > $content->parent_id)
                              )
                        ORDER BY contents.parent_id
                        ,contents.order
                        LIMIT 1
                        "
        ));


        $previous =  DB::select(DB::raw(
            "
                        SELECT contents.id , contents.title,contents.post_type FROM `contents`
                          INNER JOIN contents AS sections ON contents.parent_id = sections.id
                            WHERE contents.course_id = $content->course_id
                             AND contents.id !=  $content->id
                              AND contents.deleted_at IS NULL
                              AND  (
                                  (contents.order < $content->order AND contents.parent_id = $content->parent_id)
                                    OR
                                  ( contents.parent_id < $content->parent_id)
                              )
                        ORDER BY contents.parent_id DESC
                        ,contents.order DESC
                        LIMIT 1
                         "
        ));


        $next = ($next[0]??null);
        $previous = ($previous[0]??null);


        return view('userprofile::users.file',compact('content','previous','next'));
    }



//    public function course_preview($content_id){
//        $content = Content::whereId($content_id)->with(['upload','course.users' => function($q){
//            $q->where('users.id',\auth()->id());
//        }])->first();
//
//        if (!$content){
//            abort(404);
//        }
////        return $content;
//
//        return view('userprofile::users.course_preview',compact('content'));
//    }



    public function logout(Request $request) {
        Auth::logout();
        return redirect('/');
    }


    public function login()
    {
        if(Auth::check()){
            return redirect('/');
        }
        return view('userprofile::users.login');
    }

    public function loginSubmit(Request $request)
    {
        $data = Validator::validate($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($data)) {


            if(request()->has('redirectTo')) {
                return redirect()->route('education.cart');
            }

            return redirect()->route('user.home');

        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);

        return back();
    }

    public function register()
    {
        if(Auth::check()){
            return redirect('/');
        }
        return view('userprofile::users.register');
    }

    public function registerSubmit(Request $request)
    {
        $request->validate([
            'en_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required',
            'password' => 'required|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();

        if($user) {

            if(is_null($user->password)) {
                $user->update(['password' => Hash::make($request->password)]);
            }

        }else {

            $user = User::create([
                'name' => $request->en_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'user_type' => 41
            ]);
        }




        Auth::login($user);

        if(request()->has('redirectTo')) {
            return redirect()->route('education.cart');
        }

        if(request()->has('action') && request()->action == 'wishlist') {
            return redirect(request()->redirect);
        }

        return redirect()->route('user.home');
    }

    // public function passwordReset() {
    //     return view('userprofile::users.password_reset');
    // }

    // public function resetSubmit() {
    //     dd(request()->all());
    // }

}
