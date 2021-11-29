<?php
namespace App\Imports;

use App\Models\Training\Question;
use App\Models\Training\Answer;
use App\Models\Training\Exam;
// use App\Models\Training\Session;

use App\Models\Training\Attendant;
use App\User;
// use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class QuestionsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {

        // dd(request()->all());
        // dd($rows);
        $mark = 0;
        foreach ($rows as $row)
        {

            if($row['question_text'] != '')
            {
                DB::table('questions')->insert([
                    [

                        'title'             => $row['question_text'],
                        'mark'              => $row['default_garde'],
                        'exam_id'           => request()->content_id,
                        'feedback'          => $row['general_feedback'],
                        'question_type'     => $row['question_type'],
                        'question_name'     => $row['question_name'],
                        'penalty'           => $row['penalty'],
                        'hidden'            => $row['hidden'],
                        'single'            => $row['single'],
                        'shuffle'           => $row['shuffle'],
                        'answering_number'  => $row['answering_number'],
                        'instruction'       => $row['instruction'],
                        'correct_feedback'  => $row['correct_feedback'],
                        'partially_feedback'=> $row['partially'],
                        'incorrect_feedback'=> $row['incorrect'],
                    ],
                ]);

                $question_id = DB::getPdo()->lastInsertId();


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
            $mark += $row['default_garde'];

        }
        $exam_id = Exam::where('content_id', request()->content_id)->first();
        DB::table('exams')
        ->where('id',  $exam_id->id)
        ->update(['exam_mark' => $mark]);

    }


}

