<?php
namespace App\Imports;

use App\Models\Training\Question;
use App\Models\Training\Answer;
use App\Models\Training\Exam;
use App\Models\Training\Content;
use App\Models\Training\Unit;

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
                $content = Content::where('id',request()->content_id)->first();
                // dd($content);
                $units = explode(',',$row['unit_id']);
                foreach($units as $u)
                {
                    $unit = Unit::where('unit_no',$u)->where('course_id',$content->course_id)->first();
                    if(isset($unit->id))
                    {
                        DB::table('question_units')->insert([
                            [
                                'unit_id'            => $unit->id,
                                'question_id'        => $question_id,
                            ],
                        ]);
                    }
                }
                // dd(300);
                for($i=1;$i<=4;$i++)
                {
                    $check_correct = 0;
                    if($row['fraction'] == '100' && $i == 1)
                        $check_correct = 1;
                    else if($row['fraction2'] == '100' && $i == 2)
                        $check_correct = 1;
                    if($row['fraction_3'] == '100' && $i == 3)
                        $check_correct = 1;
                    elseif($row['fraction_4'] == '100' && $i == 4)
                        $check_correct = 1;

                    if($row['answer'.$i] === true)
                        $row['answer'.$i] = 'TRUE';
                    if($row['answer'.$i] === false)
                        $row['answer'.$i] = 'FALSE';
                   if( !is_null($row['answer'.$i])  )

                    // if(!empty($row['answer'.$i]) && $row['answer'.$i] != '')
                    {
                        //
                        Answer::create([
                            'title'             => $row['answer'.$i],
                            'question_id'       => $question_id,
                            'check_correct'     => $check_correct,
                        ]);
                    }

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

// DELETE from answers where question_id in ( SELECT id FROM `questions` WHERE exam_id in (403,404,405,410) );
// DELETE from question_units where question_id in ( SELECT id FROM `questions` WHERE exam_id in (403,404,405,410) );
//DELETE from questions WHERE exam_id in (403,404,405,410);
//update exams set exam_mark = 0 where `content_id` in (403,404,405,410);

