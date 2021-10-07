<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Answer;
use App\Models\Training\Content;
use App\Models\Training\ContentDetails;
use App\Models\Training\Course;
use App\Models\Training\Exam;
use App\Models\Training\Question;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
{

    public function contents()
    {
        $course_id = request()->course_id;
        $course = Course::with(['upload', 'user'])->where('id',$course_id)->first();
        $contents = Content::where('course_id',$course_id)
            ->whereNull('parent_id')
            ->with(['contents' => function($q){
                $q->with(['details','exam']);
            },'details','exams'])
//            ->latest()
            ->get();
        return view('training.courses.contents.index', compact('course', 'contents'));
    }

    public function delete_content()
    {
        $content_id = request()->content_id;
         Content::where('id',$content_id)->delete();
        return response()->json([ 'status' => 'success']);
    }

    public function add_section()
    {
        $rules = [
            'title'      => "required|string|min:3|max:50",
            'course_id'  =>'required|exists:courses,id',
            'excerpt'    =>  "required|string",
        ];

        $validator = Validator::make(\request()->all(), $rules);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()]);
        }

        $content = Content::create([
                'title'      => request()->title,
                'course_id'  =>request()->course_id,
                'post_type'  => 'section',
        ]);

        $content->details()->create([
                'excerpt'    =>  request()->excerpt,
        ]);

        $content = Content::whereId($content->id)->with(['details','contents'])->first();
