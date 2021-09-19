<?php

namespace App\Http\Controllers\Admin;

use App\Constant;
use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Admin\Testimonial;
use App\Models\Training\Course;
use App\User;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function __construct()
    {
        Active::$folder = 'testimonials';
    }

    public function index(){

        $post_type = GetPostType('education');
        $trash = GetTrash();

        $testimonials = Testimonial::with(['user', 'userId', 'course'])
            ->where('post_type', $post_type);

        $count = $testimonials->count();
        $testimonials = $testimonials->page();
        return Active::Index(compact('testimonials', 'count', 'post_type', 'trash'));
    }

    public function create(){
        $courses=Course::all();
        $users = User::whereIn('user_type', [31,41,315])->orderby('name')->get();
        // $users=User::where('user_type',41)->get();
        $status=Constant::where('parent_id',32)->get();
        return Active::Create(compact('courses','users','status'));
    }

    public function store(TestimonialRequest $request){

        $validated = $this->Validated($request->validated());
        $validated['created_by'] = auth()->user()->id;

        $testimonial = Testimonial::create($validated);

        \App\Models\SEO\Seo::seo($testimonial);

        return Active::Inserted($testimonial->trans_name, ['post_type'=>$testimonial->post_type]);
    }

    public function edit(Testimonial $testimonial){
        $courses=Course::all();
        $users=User::where('user_type',41)->get();
        $status=Constant::where('parent_id',32)->get();
        return Active::Edit(['eloquent'=>$testimonial, 'post_type'=>$testimonial->post_type,
            'courses'=>$courses,'users'=>$users,'status'=>$status]);
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial){

        $validated = $this->validated($request->validated());
        $validated['updated_by'] = auth()->user()->id;

        Testimonial::find($testimonial->id)->update($validated);
        \App\Models\SEO\Seo::seo($testimonial);

        return Active::Updated($testimonial->trans_name);
    }

    public function destroy(Testimonial $testimonial, Request $request){
        Testimonial::where('id', $testimonial->id)->SoftTrash();
        return Active::Deleted($testimonial->title);
    }

    public function restore($testimonial){
        Testimonial::where('id', $testimonial)->RestoreFromTrash();

        $testimonial = Testimonial::where('id', $testimonial)->first();
        return Active::Restored($testimonial->title);
    }

    private function Validated($validated){
        $validated['excerpt'] = null;
        $validated['updated_by'] = auth()->user()->id;

        $validated['show_in_home'] = 0;
        if(request()->has('show_in_home')){
            $validated['show_in_home'] = 1;
        }
        if(request()['status']==33){
            $validated['activated_by'] = auth()->user()->id;
            $validated['activated_at'] = now();
        }else{
            $validated['activated_by'] = null;
        }

        return $validated;
    }
}
