<?php
namespace App\Imports;

use App\Models\Training\Question;
use App\Models\Training\Answer;
use App\Models\Training\Exam;

// use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class QuestionsCourseImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {
        $mark = 0; $contentIds=[];
        // dd(request()->all());
        foreach ($rows as $row)
        {
            if (!in_array($row['content_id'], $contentIds))
            {
                $contentIds[] = $row['content_id'];
            }

            DB::table('questions')->insert([
                [
                    'title'             => $row['question_text'],
                    'mark'              => 1,//$row['default_garde'],
                    'exam_id'           => $row['content_id'],
                    'feedback'          => $row['rationale'],
                    'question_type'     => 'multichoice',//$row['question_type'],
                    'question_name'     => $row['q_no'],
                    'shuffle'           => 1,//$row['shuffle'],
                ],
            ]);

            $question_id = DB::getPdo()->lastInsertId();
            // dump($question_id);
            for($i=1;$i<=4;$i++)
            {
                if($i == 1)
                {
                    $title = $row['correct_option'];
                    $check_correct = 1;
                }
                else
                {
                    $title = $row['option_'.$i];
                    $check_correct = 0;
                }

                DB::table('answers')->insert([
                    [
                        'title'             => $title,//$row['answer'.$i],
                        'question_id'       => $question_id,
                        'check_correct'     => $check_correct,
                    ],
                ]);

            }

            $mark += 1;//$row['default_garde'];

        }

        $contentIds = array_unique($contentIds);
        for($i=0;$i<count($contentIds);$i++)
        {
            $sum = DB::table('questions')->where('exam_id',$contentIds[$i])->sum('mark');
            DB::table('exams')
            ->where('content_id',  $contentIds[$i])
            ->update(['exam_mark' => $sum]);
        }


        // dd(500);
        // $mark = 0;
        // foreach ($rows as $row)
        // {

        //     if($mark < 5)
        //     {
        //         dump( $row['content_id']);
        //         // if($row['question_text'] != '')
        //         // {

        //             DB::table('questions')->insert([
        //                 [
        //                     'title'             => $row['question_text'],
        //                     'mark'              => 1,//$row['default_garde'],
        //                     'exam_id'           => $row['content_id'],
        //                     'feedback'          => $row['rationale'],
        //                     'question_type'     => 'multichoice',//$row['question_type'],
        //                     'question_name'     => $row['q_no'],
        //                     'shuffle'           => 1,//$row['shuffle'],
        //                 ],
        //             ]);

        //             // dump($row['question_text']);
        //             // dump($row['content_id']);
        //             // dump($row['rationale']);

        //             $question_id = DB::getPdo()->lastInsertId();
        //             // dd($question_id);
        //             for($i=1;$i<=4;$i++)
        //             {
        //                 if($i == 1)
        //                 {
        //                     $title = $row['correct_option'];
        //                     $check_correct = 1;
        //                 }
        //                 else
        //                 {
        //                     $title = $row['option_'.$i];
        //                     $check_correct = 0;
        //                 }

        //                 Answer::create([
        //                     'title'             => $title,//$row['answer'.$i],
        //                     'question_id'       => $question_id,
        //                     'check_correct'     => $check_correct,
        //                 ]);
        //             }
        //         // }
        //         $mark += 1;//$row['default_garde'];
        //     }
        //     else
        //         break;


        // }

        // $exam_id = Exam::where('content_id', $row['content_id'])->first();
        // DB::table('exams')
        // ->where('id',  $exam_id->id)
        // ->update(['exam_mark' => $mark]);


    }


}

