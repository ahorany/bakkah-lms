<?php

namespace App\Http\Controllers\Migration;

use App\Http\Controllers\Controller;
use App\Imports\StoreDBMigrationCourseUsersImport;
use App\Mail\UserMail;
use App\Mail\UserMigrationMail;
use App\Models\Training\Content;
use App\Models\Training\Course;
use App\Models\Training\CourseRegistration;
use App\Models\Training\UserBranch;
use App\Models\Training\UserContent;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class UserMigrateDataController extends Controller
{
    public function index(){
       return view('migration.index');
    }// end method

    public function upload(Request $request){
        if (auth()->id() != 1){
            abort(404);
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx',
        ]);

        $course = Course::select('id', 'ref_id')->where('id', $request->course_id)->first();
        if ((!$course) || $course->training_option_id == 13) {
            abort(404);
        }


        $file = request()->file('file');
        if ($file){
            $original_file_name = $file->getClientOriginalName();
            $extension          = $file->getClientOriginalExtension();
            $save_file_name      = self::generateFileName($original_file_name);
            $file->move(public_path("upload/excel"), $save_file_name);

            $master_id = DB::table('user_migration_files')->insertGetId([
                'name'      => $original_file_name,
                'file'      => $save_file_name,
                'extension' => $extension ,
                'course_id' => $course->id ,
                'course_ref_id' => $course->ref_id,
            ]);

        Excel::import(new StoreDBMigrationCourseUsersImport($course,$master_id), public_path("upload/excel/{$save_file_name}"));

            return redirect()->back()->with(['color' => 'green' , 'msg' => 'Successfully done!']);
        }
        return redirect()->back()->with(['color' => 'red' , 'msg' => 'The operation failed!']);
    }// end method

    private static function generateFileName($name){
        $fileName = date('Y-m-d-H-i-s') . '_' . trim($name);
        $fileName = str_replace(' ','_',$fileName);
        $fileName = str_replace(['(',')'],'_',$fileName);
        $fileName = trim(strtolower($fileName));
        return $fileName;
    }// end method


    public function sendMails(Request $request){
        $request->validate([
            'master_id' => 'required|exists:user_migration_files,id',
        ]);


        $master =  DB::table('user_migration_files')
            ->where('id',$request->master_id)
            ->where('course_id',$request->course_id)
            ->first();
        if (!$master){
            abort(404);
        }
        $rows = DB::table('user_migration_data')->get();
        foreach ($rows as $index => $row) {
            Mail::to($row->email)->send(new UserMigrationMail());
        }


        return redirect()->back()->with(['color' => 'green' , 'msg' => 'Successfully done!']);
    }// end method




    public function save(Request $request){
        $request->validate([
            'master_id' => 'required|exists:user_migration_files,id',
        ]);


       $master =  DB::table('user_migration_files')
            ->where('master_id',$request->master_id)
            ->where('course_id',$request->course_id)
            ->first();
       if (!$master){
           abort(404);
       }

        $course = Course::select('id', 'ref_id')->where('id', $request->course_id)->first();
        if ((!$course) || $course->training_option_id == 13) {
            abort(404);
        }

        $course_contents = Content::where('course_id', $request->course_id)
//            ->where('role_and_path', 1)
            ->where('post_type', '!=', 'section')
            ->where('post_type', '!=', 'discussion')
            ->get();



        $rows = DB::table('user_migration_data')->get();

        foreach ($rows as $index => $row) {
            try {
                $row = (array)$row;

                $user = User::where('email', $row['email'])->first();
                $role_id = 3;
                if (!$user) {
                    $user = User::create([
                        'email' => $row['email'],
                        'password' => $row['hash_password'],
                    ]);

                    if ($row['role'] == "Instructor") {
                        $role_id = 2;
                    }

                    $user->assignRole([$role_id]);
                    Mail::to($user->email)->send(new UserMail($user->id ,  $row['plain_password']));
                }


                UserBranch::firstOrCreate([
                    'user_id' => $user->id,
                    'branch_id' => 1,
                ], [
                    'user_id' => $user->id,
                    'branch_id' => 1,
                    'name' => $row['first_name'] . " " . $row['last_name'],
                ]);


                $courseRegistration = CourseRegistration::where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->first();

                if (!$courseRegistration) {
                    $completed_at = $row['completed_at'] == '-' ? null : $row['completed_at'];
                    $courseRegistration = CourseRegistration::create([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'role_id' => $role_id,
                        'paid_status' => 503,
                        'completed_at' => $completed_at,
                        'is_migrated' => 1,
                    ]);


                    if ($row['status'] != 'Not started' && $row['status'] != '-') {
                        if ($row['status'] == 'Completed') {
                            foreach ($course_contents as $content) {
                                UserContent::create([
                                    'user_id' => $user->id,
                                    'content_id' => $content->id,
                                    'start_time' => Carbon::now(),
                                    'is_completed' => 1,
                                    'flag' => 0,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);
                            }// end foreach

                            // update progress to => 100% completed
                            $courseRegistration->update([
                                'progress' => 100
                            ]);

                        } else {
                            $user_content_count = 0;
                            foreach ($course_contents as $content) {
                                $user_progress_in_xls = $row['status'] * 100;
                                if ((($user_content_count / count($course_contents)) * 100) >= $user_progress_in_xls) {
                                    break; // stop loop when progress_in_db >= user_progress_in_xls_file
                                }

                                UserContent::create([
                                    'user_id' => $user->id,
                                    'content_id' => $content->id,
                                    'start_time' => Carbon::now(),
                                    'is_completed' => 1,
                                    'flag' => 0,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);

                                $user_content_count++;
                            }// end foreach

                            // update progress
                            $courseRegistration->update([
                                'progress' => round((($user_content_count / count($course_contents)) * 100), 1)
                            ]);
                        }// end else
                    } else {
                        // Not Started
                        $courseRegistration->update([
                            'progress' => 0
                        ]);
                    }

                }// end if => check courseRegistration

            } catch (\Exception $e) {
                dump($index);
                dd($row);
            }
        }// end foreach

        return redirect()->back()->with(['color' => 'green' , 'msg' => 'Successfully done!']);
    }// end method


}