//        $contents = Content::where('course_id',$course_id)->whereNull('parent_id')->with(['contents','details'])->latest()->get();
        return response()->json([ 'status' => 'success','section' => $content]);

    }

    public function update_section()
    {
        $rules = [
            'title'      => "required|string|min:3|max:20",
            'course_id'  =>'required|exists:courses,id',
            'content_id'  =>'required|exists:contents,id',
            'excerpt'    =>  "required|string",
        ];

        $validator = Validator::make(\request()->all(), $rules);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()]);
        }


        $content = Content::whereId(request()->content_id)->update([
            'title'      => request()->title,
            'course_id'  =>request()->course_id,
        ]);

        ContentDetails::updateOrCreate([
            'content_id' => request()->content_id
        ],[
            'excerpt'    =>  request()->excerpt,
        ]);


        return response()->json([ 'status' => 'success']);

    }

    private function contentValidation($type){
        // validation
        if($type == 'exam'){
            $rules = [
                'title'      => "required|string|min:3|max:50",
                'course_id'  =>'required|exists:courses,id',
                'excerpt'    =>  "required|string",
                'content_id' => 'required|exists:contents,id',
                'duration'=>'required|numeric|gt:-1',
                'pagination'=>'required|numeric|gt:-1',
                'attempt_count'=>'required|numeric|gt:0',
                'start_date'  => 'required|date|before:end_date',
                'end_date'     => 'required|date|after:start_date',
            ];
        }else{
            $mimes ='';
            switch ($type){
                case 'video': $mimes = '|mimes:mp4,mov,ogg,qt|max:20000' ; break;
                case 'audio': $mimes = '|required|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav'; break;
                case 'presentation': $mimes = '|mimes:ppt,pptx'; break;
                case 'scorm': $mimes = '|mimes:pdf,doxc'; break;
                default : $mimes = '';;
            }

            $file =  'required_without:url';
            if(\request()->hasFile('file')){
                $file = 'required_without:url|file'.$mimes;
            }

            $rules = [
                'title'      => "required|string|min:3|max:20",
                'url'        =>   "required_without:file|max:200",
                'course_id'  =>'required|exists:courses,id',
                'content_id' => 'required|exists:contents,id',
                'file'      => $file,
            ];

        }

        $validator = Validator::make(\request()->all(), $rules);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return null;
    }

    public function add_content(){
        $validate = $this->contentValidation(request()->type);

        if($validate){
            return response()->json(['errors' => $validate]);
        }

        if(\request()->type == 'exam'){
            $content = Content::create([
                'title'      => request()->title,
                'course_id'  =>request()->course_id,
                'post_type'  => request()->type,
                'parent_id'  => request()->content_id,
            ]);
            $content->details()->create([
                'excerpt'    =>  request()->excerpt,
                'content_id' => $content->id,
                'type' => request()->type,
            ]);

            $content->exams()->create([
                'start_date'    =>  request()->start_date,
                'end_date' => request()->end_date,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'duration' => request()->duration,
                'pagination' => request()->pagination,
                'attempt_count' => request()->attempt_count,
            ]);

        }else{
            $content = Content::create([
                'title'      => request()->title,
                'course_id'  =>request()->course_id,
                'post_type'  => request()->type,
                'url'        => request()->url,
                'parent_id'  => request()->content_id,
            ]);

            $path = '';
            switch (request()->type){
                case 'video': $path = public_path('upload/files/videos'); break;
                case 'audio': $path = public_path('upload/files/audios'); break;
                case 'presentation': $path = public_path('upload/files/presentations'); break;
                case 'scorm': $path = public_path('upload/files/scorms'); break;
                default : $path = public_path('upload/files/files');
            }

            Content::UploadFile($content,['folder_path' => $path]);
        }

        $content = Content::whereId($content->id)->with(['details','exam'])->first();
        return response()->json([ 'status' => 'success','data' => $content]);
    }

    private function updateContentValidation($type){

        $mimes ='';
        switch ($type){
            case 'video': $mimes = '|mimes:mp4,mov,ogg,qt|max:20000' ; break;
            case 'audio': $mimes = '|required|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav'; break;
            case 'presentation': $mimes = '|mimes:ppt,pptx'; break;
            case 'scorm': $mimes = '|mimes:pdf,doxc'; break;
            default : $mimes = '';;
        }

        if ($mimes != ''){
            $file =  'required_without:url';
            if(\request()->hasFile('file')){
                $file = 'required_without:url|file'.$mimes;
            }

            $rules = [
                'title'      => "required|string|min:3|max:50",
                'url'        =>   "required_without:file|max:200",
                'file'      => $file,
            ];
        }else{
            $rules = [
                'title'      => "required|string|min:3|max:20",
                'excerpt'    =>  "required|string",
                'duration'=>'required|numeric|gt:-1',
                'attempt_count'=>'required|numeric|gt:0',
                'pagination'=>'required|numeric|gt:-1',
                'start_date'  => 'required|date|before:end_date',
                'end_date'     => 'required|date|after:start_date',
            ];
        }



        $validator = Validator::make(\request()->all(), $rules);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return null;
    }

    public function update_content()
    {
        $validate = $this->updateContentValidation(request()->type);

        if($validate){
            return response()->json(['errors' => $validate]);
        }

        if (\request()->type == 'exam') {
            $content = Content::whereId(request()->content_id)->update([
                'title' => request()->title,
            ]);
            ContentDetails::where('content_id', request()->content_id)->update([
                'excerpt' => request()->excerpt,
            ]);

            Exam::where('content_id', request()->content_id)->update([
                'start_date' => request()->start_date,
                'end_date' => request()->end_date,
                'duration' => request()->duration,
                'pagination' => request()->pagination,
                'attempt_count' => request()->attempt_count,
                'updated_by' => auth()->id(),
            ]);

        } else {
            $content = Content::whereId(request()->content_id)
                ->update([
                    'title' => request()->title,
                    'url' => request()->url,
                ]);


            $path = '';
            switch (request()->type) {
                case 'video':
                    $path = public_path('upload/files/videos');
                    break;
                case 'audio':
                    $path = public_path('upload/files/audios');
                    break;
                case 'presentation':
                    $path = public_path('upload/files/presentations');
                    break;
                case 'scorm':
                    $path = public_path('upload/files/scorms');
                    break;
                default :
                    $path = public_path('upload/files/files');
            }
            if (request()->file('file') != null) {
                Content::UploadFile(Content::where('id', request()->content_id)->first(), ['method' => 'update', 'folder_path' => $path]);
            }
        }
        return response()->json([ 'status' => 'success']);
    }








}
