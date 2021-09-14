<?php

namespace App\Http\Controllers\Front\Consulting;

use App\Constant;
use App\Helpers\Active;
use App\Helpers\Recaptcha;
use App\Helpers\RedirectTransSlug;
use App\Models\Admin\Post;
use Illuminate\Http\Request;
use App\Models\Admin\Contact;
use App\Models\Admin\Partner;
use App\Models\Training\Report;
use App\Models\Training\Webinar;
use App\Models\Admin\Testimonial;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Admin\ContactRequest;
use Carbon\Carbon;

class ConsultingController extends Controller
{
    public function __construct()
    {
        $this->path = FRONT . '.consulting';
    }

    public function index(){

        $sliders = $this->GetPost('consulting-slider', 3);

        $USPs = $this->GetPost('usp-consulting', 4);

        $clients = Partner::GetPartners('clients', 18, true, 0, 1);

        $latest_insights = $this->GetPost('consulting-insights', 2);

        $services = $this->GetPost('consulting-service', 6);//consulting-service

        return view($this->path.'.index', compact('sliders', 'USPs', 'clients'
            , 'latest_insights', 'services'));
    }

    private function GetPost($post_type, $take){
        $posts = Post::where('post_type', $post_type)
            ->with(['upload:uploadable_id,uploadable_type,file,title', 'postMorph.constant'])
            ->lang()
            ->take($take)
            ->orderBy('post_date', 'desc')
            ->select('id', 'title', 'excerpt', 'slug', 'basic_id', 'url', 'post_date')
            ->get();
        return $posts;
    }

    //remove
//    public function single($post_id){
//        $consulting = Post::with('upload')->find($post_id);
//        $consultings = Post::with('upload')->latest()->take(3)->get();
//        return view($this->path.'.single', compact('consulting','consultings'));
//    }

    public function knowledgeCenter($slug=null){
        $recentArticles = $this->Articles($slug, 5)->get();

        $constants = $this->GetKnowledgeCenterConstants();

        $posts = $this->Articles($slug, 3)->page(3);

        $constant = Constant::where('slug', $slug)
            ->orWhere('slug', 'governance-consulting')
            ->first();

        return view($this->path.'.knowledge-center.index', compact('recentArticles', 'posts'
            , 'constants', 'constant'));
    }

    public function knowledgeCenterSingle($slug=null){

        if (is_null($slug)) {
            return redirect()->route($this->path . '.knowledge-center', ['post_type' => 'knowledge-center']);
        }

        $post = Post::with(['upload:uploadable_id,uploadable_type,file', 'postMorph.constant'])
            ->where('post_type', 'consulting-insights')
//            ->lang()
            ->whereSlug($slug)->first();



            $constant = $post->postMorph->constant;
            $relatedArticles = Post::with(['upload:uploadable_id,uploadable_type,file', 'postMorph.constant'])
            ->whereHas('postMorph.constant', function(Builder $query) use($constant){
                $query->where('id', $constant->id);
            })
            ->lang()
            ->get();

        if (!is_null($post)) {

            $post->increment('most_read');

            $recentArticles = $this->Articles($post->post_type, 5)->get();
//            dd($recentArticles);

            $constants = $this->GetKnowledgeCenterConstants();

            return view($this->path . '.knowledge-center.single', compact('recentArticles'
                , 'post', 'constants', 'relatedArticles'));
        }
        return redirect()->route('consulting.index');
    }

    private  function GetKnowledgeCenterConstants(){
        return Constant::where('post_type', 'consulting-insights')->orderBy('order')->get();
    }

    private function Articles($slug, $take=5){

        $isFound = Constant::where('post_type', $slug)
        ->orWhere('slug', $slug)
        ->count();
        if($isFound==0){
            abort(404);
        }

        $constant = Constant::where('slug', $slug)->select('id')->first();

        $posts = Post::with(['upload:uploadable_id,uploadable_type,file']);//, 'postMorph.constant'
        if(!is_null($constant)) {
            $constant_id = $constant->id;
            $posts = $posts->whereHas('postMorphs', function ($q) use ($constant_id) {
                $q->where('constant_id', $constant_id);
            });
        }
        $posts = $posts->lang()
            ->where('post_type', 'consulting-insights')
            ->latest()
            ->take($take)
            ->select('id', 'title', 'slug', 'basic_id', 'post_date', 'excerpt', 'details');
        return $posts;
    }

