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

        // dd(request()->all());

    // "fraction" => "100"
    // "answer1" => "Cost leadership"
    // "feedback1" => "
    // "fraction2" => 0
    // "answer2" => "Differentiation"
    // "feedback2" => "
    // "fraction_3" => 0
    // "answer3" => "Focus"
    // "feedback3" =>
    // "fraction_4" => 0
    // "answer4" => "Customer Focus"
    // "feedback4" =>
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
                'question_id'        => $question_id->id,
                'check_correct'     =>$check_correct,

            ]);
        }

    }

    }


}

