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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StoreDBMigrationCourseUsersImport implements ToCollection
{
    public function __construct($course,$master_id)
    {
        $this->course = $course;
        $this->master_id = $master_id;
    }


    public function collection(Collection $rows)
    {
        // deleted head row
        $rows_arr = $rows->toArray();
        array_splice($rows_arr, 0, 1);
        $rows = collect($rows_arr);

        foreach ($rows as $index => $row) {
            try {
                    $passArr = $this->generatePassword();
                     DB::table('user_migration_data')->insert([
                        'first_name'    => $row[0],
                        'last_name'     => $row[1],
                        'active'        => $row[2],
                        'email'         => $row[3],
                        'role'          => $row[4],
                        'status'        => $row[5],
                        'completed_at'  => $row[6],
                        'time'          => $row[7],
                        'avg_score'     => $row[8],
                        'cert_unq_id'   => $row[9],
                        'cert_num'      => $row[12],
                        'hash_password' => $passArr['hash_pass'],
                        'plain_password'=> $passArr['text_pass'],
                        'course_id'     => $this->course->id,
                        'ref_course_id' => $this->course->ref_id,
                        'sent'          => 0,
                        'master_id'     => $this->master_id,
                    ]);

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
