<?php

namespace App\Http\Controllers\Training;

use App\Constant;
use Carbon\Carbon;
use App\Helpers\Active;
use App\Models\Admin\Post;
use App\Models\Training\Course;
use App\Models\Training\Webinar;
use App\Models\Admin\RelatedItem;
use App\Http\Controllers\Controller;
use App\Helpers\Models\RelatedHelper;
use App\Http\Requests\Training\WebinarRequest;

class WebinarController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'webinars';
    }

    public function index(){

        $post_type = 'webinar';
        $trash = GetTrash();
        $webinars = Webinar::with(['upload', 'user']);

        if(!is_null(request()->webinar_search)) {
            $webinars = $webinars->where(function($query){
                $query->where('title', 'like', '%'.request()->webinar_search.'%')
                ->orWhere('slug', 'like', '%'.request()->webinar_search.'%')
                ->orWhere('zoom_link', 'like', '%'.request()->webinar_search.'%')
                ->orWhere('video_link', 'like', '%'.request()->webinar_search.'%');
            });
        }

        $show_in_website = request()->has('show_in_website')?1:0;
        $webinars = $webinars->where('show_in_website', $show_in_website);

        $count = $webinars->count();
        $webinars = $webinars->page();
        return Active::Index(compact('webinars', 'count', 'post_type', 'trash'));
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
    }

    public function store(WebinarRequest $request){

        $validated = $this->Validated($request);
        $validated['excerpt'] = null;
        $validated['details'] = null;
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;

        $webinar = Webinar::create($validated);
        $RelatedHelper = new RelatedHelper();
        $RelatedHelper->create($webinar->id, 'course_ids', 471, 475);
        $RelatedHelper->create($webinar->id, 'article_ids', 472, 475);
//        webinarDetail::Add($webinar);

        Webinar::UploadFile($webinar, [
            'post_type'=>'en_image'
            , 'locale'=>'en'
            , 'upload_title'=>'en_upload_title'
            , 'upload_excerpt'=>'en_upload_excerpt'
        ], $name='en_image');

        Webinar::UploadFile($webinar, [
            'post_type'=>'ar_image'
            , 'locale'=>'ar'
            , 'upload_title'=>'ar_upload_title'
            , 'upload_excerpt'=>'ar_upload_excerpt'
        ], $name='ar_image');

        \App\Models\SEO\Seo::seo($webinar);

        return Active::Inserted($webinar->trans_title);
    }

    public function edit(Webinar $webinar){
        $RelatedHelper = New RelatedHelper();

        $courses = Course::all();
        $related_courses = $RelatedHelper->getRelatedItems($webinar->id, 471, 475);

        $articles = Post::lang()->get();
        $related_articles = $RelatedHelper->getRelatedItems($webinar->id, 472, 475);


        return Active::Edit([
            'eloquent' => $webinar,
            'courses' => $courses,
            'related_courses' => $related_courses,
            'articles' => $articles,
            'related_articles' => $related_articles,
        ]);

    }

    public function update(WebinarRequest $request, Webinar $webinar){

        $validated = $this->Validated($request);
        $validated['excerpt'] = null;
        $validated['details'] = null;
        $validated['updated_by'] = auth()->user()->id;

        Webinar::find($webinar->id)->update($validated);
        Webinar::SetMorph($webinar->id);
        Webinar::UploadFile($webinar, ['method'=>'update']);

        $RelatedHelper = new RelatedHelper();
        $RelatedHelper->update($webinar->id, 'course_ids', 471, 475);
        $RelatedHelper->update($webinar->id, 'article_ids', 472, 475);

        Webinar::UploadFile($webinar, [
            'post_type'=>'en_image'
            , 'locale'=>'en'
            , 'upload_title'=>'en_upload_title'
            , 'upload_excerpt'=>'en_upload_excerpt'
            , 'method'=>'update'
        ], $name='en_image');

        Webinar::UploadFile($webinar, [
            'post_type'=>'ar_image'
            , 'locale'=>'ar'
            , 'upload_title'=>'ar_upload_title'
            , 'upload_excerpt'=>'ar_upload_excerpt'
            , 'method'=>'update'
        ], $name='ar_image');

        \App\Models\SEO\Seo::seo($webinar);
        return Active::Updated($webinar->trans_title);
    }

    public function destroy(Webinar $webinar){
        Webinar::where('id', $webinar->id)->SoftTrash();
        return Active::Deleted($webinar->trans_title);
    }

    public function restore($webinar){
        Webinar::where('id', $webinar)->RestoreFromTrash();
        $webinar = Webinar::where('id', $webinar)->first();
        return Active::Restored($webinar->trans_title);
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
        $validated['details'] = null;
        $validated['updated_by'] = auth()->user()->id;
        $validated['session_start_time'] = Carbon::createFromFormat('Y-m-d H:i', $validated['session_start_time'])->format('Y-m-d H:i:s');
        $validated['session_end_time'] = Carbon::createFromFormat('Y-m-d H:i', $validated['session_end_time'])->format('Y-m-d H:i:s');

        return $validated;
    }
}
