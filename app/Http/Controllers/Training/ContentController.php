<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Answer;
use App\Models\Training\Content;
use App\Models\Training\ContentDetails;
use App\Models\Training\Course;
use App\Models\Training\Exam;
use App\Models\Training\Question;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
                $q->with(['upload','details','exam'])->orderBy('id');
            },'details','exams'])
            ->orderBy('id')
//            ->latest()
            ->get();
//        return $contents;
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
            'title'      => "required|string",
            'course_id'  =>'required|exists:courses,id',
//            'excerpt'    =>  "required|string",
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
            'title'      => "required|string",
            'course_id'  =>'required|exists:courses,id',
            'content_id'  =>'required|exists:contents,id',
//            'excerpt'    =>  "required|string",
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

            $start_date = '';
            $end_date = '';
            if( strtotime(\request()->start_date) && strtotime(\request()->end_date)  ){
                $start_date  = 'required|date|before:end_date';
                $end_date    = 'required|date|after:start_date';
            }


            $rules = [
                'title'      => "required|string",
                'course_id'  =>'required|exists:courses,id',
//                'excerpt'    =>  "required|string",
                'content_id' => 'required|exists:contents,id',
                'duration'=>'nullable|numeric|gt:-1',
                'pagination'=>'nullable|numeric|gt:-1',
                'attempt_count'=>'nullable|numeric|gt:-1',
                'start_date'  => $start_date,
                'end_date'     => $end_date,
            ];
        }else{
            $mimes ='';
            switch ($type){
                case 'video': $mimes = '|mimes:mp4,mov,ogg,qt|max:100000' ; break;
                case 'audio': $mimes = '|required|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav'; break;
                case 'presentation': $mimes = '|mimes:ppt,pptx,pdf,docx'; break;
                case 'scorm': $mimes = '|mimes:pdf,docx'; break;
                default : $mimes = '';;
            }

            if($type == 'video'){
                $file =  'required_without:url';
                if(\request()->hasFile('file')){
                    $file = 'required_without:url|file'.$mimes;
                }

                $rules = [
                    'title'      => "required|string",
                    'url'        =>   "required_without:file",
                    'course_id'  =>'required|exists:courses,id',
                    'content_id' => 'required|exists:contents,id',
                    'file'      => $file,
                ];
            }else{
                $rules = [
                    'title'      => "required|string",
                    'course_id'  =>'required|exists:courses,id',
                    'content_id' => 'required|exists:contents,id',
                    'file'      => 'required|file'.$mimes,
                ];
            }


        }

        $validator = Validator::make(\request()->all(), $rules);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return null;
    }

    public function add_content(){
        $validate = $this->contentValidation(request()->type);
//        return \request();

        if($validate){
            return response()->json(['errors' => $validate]);
        }
        $parent_id = (int) request()->content_id;

        $max_order =  DB::select(DB::raw("SELECT MAX(`order`) as max_order FROM `contents` WHERE parent_id= $parent_id  "));

        if(\request()->type == 'exam'){
            $content = Content::create([
                'title'      => request()->title,
                'course_id'  =>request()->course_id,
                'post_type'  => request()->type,
                'parent_id'  => request()->content_id,
                'status' => request()->status == 'true' ? 1 : 0,
                'order'  => $max_order[0]->max_order ? ($max_order[0]->max_order + 1) : 1,
            ]);
            $content->details()->create([
                'excerpt'    =>  request()->excerpt,
                'content_id' => $content->id,
                'type' => request()->type,
            ]);

            $content->exams()->create([
                'start_date'    =>  request()->start_date??Carbon::now(),
                'end_date' => request()->end_date,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'duration' => request()->duration??0,
                'pagination' => request()->pagination??1,
                'attempt_count' => request()->attempt_count??0,
            ]);

        }else{
            $content = Content::create([
                'title'      => request()->title,
                'course_id'  =>request()->course_id,
                'post_type'  => request()->type,
                'url'        => request()->url,
                'status' => request()->status == 'true' ? 1 : 0,
                'parent_id'  => request()->content_id,
                'order'  => $max_order[0]->max_order ? ($max_order[0]->max_order + 1) : 1,
            ]);

            if(!request()->url){
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

        }

        $content = Content::whereId($content->id)->with(['upload','details','exam'])->first();
        return response()->json([ 'status' => 'success','data' => $content]);
    }

    private function updateContentValidation($type){

        $mimes ='';
        switch ($type){
            case 'video': $mimes = '|mimes:mp4,mov,ogg,qt|max:100000' ; break;
            case 'audio': $mimes = '|required|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav'; break;
            case 'presentation': $mimes = '|mimes:ppt,pptx,pdf,docx'; break;
            case 'scorm': $mimes = '|mimes:pdf,docx'; break;
            default : $mimes = '';;
        }

        if ($mimes != ''){
            $file =  'required_without:url';
            if(\request()->hasFile('file')){
                $file = 'required_without:url|file'.$mimes;
            }

            $rules = [
                'title'      => "required|string",
                'url'        =>   "required_without:file",
                'file'      => $file,
            ];
        }else{

            $start_date = '';
            $end_date = '';
//            dd(strtotime(\request()->end_date));
            if( strtotime(\request()->start_date) && strtotime(\request()->end_date)  ){
                $start_date  = 'required|date|before:end_date';
                $end_date    = 'required|date|after:start_date';
            }else if(strtotime(\request()->start_date) && !strtotime(\request()->end_date) ){
                request()->request->add(['end_date' => null]);
                $start_date = '';
                $end_date = '';
            }

//            dd(\request()->end_date);
            $rules = [
                'title'      => "required|string",
//                'excerpt'    =>  "required|string",
                'duration'=>'nullable|numeric|gt:-1',
                'pagination'=>'nullable|numeric|gt:-1',
                'attempt_count'=>'nullable|numeric|gt:-1',
                'start_date'  => $start_date,
                'end_date'     => $end_date,
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
                'status' => request()->status == 'true' ? 1 : 0,
            ]);
            ContentDetails::where('content_id', request()->content_id)->update([
                'excerpt' => request()->excerpt,
            ]);

            Exam::where('content_id', request()->content_id)->update([
                'start_date'    =>  request()->start_date??Carbon::now(),
                'end_date' => request()->end_date,
                'duration' => request()->duration??0,
                'pagination' => request()->pagination??1,
                'attempt_count' => request()->attempt_count??0,
                'updated_by' => auth()->id(),
            ]);

        } else {
            $content = Content::whereId(request()->content_id)
                ->update([
                    'title' => request()->title,
                    'url' => request()->url,
                    'status' => request()->status == 'true' ? 1 : 0,
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

        $content = Content::whereId(request()->content_id)->with(['upload','details','exam'])->first();
        return response()->json([ 'status' => 'success','data' => $content]);

//        return response()->json([ 'status' => 'success']);
    }








}
