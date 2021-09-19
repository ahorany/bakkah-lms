<?php

namespace App\Http\Controllers\Training;

use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Http\Requests\Training\TrainingOptionRequest;
use App\Models\Training\Feature;
use App\Models\Training\TrainingOption;

use App\Models\Training\Course;
use App\Constant;
use App\Models\Training\TrainingOptionFeature;
use Illuminate\Database\Eloquent\Builder;

class TrainingOptionController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'training_options';
    }

    public function index(){

        $all_courses = Course::orderBy('title->en')->get();
        $delivery_methods = Constant::where('parent_id', 10)->get();
        $lms_options = Constant::where('parent_id', 344)->get();

        $post_type = 'training-options';
        $trash = GetTrash();

        $training_options = TrainingOption::with(['course', 'user', 'lms']);
        // dd(request()->course_id);
        if(request()->has('course_id') && request()->course_id != -1){
            $training_options = $training_options->where('course_id', request()->course_id);
        }
        if(request()->has('training_option_id') && request()->training_option_id != -1){
            $training_options = $training_options->where('constant_id', request()->training_option_id);
        }
        if(request()->has('lms_id') && request()->lms_id != -1){
            $training_options = $training_options->where('lms_id', request()->lms_id);
        }

        // if(!is_null(request()->eval_api_search)) {
        //     $training_options = $training_options->where(function($query){
        //         $query->where('evaluation_api_code', 'like', '%'.request()->eval_api_search.'%');
        //     });
        // }
        $count = $training_options->count();
        $training_options = $training_options->page();
        return Active::Index(compact('training_options', 'all_courses', 'delivery_methods', 'lms_options', 'count', 'post_type', 'trash'));
    }

    public function create(){
        return Active::Create();
    }

    public function store(TrainingOptionRequest $request){

        $validated = $this->Validated($request->validated());

        // $validated = $request->validated();
        $validated['details'] = null;
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;

        $training_option = TrainingOption::create($validated);

        TrainingOption::SetMorph($training_option->id);
        TrainingOption::AddMorph($training_option->id, [23]);

        $this->uploadsVideo($training_option, 'teaser_video', null);

        return Active::Inserted($training_option->trans_title);
    }

    public function edit(TrainingOption $training_option){
        // dd($this->GetFeature($training_option));
        return Active::Edit(['eloquent'=>$training_option,'features'=>$this->GetFeature($training_option)]);
    }

    public function update(TrainingOptionRequest $request, TrainingOption $training_option){

        if(request()->has('feature_id')) {

             foreach (request()->feature_id as $key => $feature_id) {

                if ((request()->get('price-' . $feature_id) ==null || request()->get('price_usd-' . $feature_id) ==null)){

                    return redirect()->back()->with('message','Please Enter Price & Price_usd');
                }

                $excerpt = json_encode([
                    'en'=>request()->get('en_excerpt_' . $feature_id),
                    'ar'=>request()->get('ar_excerpt_' . $feature_id),
                ], JSON_UNESCAPED_UNICODE);

                TrainingOptionFeature::updateOrCreate([
                    'feature_id' => $feature_id,
                    'training_option_id' => $training_option->id,
                ], [
                    'price' => request()->get('price-' . $feature_id),
                    'price_usd' => request()->get('price_usd-' . $feature_id),
                    'xero_feature_code' => request()->get('xero_feature_code_' . $feature_id),
                    'is_include' => request()->get('is_include_' . $feature_id),
                    'excerpt' => $excerpt,
                ]);

             }
         }
        else{

           request()->feature_id = [];
        }

        $training_option->TrainingOptionFeature()->whereNotIn('feature_id', request()->feature_id)->delete();

        $validated = $this->Validated($request->validated());
        // $validated = $request->validated();
        $validated['details'] = null;
        $validated['updated_by'] = auth()->user()->id;

        $this->uploadsVideo($training_option, 'teaser_video', null);

        TrainingOption::find($training_option->id)->update($validated);
        return Active::Updated($training_option->trans_title);
    }

    public function destroy(TrainingOption $training_option){
        TrainingOption::where('id', $training_option->id)->SoftTrash();
        return Active::Deleted($training_option->trans_title);
    }

    public function restore($training_option){
        TrainingOption::where('id', $training_option)->RestoreFromTrash();
        $training_option = TrainingOption::where('id', $training_option)->first();
        return Active::Restored($training_option->trans_title);
    }

    private function Validated($validated){

        $validated['exam_is_included'] = 0;
        if(request()->has('exam_is_included')){
            $validated['exam_is_included'] = 1;
        }

        return $validated;
    }

    private function GetFeature(TrainingOption $training_option){
        return Feature::with(['TrainingOptionFeature'=>function($query) use($training_option){
            $query->where('training_option_id', $training_option->id);
        }])->get();
//
    }

    private function uploadsVideo($model, $name='video', $locale='en'){
// dd($model->constant_id);
        $full_name = $name;
        if($locale) {
            $full_name = $locale.'_'.$name;
        }

        if(request()->has($full_name)){
            // dd($full_name);
            $upload = $model->uploads()
            ->where('post_type', $name)
            ->where('locale', $locale)
            ->where('name', $model->constant_id)
            ->first();

            $pdf = request()->file($full_name);
            $title = $pdf->getClientOriginalName();

            $fileName = date('Y-m-d-H-i-s') . '-' . $full_name. '-' . $title;
            $fileName = strtolower($fileName);

            if($pdf->move(public_path('upload/video/'), $fileName)){

                if(is_null($upload))
                {
                    $model->uploads()->create([
                        'title'=>$title,
                        'name'=>$model->constant_id,
                        'file'=>$fileName,
                        'extension'=>'mp4',
                        'post_type'=>$name,
                        'created_by'=>$model->created_by,
                        'updated_by'=>$model->updated_by,
                        'locale'=>$locale,
                    ]);
                }
                else
                {
                    $this->unlinkVideo($name, $upload->file);
                    $model->uploads()->where('post_type', $name)
                    ->where('locale', $locale)
                    ->where('name', $model->constant_id)
                    ->update([
                        'title'=>$title,
                        'name'=>$model->constant_id,
                        'file'=>$fileName,
                        'extension'=>'mp4',
                        'post_type'=>$name,
                        'created_by'=>$model->created_by,
                        'updated_by'=>$model->updated_by,
                        'locale'=>$locale,
                    ]);
                }
            }
        }
    }

    private function unlinkVideo($name, $image){
        if(request()->hasFile($name) && !empty($name) && !is_null($name) && !empty($image) && !is_null($image))
        {
            if(file_exists(public_path('/upload/video/') . $image)){
                unlink(public_path('/upload/video/') . $image);
            }
        }
    }
}
