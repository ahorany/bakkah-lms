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

class QuestionsLittleImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {

        $mark = 0;
        foreach ($rows as $row)
        {

            if($row['question_text'] != '')
            {
                // dump($row['question_text']);
                DB::table('questions')->insert([
                    [
                        'title'             => $row['question_text'],
                        'mark'              => 1,
                        'exam_id'           => request()->content_id,
                        'feedback'          => $row['rationale'],
                        'question_type'     => $row['question_type'],
                        'question_name'     => $row['q_no'],
                        'hidden'            => 0,
                        'single'            => 1,
                        'shuffle'           => 1,
                    ],
                ]);

                $question_id = DB::getPdo()->lastInsertId();
                $content = Content::where('id',request()->content_id)->first();
                $units = explode(',',$row['chapter']);
                foreach($units as $unit)
                {
                    $unit = Unit::where('unit_no',$unit)->where('course_id',$content->course_id)->first();
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

                if($row['correct_option'] === true)
                    $row['correct_option'] = 'TRUE';
                if($row['correct_option'] === false)
                    $row['correct_option'] = 'FALSE';

                Answer::create([
                    'title'             => $row['correct_option'],
                    'question_id'       => $question_id,
                    'check_correct'     => 1,
                ]);
                for($i=2;$i<=4;$i++)
                {

                    if($row['option_'.$i] === true)
                        $row['option_'.$i] = 'TRUE';
                    if($row['option_'.$i] === false)
                        $row['option_'.$i] = 'FALSE';

                    Answer::create([
                        'title'             => $row['option_'.$i],
                        'question_id'       => $question_id,
                        'check_correct'     => 0,
                    ]);
                    if($row['question_type'] == 'T or F')
                    {
                        $i = 5;
                    }

                }

            }
            $mark += 1;

        }
        // dd(100);
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
/*

 */
