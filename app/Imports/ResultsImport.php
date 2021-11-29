<?php
namespace App\Imports;

use App\Models\Training\Exam;
use App\Models\Training\Question;
use App\Models\Training\Answer;

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


        // dd(request()->all());die();
        $exam_id = Exam::where('content_id', request()->content_id)->first();
        // dd($rows);
        foreach ($rows as $row)
        {

            $user_id = User::where('email', $row['email'])->first();

            if($user_id->id != '')
            {

                //assume that all exams marks are 100
                DB::table('user_exams')->insert([
                    [
                        'user_id'       => $user_id->id,
                        'exam_id'       => $exam_id->id,
                        'status'        => 1,
                    ],
                ]);

                // dump($user_id->id);
                // dd($exam_id->id);

                $user_exams_id = DB::getPdo()->lastInsertId();
                $question_count = 0;
                for($i=1;$i<=100;$i++)
                {
                    if(isset($row['q'.$i]))
                        $question_count++;
                    else
                        break;
                }

                $mark_total = 0;
                for($i=1;$i<=$question_count;$i++)
                {
                    if($row['q'.$i] == 'correct')
                    {
                        $question_id = Question::where('question_name', 'Q'.$i)->where('exam_id', request()->content_id)->first();
                        // dd($question_id->id);
                        $correct_answer = Answer::where('question_id',$question_id->id)->where('check_correct',1)->first();
                        // dd($correct_answer);
                         DB::table('user_answers')->insert([
                            [
                                'user_exam_id'  => $user_exams_id,
                                'question_id'   => $question_id->id,
                                'answer_id'     => $correct_answer->id,
                            ],
                        ]);

                        DB::table('user_questions')->insert([
                            [
                                'user_exam_id'       => $user_exams_id,
                                'question_id'       => $question_id->id,
                                'mark'              => $question_id->mark,
                            ],
                        ]);
                        $mark_total += $question_id->mark;
                    }

                }

                DB::table('user_exams')
                ->where('id', $user_exams_id)
                ->update(['mark' => $mark_total]);

            }

            // dd($question_count);




        }

    }


}

