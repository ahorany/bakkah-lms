<?php

namespace App\Http\Controllers\Training;

use App\Constant;
use App\Helpers\Active;
use App\Models\Training\Course;
use App\Models\Training\Report;
use App\Models\Admin\RelatedItem;
use App\Http\Controllers\Controller;
use App\Helpers\Models\RelatedHelper;
use App\Http\Requests\Training\ReportRequest;
use App\Models\Admin\Post;

class ReportController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'reports';
    }

    public function index(){

        $post_type = 'report';
        $trash = GetTrash();
        $reports = Report::with(['upload', 'user']);

        if(!is_null(request()->report_search)) {
            $reports = $reports->where(function($query){
                $query->where('title', 'like', '%'.request()->report_search.'%')
                ->orWhere('slug', 'like', '%'.request()->report_search.'%');
            });
        }

        $show_in_website = request()->has('show_in_website')?1:0;
        $reports = $reports->where('show_in_website', $show_in_website);

        $count = $reports->count();
        $reports = $reports->page();
        return Active::Index(compact('reports', 'count', 'post_type', 'trash'));
    }

    public function create(){
        $courses = Course::all();
        $related_courses = [];

        $articles = Post::lang()->get();
        $related_articles = [];

        return Active::Create([
            'courses' => $courses,
            'related_courses' => $related_courses,
            'articles' => $articles,
            'related_articles' => $related_articles,
        ]);
        // $reports= Reports::all();
        // // $users=User::where('user_type',41)->get();
        // // $status=Constant::where('parent_id',32)->get();
        // return Active::Create(compact('reports'));
    }

    public function store(ReportRequest $request){

        $validated = $this->Validated($request);
        $validated['details'] = null;
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;

        $report = Report::create($validated);
        $RelatedHelper = new RelatedHelper();
        $RelatedHelper->create($report->id, 'course_ids', 471, 476);
        $RelatedHelper->create($report->id, 'article_ids', 472, 476);

        Report::UploadFile($report, [
            'post_type'=>'en_image'
            , 'locale'=>'en'
            , 'upload_title'=>'en_upload_title'
            , 'upload_excerpt'=>'en_upload_excerpt'
        ], $name='en_image');

        Report::UploadFile($report, [
            'post_type'=>'ar_image'
            , 'locale'=>'ar'
            , 'upload_title'=>'ar_upload_title'
            , 'upload_excerpt'=>'ar_upload_excerpt'
        ], $name='ar_image');

        $this->uploadsPDF($report, 'en_pdf', 'en');
        $this->uploadsPDF($report, 'ar_pdf', 'ar');
        \App\Models\SEO\Seo::seo($report);

        return Active::Inserted($report->trans_title);
    }

    public function edit(Report $report){
        $RelatedHelper = New RelatedHelper();

        $courses = Course::all();
        $related_courses = $RelatedHelper->getRelatedItems($report->id, 471, 476);

        $articles = Post::lang()->get();
        $related_articles = $RelatedHelper->getRelatedItems($report->id, 472, 476);


        return Active::Edit([
            'eloquent' => $report,
            'courses' => $courses,
            'related_courses' => $related_courses,
            'articles' => $articles,
            'related_articles' => $related_articles,
        ]);
    }

    public function update(ReportRequest $request, Report $report){

        $validated = $this->Validated($request);
        $validated['details'] = null;
        $validated['updated_by'] = auth()->user()->id;

        Report::find($report->id)->update($validated);
        Report::SetMorph($report->id);
        Report::UploadFile($report, ['method'=>'update']);

        // dd(request()->course_ids, request()->article_ids);

        $RelatedHelper = new RelatedHelper();
        $RelatedHelper->update($report->id, 'course_ids', 471, 476);
        $RelatedHelper->update($report->id, 'article_ids', 472, 476);

        Report::UploadFile($report, [
            'post_type'=>'en_image'
            , 'locale'=>'en'
            , 'upload_title'=>'en_upload_title'
            , 'upload_excerpt'=>'en_upload_excerpt'
            , 'method'=>'update'
        ], $name='en_image');

        Report::UploadFile($report, [
            'post_type'=>'ar_image'
            , 'locale'=>'ar'
            , 'upload_title'=>'ar_upload_title'
            , 'upload_excerpt'=>'ar_upload_excerpt'
            , 'method'=>'update'
        ], $name='ar_image');

        $this->uploadsPDF($report, 'en_pdf', 'en');
        $this->uploadsPDF($report, 'ar_pdf', 'ar');
        \App\Models\SEO\Seo::seo($report);
//        dd();
        return Active::Updated($report->trans_title);
    }

    public function destroy(Report $report){
        Report::where('id', $report->id)->SoftTrash();
        return Active::Deleted($report->trans_title);
    }

    public function restore($report){
        Report::where('id', $report)->RestoreFromTrash();
        $report = Report::where('id', $report)->first();
        return Active::Restored($report->trans_title);
    }

    private function Validated($request){

        $validated = $request->validated();
        if(isset($request->show_in_website)){
            $validated['show_in_website'] = 1;
        }else{
            $validated['show_in_website'] = 0;
        }
        $validated['title'] = null;
        $validated['excerpt'] = null;
        $validated['pdf'] = null;
        $validated['details'] = null;
        $validated['updated_by'] = auth()->user()->id;

        return $validated;
    }

    private function uploadsPDF($model, $name='pdf', $locale='en'){
        if(request()->has($name)){
            $upload = $model->uploads()->where('post_type',$name)->first();

            $pdf = request()->file($name);
            $title = $pdf->getClientOriginalName();

            $fileName = date('Y-m-d-H-i-s') . '-' . $name. '-' . $title;
            $fileName = strtolower($fileName);

            if($pdf->move(public_path('upload/pdf/'), $fileName)){
                if(is_null($upload))
                {
                    $model->uploads()->create([
                        'title'=>$title,
                        'file'=>$fileName,
                        'extension'=>'pdf',
                        'post_type'=>'pdf',
                        'created_by'=>$model->created_by,
                        'updated_by'=>$model->updated_by,
                        'locale'=>$locale,
                    ]);
                }
                else
                {
                    $this->unlinkPDF($name, $upload->file);
                    $model->uploads()->update([
                        'title'=>$title,
                        'file'=>$fileName,
                        'extension'=>'pdf',
                        'post_type'=>'pdf',
                        'created_by'=>$model->created_by,
                        'updated_by'=>$model->updated_by,
                        'locale'=>$locale,
                    ]);
                }
            }
        }
    }

    private function unlinkPDF($name, $image){
        if(request()->hasFile($name) && !empty($name) && !is_null($name) && !empty($image) && !is_null($image))
        {
            if(file_exists(public_path('/upload/pdf/') . $image)){
                unlink(public_path('/upload/pdf/') . $image);
            }
        }
    }
}
