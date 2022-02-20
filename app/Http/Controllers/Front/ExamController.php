<?php

namespace App\Http\Controllers\Front;

use App\Helpers\CourseContentHelper;
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
use App\Models\Training\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Training\Course;
use App\Models\Training\Message;
use App\Models\Training\Reply;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    /******************************************************
     * ExamController: It is a class for Exam operations
     * on the user's side.
     *
     * ( Exam page , Start Attempt ,Complete Last Attempt,
     *  Calc Attempt Marks , Attempt Details Page,
     *  Review Attempt Page  , Add User Answers  )
     *****************************************************/



    public function __construct()
    {
        // User Must Be Auth To Use Any Method In This Class
        $this->middleware('auth');
    }

   /****************************************************************************/

    /*
     *  Exam Page Before Attempt => (This Page To Start Attempt)
     */
    public function exam($exam_id){

        // Get Exam Content
        $exam = Content::whereId($exam_id)
        ->with(['section','course','exam' => function($q){
            return $q->with(['users_exams' => function($q){
                return $q->where('user_id', auth()->id());
            }]);
        }])->first();

        $user_course_register = $this->checkUserCourseRegistration($exam->course->id);

        // Validate User Course Registration And Exam is Exists Or Not
        if( !$user_course_register || (!$exam->exam) ){
            abort(404);
        }

        // Get Next And Previous Contents Data
        // $arr =  CourseContentHelper::nextAndPreviouseQuery($exam->course_id,$exam->id,$exam->order,$exam->parent_id,$exam->section->order);
        // $previous = $arr[0];
        // $previous = ($previous[0]??null);
        // $next = $arr[1];
        // $next = ($next[0]??null);

        // Get Next And Previous Contents Data
        $arr = CourseContentHelper::NextAndPreviouseNavigation($exam);
        $next = $arr['next'];
        $previous = $arr['previous'];
        // end next and prev

        // Validate prev if completed or not =>  ( IF not redirect back with alert msg )
        if(!CourseContentHelper::checkPrevContentIsCompleted($exam->status , $previous) && $user_course_register->role_id==3){
            return redirect()->back()->with(["status" => 'danger',"msg" => "Can not open  content (Because the content is not completed) !!"]);
        }// end if

        // Create UserContent When is not exists
        UserContent::firstOrCreate([
            'user_id' => \auth()->id(),
            'content_id' => $exam_id,
        ],[
            'user_id'  => \auth()->id(),
            'content_id' => $exam_id,
            'start_time' => Carbon::now(),
        ]);

        return view('pages.exam',compact('exam','next','previous'));
    } // end function


    /*
     * Check User Course Registration
     */
    private function checkUserCourseRegistration($course_id){

        return CourseRegistration::where('course_id',$course_id)
            ->where('user_id',\auth()->id())
            ->first();
    } // end function


    /****************************************************************************/


    /*
     *  Start Exam Attempt
     */
    public function preview_exam($exam_id){
        $page_type = 'exam';

        $exam = $this->getExamDataFromDB($exam_id);

        // Validate User Course Registration
        $user_course_register = $this->checkUserCourseRegistration($exam->course->id);
        if(!$user_course_register){  abort(404);  } // end if

        // Check If Exam Has Contents And Questions
        if (!$exam->exam || (count($exam->questions) == 0) ){ abort(404); } // end if


        // Is Exam Without Timer Or Not
        $without_timer = $this->isExamWithoutTimer($exam->exam->duration);


        // Get UserExams (Attempts) Count For This Exam
        $user_exams_count = count($exam->exam->users_exams);


        /*
         * Case 1 : Complete Last Attempt
         *
         *  If UserExams (Attempts) > 0 And Last Attempt Status (Not Complete)
         */
        if (count($exam->exam->users_exams) > 0 && $exam->exam->users_exams[$user_exams_count-1]->status == 0){
            $data = $this->completeExamAttempt($exam->exam->duration,$exam->exam->end_date,$exam->exam->users_exams[$user_exams_count-1]->time);
            $start_user_attepmt = $data['start_user_attepmt'];
            $exam->exam->duration = $data['exam_duration'];
            return view('pages.exam_preview',compact('exam','start_user_attepmt','page_type','without_timer'));
        }


        /*
         * Case 2 : Create New Attempt
         *
         * If (UserExams (Attempts) < total exam attempts)
         * OR (total exam attempts == 0 => (Infinity Attempts) )
         * Else ABORT(404)
         */
        if ( $user_exams_count < $exam->exam->attempt_count ||  $exam->exam->attempt_count == 0){
            $data = $this->createNewExamAttempt($exam->exam->id,$exam->exam->duration,$exam->exam->end_date);
            $start_user_attepmt = $data['start_user_attepmt'];
            $exam->exam->users_exams->push($data['push_data_in_user_exam']);
            $exam->exam->duration = $data['exam_duration'];
            return view('pages.exam_preview',compact('exam','start_user_attepmt','page_type','without_timer'));
        }else{
            abort(404);
        }

    } // end function


    /*
     * Get Exam Data From DB When Start Attempt
     */
    private function getExamDataFromDB($exam_id){
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

        return $exam;
    } // end function


    /*
     * Check Is Exam Without Timer Or Not ( Exam Duration == 0)
     */
    private function isExamWithoutTimer($exam_duration){
        if($exam_duration == 0){
            return true;
        }
        return false;
    } // end function

    /*
     *  Calc Duration For Complete Exam Last Attempt
     */
    private function completeExamAttempt($exam_duration,$exam_end_date,$last_attempt_time){
        // Duration Remain Time Calc To Complete Exam
        $start_user_attepmt = Carbon::now();
        $d = Carbon::parse($start_user_attepmt)
            ->addSeconds($exam_duration * 60)
            ->format('Y-m-d H:i:s');;
        $d1 = strtotime($d);
        $d2 = strtotime($exam_end_date);

        if( $d2 && ($d1 - $d2) > 0 ){
            $exam_duration =   $exam_duration * 60 -  ($d1 - $d2);
        }else{
            $d = Carbon::parse($start_user_attepmt)
                ->format('Y-m-d H:i:s');;
            $d1 = strtotime($d);
            $d2 = strtotime($last_attempt_time);
            $exam_duration = ($exam_duration * 60) -  ($d1 - $d2);
        }

        return [
            'start_user_attepmt' => $start_user_attepmt,
            'exam_duration' => $exam_duration,
        ];

    } // end function


    /*
     * Create New Attempt And Calc Start Time Duration
     */
    private function createNewExamAttempt($exam_id,$exam_duration,$exam_end_date){
        $start_user_attepmt = Carbon::now();
        $data = UserExam::create([
            'user_id' => \auth()->id() ,
            'exam_id' => $exam_id,
            'status' => 0,
            'time' => $start_user_attepmt,
        ]);

        // duration start exam timer calc
        $d = Carbon::parse($start_user_attepmt)
            ->addSeconds($exam_duration * 60)
            ->format('Y-m-d H:i:s');;
        $d1 = strtotime($d);
        $d2 = strtotime($exam_end_date);
        if( $d2 && ($d1 - $d2) > 0){
            $exam_duration =  $exam_duration * 60 -  ($d1 - $d2);
        }else{
            $exam_duration *= 60;
        }

        return [
            'start_user_attepmt' => $start_user_attepmt,
            'exam_duration' => $exam_duration,
            'push_data_in_user_exam' => $data
        ];
    } // end function




    /****************************************************************************/



    /*
     * Add User Attempt Answers (Send Data From JS AJAX Request)
     */
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
    } // end function



    /*
     * Save And Calc User Exam Attempt And Finish Attempt
     */
    private function saveAndCalcMark($exam,$user_exam){
        $user_exam_id = \request()->user_exam_id;

        // Calc Grade For Attempt
        $grade = CourseContentHelper::calcAttemptGrade($user_exam_id);

        // Update Attempt Mark And Status (Attempt => Complete)
        $user_exam->update([
            'status' => 1,
            'end_attempt' => Carbon::now(),
            'mark' => $grade
        ]);


        /*
         * Update User Course Progress
         * When Content Have Role And Path && Grade Attempt >= Exam Pass Mark
         */
        if( $exam->content->role_and_path == 1 && ((($exam->exam_mark * $exam->pass_mark) / 100) <= $grade) ){
             $this->updateUserCourseProgress( $exam->content->course_id);
          } // end if


         // Update User Content (Exam) Complete Status When Grade Attempt >= Exam Pass Mark
         $this->updateExamCompleteStatus($grade,$user_exam->exam->content->id,$exam->exam_mark,$exam->pass_mark);


    } // end function




    /*
     * Update UserContent (Exam) ->> Complete Status
     * When Grade Attempt >= Exam Pass Mark
     */
    private function updateExamCompleteStatus($grade,$content_id,$exam_mark,$exam_pass_mark){
        // Check IF Grade Attempt >= Exam Pass Mark
        if ( (($exam_mark * $exam_pass_mark) / 100) <= $grade){
            UserContent::where('user_id',\auth()->id())
                ->where('content_id',$content_id)
                ->update([
                    "is_completed" => 1,
                ]);
        } // end if
    } // end function


    /*
     * Update User Course Progress
     * When Content Have Role And Path && Grade Attempt >= Exam Pass Mark
     */
    private function updateUserCourseProgress($course_id){
        // Get User Contents Count
        $user_contents_count = CourseContentHelper::getUserCourseCountsCount($course_id);

        // Get Course Contents Count
        $contents_count = CourseContentHelper::getCourseContentsCount($course_id);


        // Update User Course Progress In CourseRegistration
        CourseRegistration::where('course_id',$course_id)
            ->where('user_id',\auth()->id())
            ->update([
                'progress'=> round(($user_contents_count / $contents_count) * 100 ,  1)
            ]);

    } // end function


    /****************************************************************************/




    /*
     * Attempt Details Page (Exam Marks Per Unit)
     */
    public function attempt_details ($user_exams_id){
        // Get UserExam (Attempt) Data
        $exam = UserExam::whereId($user_exams_id)
            ->where('user_id',\auth()->id())
            ->where('status',1)
            ->with(['exam' => function($q){
                return $q->select('id','content_id')->with(['content' => function($query){
                    $query->select('id','title');
                }]);
            }])
            ->first();

        // Check If Attempt Is Exists
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
            SELECT  ' ' as id, 'Other' as title,sum(uq.mark) as result,count(uq.question_id) as count,sum(q.mark)
            from user_questions uq  join user_exams ue on ue.id = uq.user_exam_id
            join questions q on q.id = uq.question_id
            where  ue.id = $user_exams_id and uq.question_id not in (select question_id from question_units)
            ") ;

        return view('pages.exam_details',compact('unit_marks','exam_title','exam_id','units_rprt'));
    } // end function



    /****************************************************************************/


    /*
     * Review Exam Page (User Review Answers From UserExam (Attempt) )
     */
    public function review_exam($exam_id){
        $page_type = 'review';

        // Get UserExam (Attempt) Data
        $exam = UserExam::whereId($exam_id)
            ->where('user_id',\auth()->id())
            ->where('status',1)
            ->with(['exam.content.questions.answers','user_answers','user_questions'])
            ->first();


        /*
         * Validate If Exam Expired Or Not
         * (User Can Open Review Page When exam end date is expired)
         */
        if( $exam->exam && $exam->exam->end_date > Carbon::now() ){
            abort(404);
        }

        if ( !$exam  ) abort(404);

        // Get next and prev
        $arr = CourseContentHelper::nextAndPreviouseQuery($exam->exam->content->course_id,$exam->exam->content->id,$exam->exam->content->order,$exam->exam->content->parent_id,$exam->exam->content->section->order);
        $previous = $arr[0];
        $next = $arr[1];
        $next = ($next[0]??null);
        $previous = ($previous[0]??null);
        // end next and prev

        return view('pages.review',compact('exam','page_type','next','previous'));

    } // end function




    /****************************************************************************/


} // End Class
