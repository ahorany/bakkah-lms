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
use App\Helpers\CourseContentHelper;

class ContentController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:course.contents.list');
    }

    public function reset_order_contents($course_id){
        $i = 0;
        $contents = Content::where('course_id',$course_id)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
        foreach ($contents as $content){
            Content::where('course_id',$course_id)->where('id',$content->id)->update([
                "order" => $i
            ]);
            $i++;
        }
        return $contents;
    }

    public function save_content_order()
    {
        $list_of_ids = request()->data;
        $data = Content::select('id','order')->whereIn('id',$list_of_ids )->get();

        foreach ($data as $d){
            foreach ($list_of_ids as $index => $id){
                if($id == $d->id){
                    Content::where('id',$id )->update([
                        'order' => $index +1
                    ]);
                }
            }
        }
        return response()->json(['status' => true]);
    }

    public function contents()
    {
        $course_id = request()->course_id;
        $course = Course::with(['upload', 'user'])->where('id',$course_id)->first();
        $contents = Content::where('course_id',$course_id)
            ->whereNull('parent_id')
            ->with(['contents' => function($q){
                $q->with(['upload','details','exam'])->withCount('questions')->orderBy('order');
            },'details','exams'])
            ->orderBy('order')
//            ->orderBy('id')
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

        $course_id = request()->course_id;
        $max_order =  DB::select(DB::raw("SELECT MAX(`order`) as max_order FROM `contents` WHERE course_id = $course_id  AND parent_id IS NULL"));

        $content = Content::create([
            'title'      => request()->title,
            'course_id'  =>request()->course_id,
            'post_type'  => 'section',
            'order'  => $max_order[0]->max_order ? ($max_order[0]->max_order + 1) : 1,
            'hide_from_trainees'  =>request()->hide_from_trainees??false,
        ]);

        $content->details()->create([
            'excerpt'    =>  request()->excerpt,
        ]);
        $content = Content::whereId($content->id)->with(['details','contents'])->first();
//        $contents = Content::where('course_id',$course_id)->whereNull('parent_id')->with(['contents','details'])->latest()->get();
        return response()->json([ 'status' => 'success', 'section' => $content]);
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
            'hide_from_trainees'  =>request()->hide_from_trainees??false,
        ]);

        ContentDetails::updateOrCreate([
            'content_id' => request()->content_id
        ],[
            'excerpt'    =>  request()->excerpt,
        ]);
        return response()->json([ 'status' => 'success', 'section' => $content]);
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
                'pagination'=>'nullable|numeric|gt:0',
                'attempt_count'=>'nullable|numeric|gt:-1',
                'start_date'  => $start_date,
                'end_date'     => $end_date,
                'pass_mark' => "required|gt:-1|lt:101"
            ];
        }else{
            $mimes ='';
            switch ($type){
                case 'video': $mimes = '|mimes:mp4,mov,ogg,qt|max:200000' ; break;
                case 'audio': $mimes = '|required|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav'; break;
                case 'presentation': $mimes = '|mimes:ppt,pptx,pdf,doc,docx,xls,xlsx,jpeg,png'; break;
                case 'scorm': $mimes = '|mimes:zip'; break;
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
                    'time_limit'=>'nullable|numeric|gt:-1',
                ];
            }else{
                $rules = [
                    'title'      => "required|string",
                    'course_id'  =>'required|exists:courses,id',
                    'content_id' => 'required|exists:contents,id',
                    'file'      => 'required|file'.$mimes,
                    'time_limit'=>'nullable|numeric|gt:-1',
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
                'downloadable' => request()->downloadable == 'true' ? 1 : 0,
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
                'pass_mark' =>  request()->pass_mark,
                'shuffle_answers' => request()->shuffle_answers == 'true' ? 1 : 0,
            ]);

        }else{
            $content = Content::create([
                'title'      => request()->title,
                'course_id'  =>request()->course_id,
                'post_type'  => request()->type,
                'url' => request()->url == 'null' ? null : request()->url,
                'status' => request()->status == 'true' ? 1 : 0,
                'downloadable' => request()->downloadable == 'true' ? 1 : 0,
                'parent_id'  => request()->content_id,
                'order'  => $max_order[0]->max_order ? ($max_order[0]->max_order + 1) : 1,
                'time_limit'=> request()->time_limit,

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

                        if(request()->type == "scorm"){
                           $this->unzipScormFile();
                        }
            }

        }

        $content = Content::whereId($content->id)->with(['upload','details','exam'])->first();
        return response()->json([ 'status' => 'success','data' => $content]);
    }

    private function updateContentValidation($type){

        $mimes ='';
        switch ($type){
            case 'video': $mimes = '|mimes:mp4,mov,ogg,qt|max:200000' ; break;
            case 'audio': $mimes = '|required|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav'; break;
            case 'presentation': $mimes = '|mimes:ppt,pptx,pdf,doc,docx,xls,xlsx,jpeg,png'; break;
            case 'scorm': $mimes = '|mimes:zip'; break;
            default : $mimes = '';
        }

        if ($mimes != ''){
            if($type == "video"){
                $file = "";
                $content_id = request()->content_id;
                $sql = "SELECT *  FROM `uploads` WHERE `uploadable_id` = $content_id AND `uploadable_type` LIKE '%Content%'";
                $content_file_from_upload =  DB::select(DB::raw($sql));

                if(\request()->has("url") && \request()->url != "" && \request()->url != null){
                   if(count($content_file_from_upload) > 0){
                       $file = "";
                   }

                    $rules = [
                        'title'      => "required|string",
                        'url'        =>   "required_without:file",
                        'file'       =>   $file,
                        'time_limit' =>'nullable|numeric|gt:-1',
                    ];
                }else{
                    if(count($content_file_from_upload) == 0){
                        $file =  'required_without:url';
                        if(\request()->hasFile('file')){
                           $file = 'required_without:url|file'.$mimes;
                        }

                        $rules = [
                            'title'      => "required|string",
                            'url'        =>   "required_without:file",
                            'file'       =>   $file,
                            'time_limit'=>'nullable|numeric|gt:-1',
                        ];
                    }else{
                        $rules = [
                            'title'      => "required|string",
                            'url'        =>   "",
                            'file'       =>   $file,
                            'time_limit'=>'nullable|numeric|gt:-1',
                        ];
                    }
                }



            }else{
                $file =  'required_without:url';
                if(\request()->hasFile('file')){
                    $file = 'required_without:url|file'.$mimes;
                }

                $rules = [
                    'title'      => "required|string",
                    'url'        =>   "required_without:file",
                    'file'       => $file,
                    'time_limit' =>'nullable|numeric|gt:-1',
                ];
            }

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
                'pagination'=>'nullable|numeric|gt:0',
                'attempt_count'=>'nullable|numeric|gt:-1',
                'start_date'  => $start_date,
                'end_date'     => $end_date,
                'pass_mark' => "required|gt:-1|lt:101"

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
                'downloadable' => request()->downloadable == 'true' ? 1 : 0,
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
                'pass_mark' =>  request()->pass_mark,
                'shuffle_answers' => request()->shuffle_answers == 'true' ? 1 : 0,
            ]);

        } else {
            $content = Content::whereId(request()->content_id)
                ->update([
                    'title' => request()->title,
                    'url' => request()->url == 'null' ? null : request()->url,
                    'status' => request()->status == 'true' ? 1 : 0,
                    'downloadable' => request()->downloadable == 'true' ? 1 : 0,
                    'time_limit' => request()->time_limit,
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
                if(request()->type == "scorm"){
                    $this->unzipScormFile();
                }
            }
        }

        $content = Content::whereId(request()->content_id)->with(['upload','details','exam'])->first();
        return response()->json([ 'status' => 'success','data' => $content]);

//        return response()->json([ 'status' => 'success']);
    }


    private function unzipScormFile(){
        $fileName = date('Y-m-d-H-i-s') . '_' . trim(request()->file->getClientOriginalName());
        $fileName = str_replace(' ','_',$fileName);
        $fileName = str_replace(['(',')'],'_',$fileName);
        $fileName = trim(strtolower($fileName));
        $name=explode('.',$fileName)[0];

        $zip = new \ZipArchive();
        $x = $zip->open(public_path("upload/files/scorms/$fileName"));
        if ($x === true) {
            $zip->extractTo(public_path("upload/files/scorms/").$name);
            $zip->close();
       }
//        unlink(public_path("upload/files/scorms/$fileName"));
    }

    public function exam_preview_content($exam_id){

        $exam =  Content::where('id',$exam_id)->with(['exam','questions.answers'])->first();

        // Get next and prev
        $arr = CourseContentHelper::NextAndPreviouseNavigation($exam);
        $next = $arr['next'];
        $previous = $arr['previous'];
        // end next and prev

        return view('training.courses.contents.preview.exam', compact('exam', 'next', 'previous'));
    }
}
