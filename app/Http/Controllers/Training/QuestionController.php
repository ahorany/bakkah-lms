<?php

namespace App\Http\Controllers\Training;

use App\Helpers\CourseContentHelper;
use App\Http\Controllers\Controller;
use App\Models\Training\Answer;
use App\Models\Training\Content;

use App\Models\Training\ContentDetails;
use App\Models\Training\Course;
use App\Models\Training\Exam;
use App\Models\Training\Question;
use App\Constant;

use App\Models\Training\QuestionUnit;
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

//    public function add_questions($exam_id){
//        $content = Content::where('id',$exam_id)->with(['questions.answers','questions.units'])->latest()->first();
//        $course_id = $content->course_id;
//        $units = Unit::where('course_id',$course_id)->with(['subunits'])->get();
//        $units = $this->buildTree($units);
//        $import_types = Constant::where('post_type','imports')->get();
//        // dd($import_types);
//        return view('training.courses.contents.exam', compact('content','units','course_id','import_types'));
//
//    }

    public function add_questions($exam_id){
        $content = Content::where('id',$exam_id)->with(['exam','questions.answers','questions.units'])->latest()->first();

        // Get next and prev
        $arr = CourseContentHelper::NextAndPreviouseNavigation($content);
        $next = $arr['next'];
        $previous = $arr['previous'];
        // end next and prev

        $course_id = $content->course_id;
        $units = Unit::where('course_id',$course_id)->with(['subunits'])->get();
        $units = $this->buildTree($units);
        $import_types = Constant::where('post_type','imports')->get();

        return view('training.courses.contents.preview.exam', compact('content','units','course_id','import_types','next', 'previous'));

    }

    public function exam_preview(){ // new

        $exam_id = request()->exam_id;
        $content = Content::where('id',$exam_id)->with(['exam','questions.answers','questions.units'])->latest()->first();

        // Get next and prev
        // $arr = CourseContentHelper::NextAndPreviouseNavigation($content);
        // $next = $arr['next'];
        // $previous = $arr['previous'];
        // end next and prev

        $course_id = $content->course_id;
        $units = Unit::where('course_id',$course_id)->with(['subunits'])->get();
        $units = $this->buildTree($units);
        $import_types = Constant::where('post_type','imports')->get();

        return view('training.courses.contents.preview.exam', compact('content','units','course_id','import_types'));

    }


    public function add_question(){
        // validation
        $rules = [
            "title"   => "required|string",
            "mark"   => "required|numeric",
            "feedback"   => "",
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


        $question = Question::updateOrCreate(['id' => \request()->question_id],[
            'title' => \request()->title,
            'mark' => \request()->mark,
            'feedback' => \request()->feedback,
            'exam_id' => \request()->exam_id,
            'shuffle_answers' => \request()->shuffle_answers??0,
            'question_type' => 'multichoice',
        ]);

        QuestionUnit::where('question_id', $question->id)->delete();
        foreach (\request()->units_select as $unit){
            if($unit != -1){
                QuestionUnit::create([
                    'unit_id' => $unit,
                    'question_id' => $question->id,
                ]);
            }
        }



       $mark = DB::select(DB::raw("SELECT SUM(mark) as mark FROM questions WHERE exam_id =".\request()->exam_id));

        Exam::where('content_id' ,\request()->exam_id)->update([
            'exam_mark' => $mark[0]->mark
        ]);


        foreach (request()->answers as $answer ){
            Answer::updateOrCreate(['id' => $answer['id'] ],[
                'title' => $answer['title'],
                'type' => 'multi_choice',
                'check_correct' => $answer['check_correct'] ? 1 : 0,
                'question_id' => $question->id,
                'shuffle_answers' => $question->shuffle_answers,
            ]);
        }

        if(\request()->save_type == 'add'){
            $question = Question::where('id',$question->id)->with(['answers','units'])->first();
        }else{
            $question = Question::where('id',\request()->question_id)->with(['answers','units'])->first();
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
