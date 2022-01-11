<?php

namespace App\Http\Controllers\Front;

use App\Helpers\CourseContentHelper;
use App\Models\Training\Content;
use App\Models\Training\CourseRegistration;
use App\Models\Training\UserContent;
use Carbon\Carbon;
use App\Models\Training\Course;
use App\Models\Training\UserContentsPdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;


class CourseController extends Controller
{
    /*********************************************************
     * CourseController: It is a class for all course
     * operations and content on the user's side.
     *
     * (Course page , Course rate , Preview Content ,
     *  Resume Content, Update UserContent Completed Status)
     ********************************************************/



    public function __construct()
    {
        // User Must Be Auth To Use Any Method In This Class
        $this->middleware('auth');
    }


    /****************************************************************************/

    /*
     *  Course User page
     */
    public function course_details($course_id){

        // clear session => (Sidebar active color)
        session()->put('infastructure_id',-1);

       // Get Course With Contents
        $course = $this->getCourseWithContents($course_id);

        // validate if course exists or not
        if(!$course){
            abort(404);
        }// end if


        // Get total rate for course
        $total_rate = $this->getTotalRateForCourse($course->id);

        // Get User Course Activities
        $activities = $this->getUserCourseActivities($course->id,\auth()->id());
        $course_registration_id = CourseRegistration::where('course_id',$course->id)->where('user_id',\auth()->id())->pluck('id')->first();

        return view('pages.course_details',compact('course','total_rate','activities','course_registration_id'));
    } // end function


    /*
     * Get Course With Contents
     */
    private function getCourseWithContents($course_id){
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

        return $course;
    } // end function


    /*
     * Get total rate for course
     */
    private function getTotalRateForCourse($course_id){
        $sql = 'SELECT AVG(rate) as total_rate
                FROM `courses_registration`
                WHERE course_id =' .$course_id;
        $total_rate = DB::select(DB::raw($sql));
        $total_rate = $total_rate[0]->total_rate??0;
        return $total_rate;
    } // end function


    /*
     * Get User Course Activities
     */
    private function getUserCourseActivities($course_id,$user_id){
        $sql = "SELECT contents.id as content_id,contents.post_type as type,
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
                         LIMIT 5";
        $activities =  DB::select(DB::raw($sql));
        return $activities;
   } // end function



    /****************************************************************************/



    /*
     * Resume Button Action in course page
     */
    public function resume($course_id){
        // Get resume content
        $content =  $this->getResumeContent($course_id,\auth()->id());

        /*
         * IF exists content and
         * IF (type == exam) redirect to exam page
         * Else redirect to content page
         */
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
    } // end function


    /*
     *  Get resume content
     */
    private function getResumeContent($course_id,$user_id){
        $sql = "SELECT user_contents.content_id as content_id , contents.post_type as type  FROM courses
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
                    LIMIT 1";
        $content = DB::select(DB::raw($sql));
        return $content;
    }// end function


   /****************************************************************************/


     /*
     *  User rate course action in course page
     */
    public function user_rate(){
        // Check user Course Registration Or Not
      $course_registration = CourseRegistration::where('user_id',\auth()->id())
            ->where('course_id',\request()->course_id)->first();
        if (!$course_registration){
             return response()->json(['status' => 'error']);
        }

        //  User update course rate
        $course_registration->update(['rate' => \request()->rate]);

        // Get total rate for course
        $total_rate = $this->getTotalRateForCourse(\request()->course_id);

        return response()->json(['status' => 'success','data' => $total_rate ]);
    } // end function

