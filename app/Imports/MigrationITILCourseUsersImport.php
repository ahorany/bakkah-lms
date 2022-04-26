<?php
namespace App\Imports;

use App\Mail\UserMail;
use App\Models\Training\Content;
use App\Models\Training\Course;
use App\Models\Training\CourseRegistration;
use App\Models\Training\UserBranch;
use App\Models\Training\UserContent;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MigrationITILCourseUsersImport implements ToCollection
{
    public function __construct($course_id)
    {
        $this->course_id = $course_id;
    }


    public function collection(Collection $rows)
    {
        $course = Course::select('id', 'ref_id')->where('id', $this->course_id)->first();
        if ((!$course) || $course->training_option_id == 13) {
            abort(404);
        }

        $course_contents = Content::where('course_id', $this->course_id)
            ->where('role_and_path', 1)
            ->where('post_type', '!=', 'section')
            ->where('post_type', '!=', 'discussion')
            ->get();


        // deleted head row
        $rows_arr = $rows->toArray();
        array_splice($rows_arr, 0, 1);
        $rows = collect($rows_arr);


        foreach ($rows as $index => $row) {
            try {
                $user = User::where('email', $row[3])->first();
                $role_id = 3;
                if (!$user) {
                    $passArr = $this->generatePassword();

                    $user = User::create([
                        'email' => $row[3],
                        'password' => $passArr['hash_pass'],
                    ]);

                    if ($row[4] == "Instructor") {
                        $role_id = 2;
                    }

                    $user->assignRole([$role_id]);
                    //    Mail::to($user->email)->send(new UserMail($user->id ,  $passArr['text_pass']));
                }


                UserBranch::firstOrCreate([
                    'user_id' => $user->id,
                    'branch_id' => 1,
                ], [
                    'user_id' => $user->id,
                    'branch_id' => 1,
                    'name' => $row[0] . " " . $row[1],
                ]);


                $courseRegistration = CourseRegistration::where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->first();

                if (!$courseRegistration) {
                    $completed_at = $row[6] == '-' ? null : $row[6];
                    $courseRegistration = CourseRegistration::create([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'role_id' => $role_id,
                        'paid_status' => 503,
                        'completed_at' => $completed_at,
                    ]);


                    if ($row[5] != 'Not started' && $row[5] != '-') {
                        if ($row[5] == 'Completed') {
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
                                $user_progress_in_xls = $row[5] * 100;
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
                                'progress' => (($user_content_count / count($course_contents)) * 100)
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

    }// end method


    /**
     * Generate Random Password
     * @return array
     */
    private function generatePassword(){
        $random_password =  Str::random(10) ;
        $hashed_random_password = Hash::make($random_password);
        return ['text_pass' => $random_password , 'hash_pass' => $hashed_random_password];
    }// end method
}
