<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Notifications\sendTestNotfication;
use App\User;
use Carbon\Carbon;
// use http\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Spatie\Sitemap\SitemapGenerator;

use Spatie\Sitemap\Tags\Url;
use Psr\Http\Message\UriInterface;

class HomeController extends Controller
{

    /******************************************************
     * HomeController: It is a class for Home operations.
     *
     * ( Home page , Last Video , Next Videos ,
     *  Complete Courses, User All Courses Activities)
     ******************************************************/


    public function __construct()
    {
        // User Must Be Auth To Use Any Method In This Class
        $this->middleware('auth');
    }


    /****************************************************************************/

    /*
     *  Home Page
     */
    public function home() {
        // clear session => (Sidebar active color)
        session()->put('infastructure_id',-1);

        // Get all user courses registration
        $courses =  User::where('id',\auth()->id())->with(['courses' => function($q){
            return $q->with(['training_option' , 'upload' => function($q){
                return $q->where('post_type','image');
            }]);
        }])->first();


        // Get last video (user watched)
        $last_video = $this->getLastVideoForUser();


        // Get next videos
        $next_videos = [];
        if($last_video){
            $next_videos = $this->getNextVideos($last_video);
        }

        // Get Completed Courses
        $complete_courses =  $this->getCompleteCourses();


        //  Get all activities for user
        $activities = $this->getUserCoursesActivities();

        return view('home', compact('complete_courses','courses','last_video','next_videos','activities'));
    } // end function

    
    public function certificate(){
        return view('certificate');
    }

    /*
     * Get last video user watched
     */
    private function getLastVideoForUser(){
        $sql = "SELECT user_contents.id ,contents.url as url, contents.id as content_id,
               uploads.file FROM user_contents
                  INNER JOIN contents ON  contents.id = user_contents.content_id
                  LEFT JOIN uploads  ON  contents.id = uploads.uploadable_id
                    WHERE user_contents.user_id = ".\auth()->id()."
                    AND (uploads.uploadable_type = 'App\\\\Models\\\\Training\\\\Content' OR contents.url IS NOT NULL)
                    AND contents.post_type = 'video'
                    AND contents.deleted_at IS NULL
               ORDER BY user_contents.id DESC LIMIT 1";

        $video = DB::select(DB::raw($sql));

        return $video[0]??null;
    } // end function


    /*
     * Get next videos (just 4 videos)
     */
    private function getNextVideos ($last_video){
        $sql = "SELECT contents.id ,contents.title  FROM contents
                 INNER JOIN courses_registration ON courses_registration.course_id = contents.course_id
                    WHERE contents.id > ".$last_video->content_id."
                    AND contents.post_type = 'video'
                    AND courses_registration.user_id =". \auth()->id() ."
                    AND contents.deleted_at IS NULL LIMIT 4";
        $next_videos = DB::select(DB::raw($sql));
        return $next_videos;
    } // end function


    /*
     * Get Completed Courses
     */
    private function getCompleteCourses(){
        $sql = "SELECT COUNT(id) as courses_count,
                case when (progress=100) then 1
                     when ( progress= 0 OR progress is null) then 2
                     when (progress<100 AND progress != 0) then 0
                end as status
                FROM courses_registration
                WHERE user_id = ".\auth()->id()."
                GROUP BY user_id, status
                ORDER By status";

        $complete_courses =  DB::select(DB::raw($sql));
        return $complete_courses;
    } // end function



    /*
     *  Get all activities for user
     */
    private function getUserCoursesActivities(){
        $user_id = \auth()->id();
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
                        ORDER BY user_contents.id DESC
                         LIMIT 5";

        $activities = DB::select(DB::raw($sql));
        return $activities;
    } // end function


    /****************************************************************************/

} // End Class