    /****************************************************************************/
    /*
     * Preview Content Course ( Content Page )
     */
    public function preview_content($content_id){

        // Get content from DB
        $content = Content::whereId($content_id)
            ->with(['upload','course' ,'section'])->first();

        // dd($content);
        // Check if content not found => ABORT(404)
        if (!$content){
            abort(404);
        }// end if


        // Check if user is not register in course AND user role not admin => ABORT(404)
        $user_course_register = $this->checkUserCourseRegistrationAndRole($content->course->id);
        if(!$user_course_register){
            abort(404);
        }// end if

        // Get next and prev
        $arr = CourseContentHelper::nextAndPreviouseQuery($content->course_id,$content->id,$content->order,$content->parent_id,$content->section->order);
        $previous = $arr[0];
        $next = $arr[1];
        $next = ($next[0]??null);
        $previous = ($previous[0]??null);
        // end next and prev

        //TODO: Ahoray
        // Validate prev if completed or not =>  ( IF not redirect back with alert msg )
        if(!request()->has('preview')){
            if(!CourseContentHelper::checkPrevContentIsCompleted($content->status , $previous)){
                return redirect()->back()->with(["status" => 'danger',"msg" => "You can only go to the next page if you have completed the content"]);
            }// end if
        }

       /*
        *  Create New User Content OR
        *  Update Start Time For User Content
        *  When User Open Content Page AND User Content Not Completed
        */
        $is_completed = $this->createUserContentOrUpdate($content_id,$content->time_limit);


        // Check content is completed => enabled next button
        $enabled = true;
        if ($is_completed != 1){
            $enabled = false;
        }// end if


       // Update Course Registration Progress When content have (role and path)
        $this->updateCourseRegistrationProgress($content->course_id,$content->role_and_path);


       // if downloadable status == true (Download file)
        if($content->downloadable == 1){
            // get content file path
            $file = $this->getContentFilePath($content->post_type);
            if ($file){
                return response()->download($file); // download response
            }
        }// end if


        // get time limit content (duration in seconds)
        $time_limit = $content->time_limit;


        // Check user progress if grater than or equal complete progress for course
        // When user course => completed_at is null => update completed at In Course Registration
        // pop up status => preview else disable
        $popup_compelte_status = false;
        if ( ($content->course->complete_progress <= $user_course_register->progress)){
            // Check if user course => completed_at is null => update completed at In Course Registration
            if (!$user_course_register->completed_at){
               CourseRegistration::where('user_id',auth()->id())
                   ->where('course_id', $content->course->id)->update([
                       'completed_at' => Carbon::now(),
                   ]);

                $popup_compelte_status = true;
            } // end if
        } // end if
        $page_num = UserContentsPdf::where('content_id',$content->id)->where('user_id',auth()->user()->id)->pluck('current_page')->first();
        return view('pages.file',compact('content','previous','next','enabled','time_limit','popup_compelte_status','page_num'));
    } // end function


    /*
     *  IF user is not register in course AND user role not admin => return false
     */
    private function checkUserCourseRegistrationAndRole($course_id){
        $user_course_register = CourseRegistration::where('course_id',$course_id)->where('user_id',\auth()->id())->first();
        $role_auth_is_admin = \auth()->user()->roles()->first();

        if(!$user_course_register && ($role_auth_is_admin && $role_auth_is_admin->id != 1)){
            return false;
        }

        return $user_course_register;
    } // end function




