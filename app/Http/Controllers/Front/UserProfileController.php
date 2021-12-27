<?php

namespace App\Http\Controllers\Front;

use App\Models\Training\Answer;
use App\Models\Training\Content;
use App\Models\Training\CourseRegistration;
use App\Models\Training\Exam;
use App\Models\Training\Question;
use App\Models\Training\UserAnswer;
use App\Models\Training\UserContent;
use App\Models\Training\UserExam;
use App\Models\Training\UserQuestion;
use App\User;
use App\Constant;
use App\Models\Admin\Role;
use App\Models\Admin\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Training\Course;
use App\Models\Training\Message;
use App\Models\Training\Reply;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
<<<<<<< HEAD
    /************************************************************
     * UserProfileController: It is a class for User operations.
     *
     * ( Info page , Update Info , Logout ,
     *  Change Password Page,Update Changed Password)
     ***********************************************************/
=======
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'login', 'register', 'loginSubmit', 'registerSubmit', 'passwordReset', 'resetSubmit']]);

        $this->middleware('verified', ['except' => [
            'login', 'register', 'loginSubmit', 'registerSubmit', 'logout'
        ]]);
    }

    public function resume($course_id){
        $user_id = \auth()->id();
       $content =  DB::select(DB::raw("SELECT user_contents.content_id as content_id , contents.post_type as type  FROM courses
                                    INNER JOIN contents ON courses.id = contents.course_id
                                    INNER JOIN user_contents ON contents.id = user_contents.content_id
                                    WHERE courses.id = $course_id
                                    AND
                                    user_contents.user_id = $user_id
                                    AND
                                    courses.deleted_at IS NULL
                                    AND
                                    contents.deleted_at IS NULL
                                    AND
                                    contents.downloadable = 0
                                    ORDER BY user_contents.id DESC
                                    LIMIT 1"));

       if(count($content) > 0){
           $content_id = $content[0]->content_id;
           if($content[0]->type == "exam"){
               return redirect()->route('user.exam',$content_id);
           }else{
               return redirect()->route('user.course_preview',$content_id);
           }
       }else{
           abort(404);
       }
    }


    public function join_zoom(){
        return view('pages.zoom.join');
    }

    public function meeting(){
       $nickname = session()->get('nickname');
       $meetingId = session()->get('meetingId');
        return view('pages.zoom.meeting',compact('nickname','meetingId'));
    }

    public function add_join_zoom(Request $request){
        $nickname = $request->nickname;
        $meetingId = $request->meetingId;
        return redirect(route("user.meeting"))->with(['nickname' => $nickname , 'meetingId' => $meetingId]);
    }

    public function attempt_details ($user_exams_id){
        $exam = UserExam::whereId($user_exams_id)
            ->where('user_id',\auth()->id())
            ->where('status',1)
            ->with(['exam' => function($q){
                return $q->select('id','content_id')->with(['content' => function($query){
                    $query->select('id','title');
                }]);
            }])
            ->first();

        if ( !$exam  ) abort(404);

       $exam_title =  $exam->exam->content->title;
       $exam_id =  $exam->exam->content->id;

       $unit_marks =  DB::select(DB::raw("
            SELECT units.title as unit_title, questions.unit_id as unit_id , SUM(user_questions.mark) as unit_marks , SUM(questions.mark) as total_marks
            from questions
            left join user_questions ON user_questions.question_id = questions.id
            and user_questions.user_exam_id = $user_exams_id
            LEFT JOIN units ON questions.unit_id = units.id
            where questions.exam_id = $exam_id
            GROUP BY questions.unit_id
       "));

       $units_rprt = DB::select(" SELECT m.id, m.title, sum(m.res) as result ,count(m.question_id) as count,sum(m.tot) as total
       from (
           SELECT u.id, u.title, qu.question_id
           , (select uq.mark/count(id) from question_units where question_id = qu.question_id) as res
            , (select q.mark/count(id) from question_units where question_id = qu.question_id) as tot
           , uq.mark
           from units u
           join question_units qu on u.id=qu.unit_id
           join user_questions uq on uq.question_id = qu.question_id
           join user_exams ue on ue.id = uq.user_exam_id
           join questions q on q.id = uq.question_id
           where  ue.id = $user_exams_id
           order by u.id asc
                ) as m
                group by m.id
                union
            SELECT  ' ' as id, 'other' as title,sum(uq.mark) as result,count(uq.question_id) as count,sum(q.mark)
            from user_questions uq  join user_exams ue on ue.id = uq.user_exam_id
            join questions q on q.id = uq.question_id
            where  ue.id = $user_exams_id and uq.question_id not in (select question_id from question_units)
            ") ;

        // dd( $units_rprt);
    // dd($units_rprt);
        return view('pages.exam_details',compact('unit_marks','exam_title','exam_id','units_rprt'));

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


    // exam page
    public function exam($exam_id){

        $exam = Content::whereId($exam_id)
            ->with(['section','course', 'exam' => function($q){
                return $q->with(['users_exams' => function($q){
                    return $q->where('user_id',auth()->id());
                }]);
            }])->first();

        $user_course_register = CourseRegistration::where('course_id',$exam->course->id)->where('user_id',\auth()->id())->first();
        if(!$user_course_register){
            abort(404);
        }

        if (!$exam->exam) abort(404);

        UserContent::firstOrCreate([
            'user_id' => \auth()->id(),
            'content_id' => $exam_id,
        ],[
            'user_id'  => \auth()->id(),
            'content_id' => $exam_id,
        ]);


        $arr =  $this->nextAndPreviouseQuery($exam->course_id,$exam->id,$exam->order,$exam->parent_id,$exam->section->order);
        $previous = $arr[0];
        $next = $arr[1];


        $next = ($next[0]??null);
        $previous = ($previous[0]??null);

        return view('pages.exam',compact('exam','next','previous'));
    }



    // add user answers exam

    public function add_answers(){
        $user_exam =  UserExam::whereId(\request()->user_exam_id)
            ->where('user_id',\auth()->id())->where('status',0)->with(['exam.content'])->first();
        if (!$user_exam) abort(404);

        if(\request()->has('answer') && \request()->status != 'save'){
                if(is_array(\request()->answer)) {
                        UserAnswer::where( 'user_exam_id' , \request()->user_exam_id)->
                        where('question_id',request()->question_id)->delete();
                        $question = Question::select('id','mark')->where('id',request()->question_id)->with(['answers' => function($q){
                            return $q->where('check_correct',1);
                        }])->first();

                        $count_correct_answers = 0;
                        foreach (\request()->answer as $answer){
                            foreach ($question->answers as $question_answer){
                                if($question_answer->id == $answer){
                                    $count_correct_answers++;
                                }
                            }
                            UserAnswer::create([
                                'answer_id' => $answer,
                                'question_id' => request()->question_id,
                                'user_exam_id' => \request()->user_exam_id,
                            ]);
                        }

                        $mark = 0;
                        if(count(\request()->answer) > count($question->answers) ){
                            $mark = 0;
                        }else if($count_correct_answers == count($question->answers) ){
                            $mark = $question->mark;
                        }else{
                            $mark = $question->mark / count($question->answers);
                            $mark = $mark * $count_correct_answers;
                        }

                        UserQuestion::updateOrCreate([
                            'question_id' => \request()->question_id,
                            'user_exam_id' => \request()->user_exam_id,
                        ],[
                            'question_id' => \request()->question_id,
                            'user_exam_id' => \request()->user_exam_id,
                            'mark' => $mark
                        ]);

                }else{
                    $question = Question::select('id','mark')->where('id',\request()->question_id)->first();
                    $answer_db = Answer::select('id','check_correct')->where('id',\request()->answer)->first();

                    UserAnswer::updateOrCreate([
                        'question_id' => \request()->question_id,
                        'user_exam_id' => \request()->user_exam_id,
                    ],[
                        'answer_id' => \request()->answer,
                        'question_id' => \request()->question_id,
                        'user_exam_id' => \request()->user_exam_id,
                    ]);


                    UserQuestion::updateOrCreate([
                        'question_id' => \request()->question_id,
                        'user_exam_id' => \request()->user_exam_id,
                    ],[
                        'question_id' => \request()->question_id,
                        'user_exam_id' => \request()->user_exam_id,
                        'mark' => ($answer_db->check_correct == 1 ? $question->mark : 0 )
                    ]);
                }

        }


        // save answers
        if (\request()->status == 'save'){
            $this->saveAndCalcMark($user_exam->exam,$user_exam);
            return response(['status' => 'success' , 'redirect_route' => route('user.exam',$user_exam->exam->content_id)]);
        }
    }

    private function saveAndCalcMark($exam,$user_exam){
        $user_exam_id = \request()->user_exam_id;

        $grade =  DB::select( DB::raw("SELECT SUM(mark) as grade
                                FROM `user_questions`
                                WHERE user_exam_id = ". $user_exam_id ."
                                "));
        $grade = $grade[0]->grade??0;
        $user_exam->update([
            'status' => 1,
            'end_attempt' => Carbon::now(),
            'mark' => $grade
        ]);
        $content = $exam->content;
        // update progress
        if($content->role_and_path == 1 && ( ( ($exam->exam_mark * $exam->pass_mark) / 100 ) <= $grade) ){

            $user_contents_count = DB::select(DB::raw("SELECT COUNT(user_contents.id) as user_contents_count FROM user_contents
                                   INNER JOIN contents on user_contents.content_id = contents.id
                                   WHERE user_contents.user_id =".\auth()->id()."
                                   AND  contents.deleted_at IS NULL
                                   AND  contents.role_and_path = 1
                                   AND contents.course_id = ". $content->course_id ."

                             "));
            $user_contents_count = $user_contents_count[0]->user_contents_count??0;

            $contents_count = DB::select(DB::raw("SELECT COUNT(id) as contents_count
                                                            FROM contents
                                                            WHERE   course_id =". $content->course_id ."
                                                            AND parent_id IS NOT NULL
                                                            AND  deleted_at IS NULL
                                                            AND  role_and_path = 1
                                                            "));
            $contents_count = $contents_count[0]->contents_count??0;

            CourseRegistration::where('course_id',$content->course_id)
                ->where('user_id',\auth()->id())->update(['progress'=> round(($user_contents_count / $contents_count) * 100 ,  1)  ]);

        }
    }

    // start exam attempt
    public function preview_exam($exam_id){

        $page_type = 'exam';
        $exam = Content::whereId($exam_id)
            ->with(['course','exam' => function($q){
                return $q->with(['users_exams' => function($query){
                    return $query->where('user_id',\auth()->id())->with('user_answers');
                }])->where('start_date','<=',Carbon::now())->where(function ($q){
                      $q->where('end_date','>',Carbon::now())->orWhere('end_date',null);
                });
            },'questions.answers' => function($q){
                return $q->select('id','title','question_id')->inRandomOrder();
            },'questions' => function($q){
                $q->select('id','title','mark','exam_id','unit_id')->withCount(['answers' => function ($query){
                    $query->where('check_correct' ,1);
                }]);
            }])->first();

        $user_course_register = CourseRegistration::where('course_id',$exam->course->id)->where('user_id',\auth()->id())->first();
        if(!$user_course_register){
            abort(404);
        }

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

            return view('pages.exam_preview',compact('exam','start_user_attepmt','page_type','without_timer'));
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
            return view('pages.exam_preview',compact('exam','start_user_attepmt','page_type','without_timer'));

        }else{
            abort(404);
        }
    }

    private function checkDurationExam($exam_duration){
        $start_user_attepmt = Carbon::now();
        $d = Carbon::parse($start_user_attepmt)
            ->addSeconds($exam_duration * 60)
            ->format('Y-m-d H:i:s');;
    }

    public function review_exam($exam_id){
        $page_type = 'review';


        $exam = UserExam::whereId($exam_id)
            ->where('user_id',\auth()->id())
            ->where('status',1)
            ->with(['exam.content.questions.answers','user_answers','user_questions'])
            ->first();

        if( $exam->exam && $exam->exam->end_date > Carbon::now() ){
            abort(404);
        }

        if ( !$exam  ) abort(404);
        return view('pages.review',compact('exam','page_type'));

    }


    public function course_details($course_id){
        session()->put('infastructure_id',-1);
        $course = Course::where('id',$course_id)->whereHas('users',function ($q){
            $q->where('users.id',\auth()->id());
        })->with(['users' => function($query){
            $query->where('user_id',\auth()->id());
        },'course_rate' => function($query){
             return $query->where('user_id',\auth()->id());
        },'uploads' => function($query){
            return $query->where(function ($q){
                $q->where('post_type','intro_video')->orWhere('post_type','image');
            });
        },'contents' => function($query){
            $query->where('post_type','section')->with(['details',
            'contents' => function($q){
                return $q->orderBy('order');
            },
            'contents.details','contents.user_contents' => function($q){
                return $q->where('user_id',\auth()->id());
            }])->orderBy('order');
        }])->first();
        if (!$course){
            abort(404);
        }
//        return $course->contents[0]->contents[0]->user_contents ;



        $total_rate = DB::select(DB::raw('SELECT AVG(rate) as total_rate FROM `courses_registration` WHERE course_id =' .$course->id));
        $total_rate = $total_rate[0]->total_rate??0;

        $date_now = Carbon::now();
        $user_id = \auth()->id();
        $course_id = $course->id;

        $activities = DB::select(DB::raw("SELECT contents.id as content_id,contents.post_type as type,
                                                courses.title as course_title,contents.title as content_title
                                                FROM contents
                                    INNER JOIN user_contents ON user_contents.content_id = contents.id
                                    INNER JOIN courses ON contents.course_id = courses.id
                                    INNER JOIN courses_registration ON courses_registration.course_id = courses.id
                                    WHERE
                                    contents.deleted_at IS NULL
                                    AND
                                     user_contents.user_id = $user_id
                                    AND
                                     courses_registration.user_id = $user_id
                                     AND
                                     courses.id = $course_id
                                     ORDER BY contents.order DESC
                                     LIMIT 5
                          "));

        return view('pages.course_details',compact('course','total_rate','activities'));
    }

    public function course_preview($content_id){
        $content = Content::whereId($content_id)
            ->with(['upload','course' ,'section'])->first();
        if (!$content){
            abort(404);
        }

        $user_course_register = CourseRegistration::where('course_id',$content->course->id)->where('user_id',\auth()->id())->first();

        $role_auth_is_admin = \auth()->user()->roles()->first();


        if(!$user_course_register && ($role_auth_is_admin && $role_auth_is_admin->id != 1)){
            abort(404);
        }

        UserContent::firstOrCreate([
            'user_id' => \auth()->id(),
            'content_id' => $content_id,
        ],[
            'user_id'  => \auth()->id(),
            'content_id' => $content_id,
        ]);

    if($content->role_and_path == 1){
        $user_contents_count = DB::select(DB::raw("SELECT COUNT(user_contents.id) as user_contents_count FROM user_contents
                                   INNER JOIN contents on user_contents.content_id = contents.id
                                   WHERE user_contents.user_id =".\auth()->id()."
                                   AND  contents.deleted_at IS NULL
                                   AND  contents.role_and_path = 1
                                   AND contents.course_id = ". $content->course_id ."

                             "));
        $user_contents_count = $user_contents_count[0]->user_contents_count??0;

        $contents_count = DB::select(DB::raw("SELECT COUNT(id) as contents_count
                                                            FROM contents
                                                            WHERE   course_id =". $content->course_id ."
                                                            AND parent_id IS NOT NULL
                                                            AND  deleted_at IS NULL
                                                            AND  role_and_path = 1
                                                            "));
        $contents_count = $contents_count[0]->contents_count??0;

        CourseRegistration::where('course_id',$content->course_id)
            ->where('user_id',\auth()->id())->update(['progress'=> round(($user_contents_count / $contents_count) * 100 ,  1)  ]);

 }


    // if downloadable status == true (Download file)
        if($content->downloadable == 1){
            $file = "";
            switch ($content->post_type){
                case 'video': $file = public_path('upload/files/videos/'.$content->upload->file);  break;
                case 'audio': $file = public_path('upload/files/audios/'.$content->upload->file);  break;
                case 'presentation': $file = public_path('upload/files/presentations/'.$content->upload->file);  break;
                case 'scorm': $file = public_path('upload/files/scorms/'.$content->upload->file);  break;
            }
            if ($file){
                return response()->download($file);
            }


        }

      $arr =  $this->nextAndPreviouseQuery($content->course_id,$content->id,$content->order,$content->parent_id,$content->section->order);

        $previous = $arr[0];
        $next = $arr[1];


        $next = ($next[0]??null);
        $previous = ($previous[0]??null);

        return view('pages.file',compact('content','previous','next'));
    }

>>>>>>> 2ee991f8503e33a8e5dc0c50e2c6dcd66d1b914a



    public function __construct()
    {
        // User Must Be Auth To Use Any Method In This Class
        $this->middleware('auth');
    }




    /*
     *  Logout Auth User
     */
    public function logout(Request $request) {
        Auth::logout();
        return redirect(route('login'));
    } // end function





    /*
     * Change Password Page
     */
    public function change_password() {
        return view('pages.change_password');
    } // end function




    /*
     * Save Changed Password
     */
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
    } // end function




    /*
     * User Info Page
     */
    public function info() {
        $user = User::with(['upload'])->findOrFail(auth()->user()->id);
        $genders = Constant::where('parent_id', 42)->get();
        $countries = Constant::where('post_type', 'countries')->get();
        return view('pages.info',compact('user', 'genders', 'countries'));
    } // end function





    /*
     * Update User Info
     */
    public function update($id,Request $request){

        $request->validate([
            'en_name' => 'required',
            'ar_name' => 'required',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'language' => 'required',
            'gender_id' => 'required|exists:constants,id',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name'      => json_encode(["en" => $request->en_name, "ar" => $request->ar_name]),
            'headline'  => $request->headline,
            'lang'      => $request->language,
            'bio'       => $request->bio,
            'mobile'    => $request->mobile,
            'gender_id' => $request->gender_id,
            'company' => $request->company,
            'job_title' => $request->job_title,
        ]);

        User::UploadFile($user, ['method'=>'update']);

        return redirect()->back();
    } // end function



    ////////////////  Zoom Test  /////////////////////

//    public function join_zoom(){
//        return view('pages.zoom.join');
//    }
//
//    public function meeting(){
//        $nickname = session()->get('nickname');
//        $meetingId = session()->get('meetingId');
//        return view('pages.zoom.meeting',compact('nickname','meetingId'));
//    }
//
//    public function add_join_zoom(Request $request){
//        $nickname = $request->nickname;
//        $meetingId = $request->meetingId;
//        return redirect(route("user.meeting"))->with(['nickname' => $nickname , 'meetingId' => $meetingId]);
//    }



} // End Class