    public function knowledgeHub($post_type=null){

        $post_type = 'knowledge-center';
        $recentArticles = $this->Articles($post_type, 3)->get();
        $most_read = $this->Articles($post_type, 3)->orderBy('most_read', 'desc')->get();
        $constants = $this->GetKnowledgeCenterConstants();

        $posts = $this->Articles($post_type, 3)->page(3);

        $constant = Constant::where('slug', $post_type)
            ->orWhere('post_type', $post_type)
            ->first();

        $reports = Report::orderBy('id', 'desc')->take(2)->get();

        $webinars = Webinar::orderBy('id', 'desc')->take(2)->get();

        return view($this->path.'.knowledge-center.hub', compact('recentArticles', 'most_read', 'posts'
            , 'constants', 'constant', 'reports', 'webinars'));
    }

    public function webinars(){//$post_id

        // $posts = Webinar::with(['user'])->page(12);
        $posts = Webinar::whereHas('upload', function(Builder $query){
            $query->where('post_type', 'image');
        })->with(['user', 'upload']);

        if(!is_null(request()->event) && request()->event == 'happening') {
            $posts = $posts->where(function($query){
                $query->where('session_start_time', '<', Carbon::now()->format('Y-m-d h:i:s'));
            });
        }

        if(!is_null(request()->event) && request()->event == 'upcoming') {
            $posts = $posts->where(function($query){
                $query->where('session_start_time', '>=', Carbon::now()->format('Y-m-d h:i:s'));
            });
        }
        $posts = $posts->page();
        return view($this->path.'.webinars.index', compact('posts'));
    }

    public function webinarSingle($slug=null){//$post_id

        if(is_null($slug)){
            return redirect()->back();
        }

        $post = Webinar::with(['upload'])
        //->lang()
        ->whereSlug($slug)->first();

        //dd($post);
        if(!is_null($post)) {
            return view($this->path . '.webinars.single', compact('post'));
        }
        return redirect()->back();
    }


    public function reports(){

        // dd('test');
        $posts = Report::whereHas('upload', function(Builder $query){
            $query->where('post_type', 'image');
        })->with(['user', 'upload'])->page();
        // dump($posts);
        // foreach($posts as $post){
        //     dump($post->id.'==>'.$post->trans_title.'===>'.$post->upload->id);
        // }

        return view($this->path.'.reports.index', compact('posts'));
    }

    public function reportSingle($slug=null){//$post_id

        if(is_null($slug)){
            return redirect()->back();
        }

        $post = Report::with(['user'])
        //->lang()
        ->whereSlug($slug)->first();

        if(!is_null($post)) {

            return view($this->path . '.reports.single', compact('post'));
        }
        return redirect()->back();
    }

    public function consultingService(){

        $posts = $this->GetServices();

        return view($this->path.'.consulting-service.index', compact('posts'));
    }

    public function consultingServiceSingle($slug=null){

        if (is_null($slug)) {
            return redirect()->route($this->path . '.static.consulting-service');
        }

        $post = Post::with(['upload:uploadable_id,uploadable_type,file', 'services:id,master_id,title,excerpt,master_id,percentage'
        , 'serviceArchives:id,master_id,title,excerpt,details'])
            ->where('post_type', 'consulting-service')
            // ->lang()
            ->whereSlug($slug)->first();

        $post->increment('most_read');

        $postForTitle = Post::with(['upload:uploadable_id,uploadable_type,file', 'services:id,master_id,title,excerpt,master_id,percentage'
        , 'serviceArchives:id,master_id,title,excerpt,details'])
            ->where('post_type', 'consulting-service')
            ->lang()
            ->whereSlug($slug)->first();

        $services = $this->GetServices();

        $USPs = $this->GetPost('usp-consulting', 4);

        $clients = Partner::GetPartners('clients', 8, true, 0, 1);

        //Strat consulting-insights
        // $latest_insights = $this->GetPost('consulting-insights', 2);
        $slug1 = $array = [
            'project-program-portfolio-consulting'=>'p3m-consulting',
            'outsourcing'=>'outSourcing-consulting',
        ][$slug]??$slug;
        $latest_insights = Post::where('post_type', 'consulting-insights')
            ->whereHas('postMorph.constant', function(Builder $query) use($slug1){
                $query->where('constants.slug', $slug1);
            })
            ->with(['upload:uploadable_id,uploadable_type,file,title', 'postMorph.constant'])
            ->lang()
            ->take(2)
            ->orderBy('post_date', 'desc')
            ->select('id', 'title', 'excerpt', 'slug', 'basic_id', 'url', 'post_date')
            ->get();
        //End consulting-insights
        // if($post->id == 309 || $post->basic_id == 309)
        {
            return view($this->path . '.consulting-service.single-custom', compact('post', 'postForTitle', 'services', 'USPs', 'clients', 'latest_insights'));
        }
        return view($this->path . '.consulting-service.single', compact('post', 'postForTitle', 'services', 'USPs', 'clients', 'latest_insights'));
    }

