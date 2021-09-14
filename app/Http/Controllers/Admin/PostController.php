<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Constant;
use App\Helpers\Active;
use App\Helpers\Models\RelatedHelper;
use App\Models\Admin\Post;
use Illuminate\Http\Request;
use App\Models\Training\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Admin\RelatedItem;
use Illuminate\Database\Eloquent\Builder;

class PostController extends Controller
{
	public function __construct()
    {
        Active::$folder = 'posts';
    }

    public function index(){
        $post_type = GetPostType('post');
    	$group_slug = GetGroupSlug('learning');
    	$trash = GetTrash();
        $categories = Constant::where('post_type', $post_type)->get();

    	$posts = Post::with(['upload', 'user'])
        ->lang()
        ->where('post_type', $post_type)
        ->where('group_slug', $group_slug);

        if(!is_null(request()->post_search)) {
            $posts = $posts->where(function($query){
                $query->where('title', 'like', '%'.request()->post_search.'%')
                ->orWhere('details', 'like', '%'.request()->post_search.'%')
                ->orWhere('slug', 'like', '%'.request()->post_search.'%');
            });
        }
        if(request()->has('category_id') && request()->category_id!=-1){
            $posts = $posts->whereHas('postMorphs', function (Builder $query){
                $query->where('constant_id', request()->category_id);
            });
        }

        $show_in_website = request()->has('show_in_website')?1:0;
        $posts = $posts->where('show_in_website', $show_in_website);

        $count = $posts->count();
        $posts = $posts->page();

        return Active::Index(compact('posts', 'count', 'post_type', 'trash', 'categories'));
    }

    public function create(){
	    $post = null;
	    if(request()->has('origin_id')){
	        $post = Post::find(request()->origin_id);
        }
	    $coins = Constant::whereIn('id',[334,335])->get();

        $courses = Course::all();

        $related_courses = [];

        return Active::Create([
            'object' => Post::class,
            'coins' => $coins,
            'eloquent' => $post,
            'countries' => $this->getAllCountries(),
            'courses' => $courses,
            'related_courses' => $related_courses
        ]);
    }

    public function store(PostRequest $request){

        $RelatedHelper = new RelatedHelper();

        $validated = $this->validated($request);
        $post = Post::StoreData($validated);

        $RelatedHelper->create($post->id, 'course_ids', 471, 472);

        return Active::Inserted($post->title, ['post_type'=>$request->post_type]);
    }

    public function edit(Post $post){
        $coins = Constant::whereIn('id',[334,335])->get();
        $RelatedHelper = New RelatedHelper();

        $courses = Course::all();
        $related_courses = $RelatedHelper->getRelatedItems($post->id, 471, 472);

        return Active::Edit([
            'eloquent' => $post,
            'object' => Post::class,
            'coins' => $coins,
            'countries' => $this->getAllCountries(),
            'courses' => $courses,
            'related_courses' => $related_courses
        ]);
    }

    public function update(PostRequest $request, Post $post){

        $RelatedHelper = new RelatedHelper();

        $validated = $this->validated($request);
        $post = Post::UpdateData($post, $validated);

        $RelatedHelper->update($post->id, 'course_ids', 471, 472);
        // return $post;
        return Active::Updated($post->title);
    }

    public function destroy(Post $post, Request $request){
        Post::SoftTrash($post);
        return Active::Deleted($post->title);
    }

    public function restore($post){
        $post = Post::RestoreFromTrash($post);
        return Active::Restored($post->title);
    }

    protected function getAllCountries(){
        $countries = null;
        if(request()->has('post_type') && (request()->post_type == 'education-slider') || (request()->post_type == 'modal-campaign') || (request()->post_type == 'navbar-campaign')){
	        $countries = Constant::where('post_type','countries')->get();
        }
        return $countries;
    }

    private function validated($request){

        $validated = $request->validated();
        if(isset($request->show_in_website)){
            $validated['show_in_website'] = 1;
        }else{
            $validated['show_in_website'] = 0;
        }
        return $validated;
    }

    public function import_from_wp(){
        dd('stoped');
        $from_table = 'bak_posts_consulting';
        $bak_posts = DB::connection('mysql2')
        ->table($from_table)
        ->where('language_code', 'en')
//         ->whereIn('trid', ['41538'])
        ->select('*')
        ->get();
        foreach($bak_posts as $post){
            $post_type = 'consulting';
            // $_GET['origin_id'] = $post->id;
            // $_GET['locale'] = 'en';

            $validated = [
                'title'=>$post->post_title,
                'post_date'=>$post->post_date,
                'post_type'=>$post_type,
                'details'=>$post->post_content,
                'excerpt'=>$post->post_excerpt,
                'element_id'=>$post->element_id,
                'trid'=>$post->trid,
                'slug'=>$post->slug,
            ];
            $this_post = Post::StoreData($validated, null, 'en');

            //ar
            $bak_posts_ar = DB::connection('mysql2')
            ->table($from_table)
            ->where('language_code', 'ar')
            ->where('trid', $post->trid)
            ->select('*')
            ->get();
            if($bak_posts_ar->count()!=0){
                foreach($bak_posts_ar as $post_ar){
                    // $_GET['origin_id'] = $this_post->id;
                    // $_GET['locale'] = 'ar';
                    $validated = [
                        'title'=>$post_ar->post_title,
                        'post_date'=>$post_ar->post_date,
                        'post_type'=>$post_type,
                        'details'=>$post_ar->post_content,
                        'excerpt'=>$post_ar->post_excerpt,
                        'element_id'=>$post_ar->element_id,
                        'trid'=>$post_ar->trid,
                        'slug'=>$post_ar->slug,
                    ];
                    Post::StoreData($validated, $this_post->id, 'ar');
                }
            }
        }
        dd('Done');
    }
}
