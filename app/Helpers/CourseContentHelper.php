<?php


namespace App\Helpers;

use App\Models\Training\Content;
use App\Models\Training\Exam;
use App\Models\Training\UserContent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CourseContentHelper
{
    /**********************************************************************
     *  CourseContentHelper: It is a helper class for all common
     *  operations => (Course , Content , Exam) on the user's side.
     *
     *  (Calc Attempt Marks , User Contents Count , Course Contents Count ,
     *   Next And Previous Contents , )
     **********************************************************************/



    /*
     *  Calc Grade For Attempt
    */
    public static function calcAttemptGrade($user_exam_id){
        $grade =  DB::select( DB::raw("SELECT SUM(mark) as grade
                                FROM `user_questions`
                                WHERE user_exam_id = ". $user_exam_id ."
                                "));
        return $grade[0]->grade??0;
    } // end function


    /*
     * Get User Contents Count
    */
    public static function getUserCourseCountsCount($course_id){
        $sql = "SELECT COUNT(user_contents.id) as user_contents_count FROM user_contents
               INNER JOIN contents on user_contents.content_id = contents.id
               INNER JOIN courses on courses.id = contents.course_id AND courses.deleted_at IS NULL AND courses.branch_id = ".getCurrentUserBranchData()->branch_id."
               WHERE user_contents.user_id =".\auth()->id()."
               AND  contents.deleted_at IS NULL
               AND  contents.role_and_path = 1
               AND contents.course_id = ". $course_id ."
               ";

        $user_contents_count = DB::select(DB::raw($sql));
        $user_contents_count = $user_contents_count[0]->user_contents_count??0;
        return $user_contents_count;
    }


    /*
     * Get Course Contents Count
    */
    public static function getCourseContentsCount($course_id){
        // Get Contents Count
        $sql = "SELECT COUNT(contents.id) as contents_count
                FROM contents
                INNER JOIN courses on courses.id = contents.course_id AND courses.deleted_at IS NULL AND courses.branch_id = ".getCurrentUserBranchData()->branch_id."
                WHERE   course_id =". $course_id ."
                AND parent_id IS NOT NULL
                AND  contents.deleted_at IS NULL
                AND  role_and_path = 1
                ";
        $contents_count = DB::select(DB::raw($sql));
        $contents_count = $contents_count[0]->contents_count??0;
        return $contents_count;
    }



    /*
      * Get Next And Previous Contents Data
    */
    public static function nextAndPreviouseQuery($course_id,$content_id,$content_order,$content_parent_id,$section_order){

        $sql = "SELECT contents.id , sections.id as section_id , contents.order,sections.order as section_order , contents.title,contents.post_type FROM `contents`
        INNER JOIN contents AS sections ON contents.parent_id = sections.id AND sections.deleted_at IS NULL
        INNER JOIN courses on courses.id = contents.course_id AND courses.deleted_at IS NULL AND courses.branch_id = ".getCurrentUserBranchData()->branch_id."
        WHERE contents.course_id =  $course_id
        AND contents.id !=  $content_id
        AND contents.deleted_at IS NULL
        AND sections.hide_from_trainees = 0
        AND (
                (contents.order > $content_order  AND contents.parent_id = $content_parent_id)
                OR
                ( sections.order > ".$section_order.")
            )
            order BY sections.order , contents.order
            LIMIT 1";

        $next =  DB::select(DB::raw($sql));


        $sql = "SELECT contents.id , contents.title,contents.post_type FROM `contents`
                       INNER JOIN contents AS sections ON contents.parent_id = sections.id AND sections.deleted_at IS NULL
                       INNER JOIN courses on courses.id = contents.course_id AND courses.deleted_at IS NULL AND courses.branch_id = ".getCurrentUserBranchData()->branch_id."
                       WHERE contents.course_id = $course_id
                       AND contents.id !=  $content_id
                       AND contents.deleted_at IS NULL
                       AND sections.hide_from_trainees = 0
                       AND  (
                              (contents.order < $content_order AND contents.parent_id = $content_parent_id)
                                OR
                              ( sections.order < ".$section_order.")
                              )
                        ORDER BY sections.order DESC
                        ,contents.order DESC
                        LIMIT 1";

        $previous =  DB::select(DB::raw($sql));

        return [$previous,$next];
    } // end function


    /*
      *  Validate prev if completed or not =>  ( IF not redirect back with alert msg )
    */
    public static function checkPrevContentIsCompleted($content_status , $previous){

        if($content_status != 1){

            if($previous){

                $content = Content::find($previous->id);
                $pass_mark = -1;
                if($content->post_type=='exam'){
                    $exam = Exam::where('content_id', $previous->id)->select('pass_mark')->first();
                    $pass_mark = $exam->pass_mark;
                }

                if($pass_mark != 0){

                    if($content->post_type=='scorm'){
                        // $user_id = sprintf("%'.05d", auth()->user()->id);
                        // $content_id = sprintf("%'.05d", $content->id);
                        // $SCOInstanceID = (1).$user_id.(2).$content_id;
                        $SCOInstanceID = ScormId($content->id);

                        $sql = "SELECT * FROM `scormvars`
                                    WHERE SCOInstanceID = $SCOInstanceID AND
                                    varName = 'cmi.core.lesson_status'
                                  ";

                        $scormPrevDataWhenComplete =  DB::select(DB::raw($sql));
                        $scormPrevDataWhenComplete = ($scormPrevDataWhenComplete[0]??null);
                        if (!is_null($scormPrevDataWhenComplete) && $scormPrevDataWhenComplete->varValue != "completed"){
                            return false;
                        }

                    }else{
                        $prev_user_content = UserContent::where('user_id',\auth()->id())
                            ->where('content_id', $previous->id)
                            ->where('is_completed', 1)
                            ->first();

                        if (!$prev_user_content){
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    } // end function

    public static function NextAndPreviouseNavigation($content){

        $arr = self::nextAndPreviouseQuery($content->course_id, $content->id, $content->order, $content->parent_id, $content->section->order);
        $previous = $arr[0];
        $next = $arr[1];
        $next = ($next[0]??null);
        $previous = ($previous[0]??null);

        return compact('next', 'previous');
    }

    public static function NextPrevNavigation($next, $previous){

        $next_url = '';
        $previous_url = '';
        if( !is_null($next)){
            if( $next->post_type != 'exam') {
                $next_url = CustomRoute('user.course_preview', $next->id);
            }else{
                if(Gate::allows('preview-gate')){
                    $next_url =  CustomRoute('training.add_questions', $next->id);
                }
                else{
                    $next_url =  CustomRoute('user.exam', $next->id);
                }
            }
        }

        if(!is_null($previous)){
            if($previous->post_type != 'exam'){
                $previous_url = CustomRoute('user.course_preview', $previous->id);
            }else{
                if(Gate::allows('preview-gate')){
                    $previous_url =  CustomRoute('training.add_questions', $previous->id);
                }
                else{
                    $previous_url =  CustomRoute('user.exam', $previous->id);
                }
            }
        }

        if(Gate::allows('preview-gate')){
            $next_url .= '?preview=true';
            $previous_url .= '?preview=true';
        }
        return compact('next_url', 'previous_url');
    }

} // End Class
