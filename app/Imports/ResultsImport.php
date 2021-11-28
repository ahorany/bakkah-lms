<?php
namespace App\Imports;

use App\Models\Training\Exam;

use App\Models\Training\Attendant;
use App\User;
// use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class ResultsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {

        // dd(request()->all());
        $exam_id = Exam::where('content_id', request()->content_id)->first();
        // dd($rows);
        foreach ($rows as $row)
        {
            dd($row);
            $user_id = User::where('email', $row['email'])->first();
            if($user_id->id != '')
            {
                DB::table('user_exams')->insert([
                    [
                        'user_id'       => $user_id->id,
                        'exam_id'       => $exam_id->id,
                        // 'end_attempt'   => $row['general_feedback'],
                        // 'mark'          => $row['question_type'],
                    ],
                ]);

                $user_exams_id = DB::getPdo()->lastInsertId();

            }





            for($i=1;$i<=4;$i++)
            {
                $check_correct = 0;
                if($row['fraction'] == '100' && $i == 1)
                    $check_correct = 1;
                else if($row['fraction2'] == '100' && $i == 2)
                    $check_correct = 1;
                if($row['fraction_3'] == '100' && $i == 3)
                    $check_correct = 1;
                elseif($row['fraction_4'] == '100' && $i == 3)
                    $check_correct = 1;

                Answer::create([
                    'title'             => $row['answer'.$i],
                    'question_id'       => $question_id,
                    'check_correct'     => $check_correct,
                ]);
            }

        }

    }


}

