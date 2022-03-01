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

class QuestionsImportFixing implements ToCollection, WithHeadingRow
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
                $question_id = DB::table('questions')->where('exam_id',request()->content_id)
                                    ->where('title',$row['question_text'])->pluck('id')->first();
                if($question_id  > 0)
                {
                    $content = Content::where('id',request()->content_id)->first();
                    // dd($content);
                    $units = explode(',',$row['unit_id']);
                    foreach($units as $unit)
                    {
                        $unit = Unit::where('unit_no',$unit)->where('course_id',$content->course_id)->first();
                        if(isset($unit->id))
                        {
                            // $unit = Unit::where('unit_no',$unit)->where('course_id',$content->course_id)->first();
                            DB::table('question_units')->insert([
                                [
                                    'unit_id'            => $unit->id,
                                    'question_id'        => $question_id,
                                ],
                            ]);
                        }
                    }
                }


            }

        }

    }


}

// DELETE from answers where question_id in ( SELECT id FROM `questions` WHERE exam_id in (403,404,405,410) );
// DELETE from question_units where question_id in ( SELECT id FROM `questions` WHERE exam_id in (403,404,405,410) );
//DELETE from questions WHERE exam_id in (403,404,405,410);
//update exams set exam_mark = 0 where `content_id` in (403,404,405,410);