    private function GetServices(){
        $posts = Post::with(['upload:uploadable_id,uploadable_type,file'])
            ->lang()
            ->where('post_type', 'consulting-service')
            ->orderBy('order', 'asc')
            ->select('id', 'title', 'slug', 'basic_id', 'post_date', 'excerpt')
            ->get();
        return $posts;
    }

    public function clients(){

        $clients = Partner::GetPartners('clients', 200, false, 0, 1);

        return view($this->path . '.partners.index', compact('clients'));
    }

    public function staticPage($slug){
        $post = Post::lang()
            ->where('slug', $slug)
            ->first();
        return view($this->path.'.static_page',compact('post'));
    }

    public function privacyPolicy()
    {
        return $this->staticPage('privacy-policy');
    }

    public function cookiesPolicy()
    {
        return $this->staticPage('cookies-policy');
    }

    public function aboutBakkah(){
        $aboutus = Post::with('upload')
            ->lang()
            ->where('post_type', 'about-us')
            ->where('group_slug', 'consulting')
            ->orderBy('order')
            ->get();

        $testimonials = null;
        return view($this->path.'.about-us.index',compact('aboutus', 'testimonials'));
    }

    public function contactusIndex($request_type=null){
        $posts=Post::where('post_type', 'consulting-service')->lang()->get();
        return view($this->path.'.contacts.index',compact('posts', 'request_type'));
    }

    public function contactusStore(Request $request){

        if(!Recaptcha::run()) {
            return back();
        }
        $validated = $this->validateRequest('consulting');
        $contact = Contact::create($validated);

        Active::Flash($contact->email, 'msg-inserted', 'success');

        if(request()->has('course_id')){
            return back();
        }
        return redirect()->route('consulting.static.contactusIndex');
    }

    public function careers(){
        return view($this->path.'.careers.index');
    }

    public function forCorporate(){
        $USPs = $this->GetPost('usp-consulting', 4);

        $clients = Partner::GetPartners('clients', 6, true, 0, 1);

        return view($this->path.'.for-corporate', compact('USPs', 'clients'));
    }

    protected function validateRequest($post_type){
        $messages = [
            'name.required' => __('formerrors.The name field is required.'),
            'name.max' => __('formerrors.The name may not be greater than 191 characters.'),
            'email.required' => __('formerrors.The email field is required.'),
            'email.email' => __('formerrors.The email must be a valid email address.'),
            'mobile.required' => __('formerrors.The mobile field is required.'),
            'mobile.numeric' => __('formerrors.The mobile must be a number.'),
            'mobile.digits_between' => __('formerrors.The mobile must be between 8 and 15 digits.'),
            'request_type.required' => __('formerrors.The request field is required.'),
            'request_type.exists' => __('formerrors.The selected request is invalid.'),
            'message.required' => __('formerrors.The message field is required.'),
        ];

        $data = request()->validate([
            'name'=>'required|max:191',
            'email'=>'required|max:191|email:rfc,dns',
            'mobile'=>'required|numeric|digits_between:6,15',
            'request_type'=>'required|exists:posts,id',
            'post_type'=>'',
            'message'=>'required',
            'created_by'=>'',
            'updated_by'=>'',
            'course_id'=>'',
        ], $messages);
        $data['post_type'] = $post_type;

        return $data;
    }
}
