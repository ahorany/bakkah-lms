<?php

namespace Modules\UserProfile\Http\Controllers;

use App\Models\Training\Answer;
use App\Models\Training\Content;
use App\Models\Training\CourseRegistration;
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

    public function attempt_details ($user_exams_id){
//        $user_exams_id = 183;

       $unit_marks =  DB::select(DB::raw("SELECT units_total_marks.unit_id as id , user_answers_units_marks.marks , units_total_marks.total_marks  ,units_total_marks.unit_title  FROM (
                                                   SELECT unit_id ,unit_title, SUM(total_table.marks) as total_marks FROM (
                                                     SELECT  DISTINCT questions.id , questions.unit_id as unit_id , units.title as unit_title, questions.mark as marks FROM user_exams
                                                       INNER JOIN exams ON user_exams.exam_id = exams.id
                                                       INNER JOIN questions ON exams.content_id = questions.exam_id
                                                        LEFT JOIN units ON units.id = questions.unit_id
                                                         WHERE user_exams.id = $user_exams_id
                                                         AND exams.deleted_at IS null
                                                             ) as total_table
                                                         GROUP BY unit_id
                                                        ) as units_total_marks

                                                INNER JOIN (SELECT units.id as unit_id
                                                               , SUM(user_answers.mark) as marks FROM user_exams
                                                                INNER JOIN user_answers ON user_exams.id = user_answers.user_exam_id
                                                                INNER JOIN questions ON questions.id = user_answers.question_id
                                                                LEFT JOIN units ON units.id = questions.unit_id
                                                                WHERE user_exams.id = ".$user_exams_id."
                                                                GROUP BY  questions.unit_id , units.title) as user_answers_units_marks
                                                                ON units_total_marks.unit_id = user_answers_units_marks.unit_id

       "));

//       dd($unit_marks);
        return view('userprofile::users.exam_details',compact('unit_marks'));

    }


    public function user_rate(){
      $course_registration =   CourseRegistration::where('user_id',\auth()->id())
            ->where('course_id',\request()->course_id)->first();
        if (!$course_registration){
             return response()->json(['status' => 'error']);
        }

        $course_registration->update(['rate' => \request()->rate]);

        $total_rate = DB::select(DB::raw('SELECT AVG(rate) as total_rate FROM `courses_registration` WHERE course_id =' .\request()->course_id));
        $total_rate = $total_rate[0]->total_rate;

        return response()->json(['status' => 'success','data' => $total_rate ]);
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

        return view('userprofile::users.exam',compact('exam'));
    }

    public function exams(){
        return view('userprofile::users.exam-front');
    }
    // public function file(){
    //     return view('userprofile::users.exam-front');
    // }

    public function add_answers(){
      $user_exam =  UserExam::whereId(\request()->user_exam_id)
          ->where('user_id',\auth()->id())->where('status',0)->first();
      if (!$user_exam) abort(404);

         // save answers
        foreach (\request()->answers as $key => $value){
            if (!is_null($value)){

                if(is_array($value)){
                     UserAnswer::where( 'user_exam_id' , \request()->user_exam_id)->
                           where('question_id',$key)->delete();

                    $question = Question::select('id','mark')->where('id',$key)->withCount(['answers' => function($q){
                        return $q->where('check_correct',1);
                    }])->first();

                    foreach ($value as $answer){
                         $answer_db = Answer::select('id','check_correct')->where('id',$answer)->first();

                         UserAnswer::create([
                             'answer_id' => $answer,
                             'question_id' => $key,
                             'user_exam_id' => \request()->user_exam_id,
                             'mark' => $answer_db->check_correct == 0 ? 0 : ( $question->mark / $question->answers_count )
                         ]);

                        $user_answers =  DB::select(DB::raw('SELECT COUNT(answer_id) as user_answers FROM `user_answers` where user_exam_id = '.\request()->user_exam_id .' and question_id = '.$key));
                        $user_answers = $user_answers[0]->user_answers??0;
                         if($user_answers > $question->answers_count){
                             UserAnswer::where('user_exam_id',\request()->user_exam_id)->where('question_id',$key)->update(['mark' => 0]);
                         }
                     }
                 }
                 else{
                     $question = Question::select('id','mark')->where('id',$key)->first();
                     $answer_db = Answer::select('id','check_correct')->where('id',$value)->first();

                     UserAnswer::updateOrCreate([
                         'question_id' => $key,
                         'user_exam_id' => \request()->user_exam_id,
                     ],[
                         'answer_id' => $value,
                         'question_id' => $key,
                         'user_exam_id' => \request()->user_exam_id,
                         'mark' => ($answer_db->check_correct == 1 ? $question->mark : 0 )
                     ]);
                 }
            }
        }

        if (\request()->status == 'save'){
            $this->saveAndCalcMark($user_exam);
            return response(['status' => 'success' , 'redirect_route' => route('user.exam',$user_exam->exam->content_id)]);
        }
    }

    private function saveAndCalcMark($user_exam){

//        $user_exam = UserExam::whereId(\request()->user_exam_id)
//            ->select('id','exam_id')->with('exam')->first();
        $user_exam_id = \request()->user_exam_id;

                $grade =  DB::select( DB::raw("SELECT SUM(mark) as grade
                                FROM `user_answers`
                                WHERE user_exam_id = ". $user_exam_id ."
                                "));
        $user_exam->update([
            'status' => 1,
            'end_attempt' => Carbon::now(),
            'mark' => $grade[0]->grade??0
        ]);
        // mark
//        $grade =  DB::select( DB::raw("SELECT SUM(questions.mark) as grade
//                                FROM `user_answers`
//                                    INNER JOIN answers ON user_answers.answer_id = answers.id
//                                    INNER JOIN questions ON questions.id = answers.question_id
//                                WHERE user_exam_id = ". $user_exam_id ."
//                                AND answers.check_correct = 1"));
//
//        UserExam::where('id',$user_exam_id)->update([
//            'end_attempt' => Carbon::now(),
//            'mark' => $grade[0]->grade??0
//        ]);

//        $grade =  DB::select( DB::raw("SELECT questions.mark, correct_answer.total
//                    from questions
//                    inner join (
//                        SELECT user_answers.question_id
//                        , (COUNT(user_answers.question_id) / COUNT(answers.question_id)) as total
//
//                        FROM user_answers
//                        INNER JOIN answers ON user_answers.answer_id = answers.id
//                        WHERE user_exam_id = ".$user_exam_id."
//                        AND answers.check_correct = 1
//                        GROUP BY user_answers.question_id
//                    ) as correct_answer on questions.id = correct_answer.question_id"));
//
//        dd($grade);

        // new sql
        /* final query
           SELECT sum(t.total) FROM( SELECT user_answers.question_id , questions.mark * (COUNT(user_answers.question_id) / COUNT(answers.question_id)) as total  FROM user_answers
                INNER JOIN answers ON user_answers.answer_id = answers.id
                INNER JOIN questions ON questions.id = answers.question_id
         WHERE user_exam_id = 134
         AND answers.check_correct = 1
         GROUP BY user_answers.question_id) as t
        */
        /*
           SELECT user_answers.question_id , questions.mark * (COUNT(user_answers.question_id) / COUNT(answers.question_id))  FROM user_answers
                INNER JOIN answers ON user_answers.answer_id = answers.id
                INNER JOIN questions ON questions.id = answers.question_id
         WHERE user_exam_id = 134
         AND answers.check_correct = 1
         GROUP BY user_answers.question_id

         * */

        /*
               SELECT SUM(questions.mark) as grade
            FROM `user_answers`
                INNER JOIN answers ON user_answers.answer_id = answers.id
                INNER JOIN questions ON questions.id = answers.question_id
            WHERE user_exam_id = 41
            AND answers.check_correct = 1

        */
    }


    public function preview_exam($exam_id){
        $page_type = 'exam';
        $exam = Content::whereId($exam_id)
            ->with(['exam' => function($q){
                return $q->with(['users_exams' => function($query){
                    return $query->where('user_id',\auth()->id())->with('user_answers');
                }])->where('start_date','<=',Carbon::now())->where(function ($q){
                      $q->where('end_date','>',Carbon::now())->orWhere('end_date',null);
                });
            },'questions.answers:id,title,question_id','questions' => function($q){
                $q->withCount(['answers' => function ($query){
                    $query->where('check_correct' ,1);
                }]);
            }])->first();

        if (!$exam->exam || (count($exam->questions) == 0) ) abort(404);


        $without_timer = false;
        if($exam->exam->duration == 0){
            $without_timer = true;
        }


        $user_exams_count = count($exam->exam->users_exams);
        if (count($exam->exam->users_exams) > 0 && $exam->exam->users_exams[$user_exams_count-1]->status == 0){
            // duration time calc
            $start_user_attepmt = Carbon::now();
            $d = Carbon::parse($start_user_attepmt)
                ->addSeconds($exam->exam->duration * 60)
                ->format('Y-m-d H:i:s');;
            $d1 = strtotime($d);
            $d2 = strtotime($exam->exam->end_date);

                if( $d2 && ($d1 - $d2) > 0 ){
                    $exam->exam->duration =   $exam->exam->duration * 60 -  ($d1 - $d2);
                }else{
                    $d = Carbon::parse($start_user_attepmt)
                        ->format('Y-m-d H:i:s');;
                    $d1 = strtotime($d);
                    $d2 = strtotime($exam->exam->users_exams[$user_exams_count-1]->time);
                    $exam->exam->duration = ($exam->exam->duration * 60) -  ($d1 - $d2);
                }

            return view('userprofile::users.exam_preview',compact('exam','start_user_attepmt','page_type','without_timer'));
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
            if( $d2 && ($d1 - $d2) > 0){
                $exam->exam->duration =   $exam->exam->duration * 60 -  ($d1 - $d2);
            }else{
                $exam->exam->duration *= 60;
            }
            return view('userprofile::users.exam_preview',compact('exam','start_user_attepmt','page_type','without_timer'));

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

        if ( !$exam  ) abort(404);
        return view('userprofile::users.exam_preview',compact('exam','page_type'));

    }


    public function course_details($course_id){
        $course = Course::where('id',$course_id)->whereHas('users',function ($q){
            $q->where('users.id',\auth()->id());
        })->with(['users' => function($query){
            $query->where('user_id',\auth()->id());
        },'course_rate','uploads' => function($query){
            return $query->where(function ($q){
                $q->where('post_type','intro_video')->orWhere('post_type','image');
            });
        },'contents' => function($query){
            $query->where('post_type','section')->with(['details','contents.details','contents.user_contents' => function($q){
                return $q->where('user_id',\auth()->id());
            }]);
        }])->first();
        if (!$course){
            abort(404);
        }



        $total_rate = DB::select(DB::raw('SELECT AVG(rate) as total_rate FROM `courses_registration` WHERE course_id =' .$course->id));
        $total_rate = $total_rate[0]->total_rate;


        return view('userprofile::users.my_courses',compact('course','total_rate'));
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


        $user_contents_count = DB::select(DB::raw("SELECT COUNT(user_contents.id) as user_contents_count FROM user_contents
                                   INNER JOIN contents on user_contents.content_id = contents.id
                                   WHERE user_contents.user_id =".\auth()->id()."
                                   AND  contents.deleted_at IS NULL
                                   AND contents.course_id = ". $content->course_id ."

                             "));
        $user_contents_count = $user_contents_count[0]->user_contents_count??0;

        $contents_count = DB::select(DB::raw("SELECT COUNT(id) as contents_count
                                                            FROM contents

                                                            WHERE   course_id =". $content->course_id ." AND parent_id IS NOT NULL AND  deleted_at IS NULL"));
        $contents_count = $contents_count[0]->contents_count??0;

        CourseRegistration::where('course_id',$content->course_id)
            ->where('user_id',\auth()->id())->update(['progress'=> round(($user_contents_count / $contents_count) * 100 ,  1)  ]);




//        return $user_contents_count;


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

    public function my_courses() {
        $courses =  User::where('id',\auth()->id())->with(['courses.upload' => function($q){
            return $q->where('post_type','image')->where('locale',app()->getLocale());
        }])->first();
        return view('userprofile::users.my_courses',compact('courses'));
    }

    public function exercise() {
        return view('userprofile::users.exercise');
    }


    public function home() {
        $courses =  User::where('id',\auth()->id())->with(['courses.upload' => function($q){
            return $q->where('post_type','image');
        }])->first();
        $video = DB::select(DB::raw("SELECT user_contents.id , uploads.file FROM user_contents
                INNER JOIN contents ON  contents.id = user_contents.content_id
                INNER JOIN uploads  ON  contents.id = uploads.uploadable_id
            WHERE user_contents.user_id = ".\auth()->id()."
            AND uploads.uploadable_type = 'App\\\\Models\\\\Training\\\\Content'
            AND contents.post_type = 'video'
            AND contents.deleted_at IS NULL
            ORDER BY user_contents.id DESC LIMIT 1
            "));

        $last_video = $video[0];

        $next_videos = DB::select(DB::raw("SELECT id ,title  FROM contents
            WHERE id > ".$last_video->id."
            AND post_type = 'video'
            AND deleted_at IS NULL"));

//dd($next_videos);
        return view('userprofile::users.home',compact('courses','last_video','next_videos'));
    }


//////////////////////////////////////

    public function logout(Request $request) {
        Auth::logout();
        return redirect(route('user.login'));
    }


    public function login()
    {
        if(Auth::check()){
            return redirect(route('user.home'));
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
            return redirect(route('user.home'));
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


    public function change_password() {
        return view('userprofile::users.change_password');
    }

    public function save_password() {

        request()->validate([
            'old_password' => 'required|min:7|max:16',
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

        return redirect(route('user.home'));
//        return view('userprofile::users.home');
    }




    public function info() {
        $user = User::with(['upload'])->findOrFail(auth()->user()->id);
        $genders = Constant::where('parent_id', 42)->get();
        $countries = Constant::where('post_type', 'countries')->get();
//         return $user;
        return view('userprofile::users.info',compact('user', 'genders', 'countries'));
    }

    //    public function dashboard() {
//        //$user = User::find(auth()->user()->id);
//        $user = auth()->user();
//        //$latestCart = Cart
////        dd($user->upload->file??null);
//        return view('userprofile::users.index',compact('user'));
//    }



//    public function referral() {
//        return view('userprofile::users.referral');
//    }
//
//    public function complaintView($type) {
//        $user = Auth::user();
//        $courses = [];
//        // return $type;
//        if($user->carts->count() > 0) {
//            foreach ($user->carts as $cart){
//                $courses = Cart::where('course_id','!=',$cart->course->id)->with('course.uploads','session.trainingOption')->get();
//            }
//        }
//        if($type == 'my_complaints'){
//            $questions = ProfileQuestion::where('post_type','learn_complaints')->with('profile_answers.profile_question_users')->get();
//        }else{
//            $questions = ProfileQuestion::where('post_type','learn_refunds')->with('profile_answers.profile_question_users')->get();
//        }
//
//        return view('userprofile::users.complaint',compact('questions','user','type'));
//    }

//    public function complaintStore(ProfileRequest $request){
//        $post_type = '';
//        ($request->post_type == 'my_complaints') ? $post_type = 'learn_complaints' : $post_type = 'learn_refunds' ;
//        $profile = ProfileQuestionUser::updateOrCreate([
//            'user_id' => auth()->user()->id,
//            'course_id' => $request->courses,
//            'profile_answer_id' => $request->answer,
//            'profile_answer_text' => $request->reason,
//            'profile_question_id' => $request->question_id,
//            'post_type' => $post_type,
//        ]);
//
//        if($profile) {
//            session()->flash('success', __('education.Complaint has been sent successfully.'));
//        }else {
//            session()->flash('error', __('education.Failed to send complaint. Please send again.'));
//        }
//        return redirect()->route('user.my_complaints',$request->post_type);
//    }

//    public function myComplaints($type){
//        $user = Auth::user();
//        if($type == 'my_complaints'){
//            $complaints = ProfileQuestionUser::where('post_type','learn_complaints')->with('products')->get();
//        }else{
//            $complaints = ProfileQuestionUser::where('post_type','learn_refunds')->with('products')->get();
//        }
//        return view('userprofile::users.my_complaints',compact('complaints','user','type'));
//    }



    public function update($id,Request $request){

        $request->validate([
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $password = $user->password;

        if (Hash::check($request->password, $password)) {
            $password = Hash::make($request->new_password);
        }



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

        return redirect()->back();
    }




}
