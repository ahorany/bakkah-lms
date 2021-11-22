<?php
namespace App\Imports;

use App\Models\Training\Question;
use App\Models\Training\Answer;
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

        foreach ($rows as $row)
        {
            $question_id =  Question::create([
                'title'             => $row['question_text'],
                'mark'              => $row['default_garde'],
                'exam_id'           => $row['idnumber'],
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

            ]);

            for($i=1;$i<=4;$i++)
            {
                 Answer::create([
                    'title'             => $row['answer'.$i],
                    'question_id'        => $question_id->id,

                ]);
            }

        }

    }


}

