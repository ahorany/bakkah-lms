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

class QuestionsMoodleImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {
        // dd(request()->all());

        // $mark = 0;
        $counter = 0;$q_no = 0;
        foreach ($rows as $row)
        {
            // dd($row[0]);
            if($counter == 0)
                $title =  $row['title'];
            if(substr($row['title'],0,7) != 'ANSWER:')
            {
                $answer_title[$counter] =  substr( $row['title'],2);
            }
            else
            {
                $q_no++;
                $correct_answer =  substr($row['title'],8);

                DB::table('questions')->insert([
                    [
                        'title'             => $title,
                        'mark'              => 1,
                        'exam_id'           => request()->content_id,
                        'feedback'          => '',
                        'question_type'     => 'multichoice',
                        'question_name'     => 'Q'.$q_no,
                        'hidden'            => 0,
                        'single'            => 1,
                        'shuffle'           => 1,
                    ],
                ]);

                $question_id = DB::getPdo()->lastInsertId();


                for($i=1;$i<=5;$i++)
                {
                    $check_correct = 0;
                    if($correct_answer == 'A' && $i == 1)
                        $check_correct = 1;
                    else if($correct_answer == 'B' && $i == 2)
                        $check_correct = 1;
                    if($correct_answer == 'C' && $i == 3)
                        $check_correct = 1;
                    elseif($correct_answer == 'D' && $i == 4)
                        $check_correct = 1;
                    elseif($correct_answer == 'E' && $i == 5)
                        $check_correct = 1;
                    if(isset($answer_title[$i]))
                    {
                        Answer::create([
                            'title'             => $answer_title[$i],
                            'question_id'       => $question_id,
                            'check_correct'     => $check_correct,
                        ]);
                    }

                }
                $answer_title = [];
                // $mark += $row['default_garde'];
                $counter = 0;continue;
            }

            // elseif($counter == 1)
            //     $answer_title[1] =  $row['title'];
            // elseif($counter == 2)
            //     $answer_title[2] =  $row['title'];
            // elseif($counter == 3)
            //     $answer_title[3] =  $row['title'];
            // elseif($counter == 4)
            //     $answer_title[4] =  $row['title'];
            // elseif($counter == 5)
            // {

            // }


            $counter++;


        }

        // $exam_id = Exam::where('content_id', request()->content_id)->first();
        // DB::table('exams')
        //     ->where('id',  $exam_id->id)
        //     ->update(['exam_mark' => $mark]);

    }


}