    /*
     *  Update Course Registration Progress When content have (role and path)
     */
    private function updateCourseRegistrationProgress($course_id,$content_role_and_path){
        if($content_role_and_path == 1){
            $user_contents_count = DB::select(DB::raw("SELECT COUNT(user_contents.id) as user_contents_count FROM user_contents
                                   INNER JOIN contents on user_contents.content_id = contents.id
                                   WHERE user_contents.user_id =".\auth()->id()."
                                   AND  contents.deleted_at IS NULL
                                   AND  contents.role_and_path = 1
                                   AND contents.course_id = ". $course_id ."

                             "));
            $user_contents_count = $user_contents_count[0]->user_contents_count??0;

            $contents_count = DB::select(DB::raw("SELECT COUNT(id) as contents_count
                                                            FROM contents
                                                            WHERE   course_id =". $course_id ."
                                                            AND parent_id IS NOT NULL
                                                            AND  deleted_at IS NULL
                                                            AND  role_and_path = 1
                                                            "));
            $contents_count = $contents_count[0]->contents_count??0;

            CourseRegistration::where('course_id',$course_id)
                ->where('user_id',\auth()->id())->update(['progress'=> round(($user_contents_count / $contents_count) * 100 ,  1)  ]);

        }
    } // end function


    /*
     * Get Content File Path
     */
    private function getContentFilePath($content_type){

        $file = "";
        if(isset($content)){
            switch ($content_type){
                case 'video': $file = public_path('upload/files/videos/'.$content->upload->file);  break;
                case 'audio': $file = public_path('upload/files/audios/'.$content->upload->file);  break;
                case 'presentation': $file = public_path('upload/files/presentations/'.$content->upload->file);  break;
                case 'scorm': $file = public_path('upload/files/scorms/'.$content->upload->file);  break;
            }
        }
        return $file;
    } // end function


    /*
     *  Create New User Content OR
     *  Update Start Time For User Content
     *    When User Open Content Page AND User Content Not Completed
     */
    private function createUserContentOrUpdate($content_id,$content_time_limit){
        $user_content =  UserContent::where('user_id' , \auth()->id())
            ->where('content_id' , $content_id)->first();

        if(!$user_content){ // create user content when not find
            $is_completed = 1;
            if($content_time_limit){ // if content has time limit => The new user content is_completed
                $is_completed = 0;
            }
            $user_content = UserContent::create([
                'user_id'      => \auth()->id(),
                'content_id'   => $content_id,
                'start_time'   => Carbon::now(),
                'is_completed' => $is_completed,
            ]);
        }else{ // update start time to now time when user content exists and (not completed)
            $is_completed = $user_content->is_completed;
            if($is_completed == 0){
                $user_content = UserContent::where('user_id' , \auth()->id())
                    ->where('content_id' , $content_id)
                    ->update([
                        'start_time'   => Carbon::now(),
                    ]);
            }
        }

        return $is_completed;
    } // end function



    /****************************************************************************/


    /*
     * When Content Timer Expired
     * (JS Code Send AJAX Request To Updated UserContent status to Completed)
     */
    public function update_completed_status(){
        // Check User Have User Content For This Content
        $user_content = UserContent::where('content_id',\request()->content_id)
            ->where('user_id',\auth()->id())
            ->with(['content'])
            ->first();

        if (!$user_content){
            abort(404);
        }


        // Check If Content Timer Is Expired
        $enabled = $this->checkIfContentTimerIsExpired($user_content->content->time_limit,$user_content->start_time);


        /*
         * If Content Timer Is Expired
         * Update User Content Completed Status => (is_completed = 1)
         * AND Return Response To JS AJAX Code
         */
        if($enabled){
            $this->updateUserContentCompletedStatus(\request()->content_id);
            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false]);
    } // end function


    /*
     * Check If Content Timer Is Expired
     */
    private function checkIfContentTimerIsExpired($content_time_limit,$user_content_start_time){
        $date_now = Carbon::now();
        if($content_time_limit){
            $start_time = Carbon::create($user_content_start_time);
            $start_time  = $start_time->addSeconds($content_time_limit);

            if($start_time->gt($date_now)){
                return false;
            }
        }
        return true;
    } // end function

    /*
     * Update User Content Completed Status => (is_completed = 1)
     */
    private function updateUserContentCompletedStatus($content_id){
         UserContent::where('content_id',$content_id)
            ->where('user_id',\auth()->id())
            ->update([
                'is_completed' => 1
            ]);
    } // end function

    public function save_page()
    {
        // dump(request()->pageNum);
        // dump(request()->content_id);
        // dd(auth()->user()->id);

        $content = UserContentsPdf::updateOrCreate([
            'content_id' => request()->content_id,
            'user_id' => auth()->user()->id
        ],[
            'current_page' => request()->pageNum,
        ]);
    }

    /****************************************************************************/


} // End Class
