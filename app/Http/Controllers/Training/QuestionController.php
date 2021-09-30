<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Answer;
use App\Models\Training\Content;
use App\Models\Training\ContentDetails;
use App\Models\Training\Course;
use App\Models\Training\Question;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function add_questions($exam_id){
        $content = Content::where('id',$exam_id)->with(['questions.answers'])->latest()->first();
        return view('training.courses.contents.exam', compact('content'));
    }

    public function add_question(){
        // validation
        $rules = [
            "title"   => "required|string|min:3|max:20",
            "mark"   => "required|numeric",
            'exam_id' => 'required|exists:contents,id',
        ];

        $validator = Validator::make(\request()->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }


        $question =Question::create([
            'title' => \request()->title,
            'mark' => \request()->mark,
            'exam_id' => \request()->exam_id,
        ]);
        return response()->json(['data' => $question]);
    }

    public function add_answer(){

        // validation
        $rules = [
            "title"   => "required|string|min:3|max:20",
            "check_correct"   => "required|boolean",
            'question_id' => 'required|exists:questions,id',
        ];

        $validator = Validator::make(\request()->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }


        $answer = Answer::create([
            'title' => request()->title,
            'type' => 'multi_choice',
            'check_correct' => \request()->check_correct ? 1 : 0,
            'question_id' => request()->question_id,

        ]);

        return response()->json(['data' => $answer]);
    }


    public function delete_answer(){
        $id = request()->answer_id;
        Answer::where('id',$id)->delete();
        return response()->json([ 'status' => 'success']);
    }

    public function update_answer(){

        // validation
        $rules = [
            "title"   => "required|string|min:3|max:20",
            "check_correct"   => "required|boolean",
            'answer_id' => 'required|exists:answers,id',

        ];

        $validator = Validator::make(\request()->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }


        $id = request()->answer_id;
        Answer::where('id',$id)->update([
            'title' => request()->title,
            'check_correct' => \request()->check_correct ? 1 : 0,
        ]);
        return response()->json([ 'status' => 'success']);
    }


}
