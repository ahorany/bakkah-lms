<?php
namespace App\Imports;
use App\Models\Training\Course;
use App\User;
// use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class UsersCoursesImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {

        foreach ($rows as $row)
        {

            if($row['course_id'] != '')
            {
                $course = Course::where('reference_course_id',$row['course_id'])->first();
                $user = User::where('username_lms',$row['usertocourses'])->first();

                DB::table('courses_registration')->insert([
                    [

                     'user_id' => $user->id,
                     'course_id'=>$course->id,
                     'progress'=>$row['completionpercentage'],
                     'score'=>$row['score'],
                     'expire_date' =>$user->expire_date,

                    ],
                ]);

                $id = DB::getPdo()->lastInsertId();
            }
        }
    }


}

