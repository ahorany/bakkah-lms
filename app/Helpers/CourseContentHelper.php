<?php


namespace App\Helpers;


use App\Models\Training\UserContent;
use Illuminate\Support\Facades\DB;

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
        $sql = "SELECT COUNT(id) as contents_count
                FROM contents
                WHERE   course_id =". $course_id ."
                AND parent_id IS NOT NULL
                AND  deleted_at IS NULL
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
                        INNER JOIN contents AS sections ON contents.parent_id = sections.id
                        WHERE contents.course_id =  $course_id
                        AND contents.id !=  $content_id
                        AND contents.deleted_at IS NULL
                        AND (
                              (contents.order > $content_order  AND contents.parent_id = $content_parent_id)
                                OR
                              ( sections.order > ".$section_order.")
                            )
                         order BY sections.order , contents.order
                         LIMIT 1";

        $next =  DB::select(DB::raw($sql));


        $sql = "SELECT contents.id , contents.title,contents.post_type FROM `contents`
                       INNER JOIN contents AS sections ON contents.parent_id = sections.id
                       WHERE contents.course_id = $course_id
                       AND contents.id !=  $content_id
                       AND contents.deleted_at IS NULL
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
                $prev_user_content = UserContent::where('user_id',\auth()->id())
                    ->where('content_id',$previous->id)
                    ->where('is_completed',1)->first();

                if (!$prev_user_content){
                    return false;
                }
            }
        }
        return true;
    } // end function


} // End Class
