<?php
namespace App\Imports;

use App\Models\Training\Course;
use App\Models\Training\Attendant;
use App\User;
// use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class CoursesImport implements ToCollection, WithHeadingRow
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
            $data = json_encode([
                'en'=>$row['course'],
                'ar'=>$row['course'],
            ], JSON_UNESCAPED_UNICODE);

            $active = 1;
            if($row['group_id'] == 'No')
                $active = 0;
            DB::table('courses')->insert([
                ['title' => $data,
                 'group_id'=>$row['group_id'],
                 'active'=> $active,
                 'excerpt'=>$row['description'],
                 'reference_course_id'=>$row['course_id'],
                 'PDUs'=>$row['pdu'],
                 'code'=>$row['code'],

                ],
            ]);

            $id = DB::getPdo()->lastInsertId();


        }

    }


}

