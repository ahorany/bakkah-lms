<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Answer;
use App\Models\Training\Content;
use App\Models\Training\ContentDetails;
use App\Models\Training\Course;
use App\Models\Training\Exam;
use App\Models\Training\Question;
use App\Models\Training\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    private function buildTree($elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                $children = $this->buildTree($elements, $element->id);
                if ($children) {
                    $element->s = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function add_questions($exam_id){
        $content = Content::where('id',$exam_id)->with(['questions.answers'])->latest()->first();
        /////
        $course_id = $content->course_id;
        $units = Unit::where('course_id',$course_id)->with(['subunits'])->get();


        $units = $this->buildTree($units);

//        return $units;
        return view('training.courses.contents.exam', compact('content','units'));
    }

    public function add_question(){
//        return \request();
        // validation

        $rules = [
            "title"   => "required|string",
            "mark"   => "required|numeric",
            'exam_id' => 'required|exists:contents,id',
        ];

        $validator = Validator::make(\request()->all(), $rules);

        foreach (request()->answers as $answer){
            if(is_null($answer['title'])){
                $validator->getMessageBag()->add('answers', 'All answers field required');
                break;
            }
        }

        if(count($validator->errors()) > 0 ) {
            return response()->json(['errors' => $validator->errors()]);
        }

//        if ($validator->fails()) {
//        }

//        return $answer_validation;

        $question = Question::updateOrCreate(['id' => \request()->question_id],[
            'title' => \request()->title,
            'mark' => \request()->mark,
            'exam_id' => \request()->exam_id,
            'unit_id' => \request()->unit_id,
        ]);

       $mark = DB::select(DB::raw("SELECT SUM(mark) as mark FROM questions WHERE exam_id =".\request()->exam_id));

        Exam::where('content_id' ,\request()->exam_id)->update([
            'exam_mark' => $mark[0]->mark
        ]);


        foreach (request()->answers as $answer ){
//            if(!is_null( $answer['title'])){
                Answer::updateOrCreate(['id' => $answer['id'] ],[
                    'title' => $answer['title'],
                    'type' => 'multi_choice',
                    'check_correct' => $answer['check_correct'] ? 1 : 0,
                    'question_id' => $question->id,
                ]);
//            }

        }

        if(\request()->save_type == 'add'){
            $question = Question::where('id',$question->id)->with(['answers'])->first();
        }else{
            $question = Question::where('id',\request()->question_id)->with(['answers'])->first();
        }
           return response()->json(['data' => $question]);
    }

    public function delete_question(){
        $id = request()->question_id;
        Question::where('id',$id)->delete();
        return response()->json([ 'status' => 'success']);
    }

//    public function add_answer(){
//
//        // validation
//        $rules = [
//            "title"   => "required|string|min:3|max:20",
//            "check_correct"   => "required|boolean",
//            'question_id' => 'required|exists:questions,id',
//        ];
//
//        $validator = Validator::make(\request()->all(), $rules);
//
//        if ($validator->fails()) {
//            return response()->json(['errors' => $validator->errors()]);
//        }
//
//
//        $answer = Answer::create([
//            'title' => request()->title,
//            'type' => 'multi_choice',
//            'check_correct' => \request()->check_correct ? 1 : 0,
//            'question_id' => request()->question_id,
//
//        ]);
//
//        return response()->json(['data' => $answer]);
//    }


    public function delete_answer(){
        $id = request()->answer_id;
        Answer::where('id',$id)->delete();
        return response()->json([ 'status' => 'success']);
    }

    public function update_answer(){

        // validation
        $rules = [
            "title"   => "required|string",
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
