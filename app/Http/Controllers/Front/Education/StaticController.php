<?php

namespace App\Http\Controllers\Front\Education;

use App\User;
use App\Constant;
use Carbon\Carbon;
use App\Helpers\Active;
use App\Helpers\Mailchimp;
use App\Helpers\Recaptcha;
use App\Mail\WebinarEmail;
use App\Models\Admin\Post;
use Illuminate\Http\Request;
use App\Models\Admin\Contact;
use App\Models\Admin\Partner;
use App\Events\MailChimpEvent;
use App\Models\Training\Course;
use App\Models\Training\Report;
use App\Models\Training\Webinar;
use App\Models\Admin\RelatedItem;
use App\Models\Admin\Testimonial;
use App\Helpers\RedirectTransSlug;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Models\RelatedHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\ContactRequest;
use App\Models\Training\WebinarsRegistration;
use App\Helpers\Models\Training\SessionHelper;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Symfony\Component\HttpFoundation\AcceptHeaderItem;
use Illuminate\Database\Schema\Builder as SchemaBuilder;
use Illuminate\Http\Exceptions\HttpResponseException;

class StaticController extends Controller
{
    public function __construct(){
        $this->path = FRONT.'.education';
    }

    public function knowledgeCenter($post_type=null){

        $recentArticles = $this->Articles($post_type, 5)->get();

        $constants = $this->GetKnowledgeCenterConstants();

        $posts = $this->Articles($post_type, 3)->page(3);

        $constant = Constant::where('slug', $post_type)
            ->orWhere('post_type', $post_type)
            ->first();

        return view($this->path.'.knowledge-center.index', compact('recentArticles', 'posts'
            , 'constants', 'constant'));
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

        $reports = Report::orderBy('id', 'desc')
        ->where('show_in_website', 1)
        ->take(2)
        ->get();

        $webinars = Webinar::orderBy('id', 'desc')
        ->where('show_in_website', 1)
        ->take(2)
        ->get();

        return view($this->path.'.knowledge-center.hub', compact('recentArticles', 'most_read', 'posts'
            , 'constants', 'constant', 'reports', 'webinars'));
    }

    public function knowledgeCenterSingle($slug=null){//$post_id

        if(is_null($slug)){
            return redirect()->route('education.static.knowledge-center', ['post_type'=>'knowledge-center']);
        }

        $post = Post::with(['upload:uploadable_id,uploadable_type,file', 'postMorph.constant'])
        ->lang()
        ->whereSlug($slug)->first();

        if(!is_null($post)) {

            $post->increment('most_read');

            $recentArticles = $this->Articles($post->post_type, 5)->get();
            $constants = $this->GetKnowledgeCenterConstants();

            return view($this->path . '.knowledge-center.single', compact('recentArticles'
                , 'post', 'constants'));
        }
        return redirect()->route('education.static.knowledge-center', ['post_type'=>'knowledge-center']);
    }

    public function knowledgeCenterSingleHub($slug=null){//$post_id
        if(is_null($slug)){
            return redirect()->route('education.static.knowledge-center', ['post_type'=>'knowledge-center']);
        }

        $post = Post::with(['upload:uploadable_id,uploadable_type,file', 'postMorph.constant'])
//        ->lang()
        ->whereSlug($slug)->first();

        $this->previewButton($post);

        if (!$post) {
            abort(404);
        }
//        dd($post->locale);
       $post = RedirectTransSlug::checkPostSlug($slug,$post->locale,$post->basic_id,'education.static.knowledge-center.single');
//        $post = Post::with(['upload:uploadable_id,uploadable_type,file', 'postMorph.constant'])
//        ->lang()
//            ->whereSlug($slug)->first();

        if(!isset($post->postMorph->constant)){
            return redirect()->route('education.static.knowledge-hub');
        }
        $constant = $post->postMorph->constant;
        $relatedArticles = Post::with(['upload:uploadable_id,uploadable_type,file', 'postMorph.constant'])
        ->whereHas('postMorph.constant', function(Builder $query) use($constant){
            $query->where('id', $constant->id);
        })
        ->lang()
        ->get();

        $RelatedHelper = new RelatedHelper();
        $RelatedCourses = $RelatedHelper->Courses($post->id, 471, 472);
        //dd($RelatedCourses);
        $SessionHelper = new SessionHelper();

        if(!is_null($post)) {

            $post->increment('most_read');

            $recentArticles = $this->Articles($post->post_type, 5)->get();
            $constants = $this->GetKnowledgeCenterConstants();

            return view($this->path . '.knowledge-center.single-hub', compact('recentArticles'
                , 'post', 'constants', 'relatedArticles', 'RelatedCourses', 'SessionHelper'));
        }
        return redirect()->route('education.static.knowledge-center', ['post_type'=>'knowledge-center']);
    }

    private  function GetKnowledgeCenterConstants(){
        return Constant::where('post_type', 'knowledge-center')->orderBy('order')->get();
    }

    private function Articles($post_type, $take=5){

        $constant = Constant::where('slug', $post_type)->select('id')->first();

        $posts = Post::with(['upload:uploadable_id,uploadable_type,file,excerpt,title']);//, 'postMorph.constant'
        if(!is_null($constant)) {
            $constant_id = $constant->id;
            $posts = $posts->whereHas('postMorphs', function ($q) use ($constant_id) {
                $q->where('constant_id', $constant_id);
            });
        }
        $posts = $posts->lang()
            ->where('post_type', 'knowledge-center')
            ->where('show_in_website', 1)
            ->latest()
            ->take($take)
            ->select('id', 'title', 'slug', 'basic_id', 'excerpt', 'details', 'post_date', 'created_at');
        return $posts;
    }

    public function contactusIndex(){
        $request_type=Constant::where('parent_id',45)->get();
        return view($this->path.'.contacts.index',compact('request_type'));
    }

//    public function contactusStore(ContactRequest $request){
    public function contactusStore(Request $request){
        $validated = $this->validateRequest('learning');

        if(!Recaptcha::run()) {
            return back();
        }

        $contact = Contact::create($validated);
        Active::Flash($contact->email, __('education.msg-inserted'), 'success');

        if(request()->has('course_id')){
            return back();
        }
        return redirect()->route('education.static.contactusIndex');
    }

    public function staticPage($post_type){
        $post = Post::lang()
            ->where('post_type', $post_type)
            ->first();
        return view($this->path.'.static_page',compact('post'));
    }

    public function aboutBakkah(){
        $aboutus = Post::with('upload')
            ->lang()
            ->where('post_type', 'about-us')
            ->where('group_slug', 'learning')
            ->orderBy('order')->get();

        $testimonials = Testimonial::where('post_type', 'education')
            ->with(['userId.upload:uploadable_id,uploadable_type,file,title', 'course:id,short_title'])
            ->whereNotNull('activated_at')
            ->take(6)
            ->orderBy('order')
            ->get();
        return view($this->path.'.about-us.index',compact('aboutus', 'testimonials'));
    }

    public function forCorporate(){

        $partners = Partner::GetPartners('partners', 9, true, 1, 0);

//      $USPs = Post::where('post_type', 'USP')
        $USPs = $this->GetPost('critical-factor', 4, 'order', 'asc');

        return view($this->path.'.for-corporate',compact('partners', 'USPs'));
    }

    public function validateRequest($post_type){

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
            'request_type'=>'required|exists:constants,id',
            'post_type'=>'',
            'message'=>'required',
            'created_by'=>'',
            'updated_by'=>'',
            'course_id'=>'',
        ], $messages);


        $data['post_type'] = $post_type;

        return $data;
    }

    private function GetPost($post_type, $take, $order_field='post_date', $order='desc'){
        $posts = Post::where('post_type', $post_type)
        ->with('upload:uploadable_id,uploadable_type,file,title')
        ->lang()
        ->take($take)
        ->orderBy($order_field, $order)
        ->where('show_in_website',1)
        ->select('id', 'title', 'excerpt', 'url')
        ->get();
        return $posts;
    }

    public function partners(){//$post_id

        $posts = Partner::where('post_type', 'partners')
        ->where('type', 'education')
        ->with(['user'])->page(12);

        $SessionHelper = new SessionHelper();

        return view($this->path.'.partners.index', compact('posts', 'SessionHelper'));
    }

    public function partnerSingle($slug=null){//$post_id

        if(is_null($slug)){
            return redirect()->back();
        }

        $partner = Partner::with(['upload'])
        //->lang()
        ->whereSlug($slug)->first();

        if(!is_null($partner)) {

            $recentArticles = $this->Articles($partner->post_type, 5)->get();
            $constants = $this->GetKnowledgeCenterConstants();

            $SessionHelper = new SessionHelper();
            $courses = $SessionHelper->SessionsByPartners($partner->id);

            return view($this->path . '.partners.single', compact('partner', 'courses', 'SessionHelper'));
        }
        return redirect()->back();
    }

    // public function clients(){//$post_id

    //     $posts = Partner::where('post_type', 'clients')
    //     ->where('type', 'education')
    //     ->with(['user'])->page(12);

    //     $SessionHelper = new SessionHelper();

    //     return view($this->path.'.clients.index', compact('posts', 'SessionHelper'));
    // }

    public function clients(){

        $posts = Partner::GetPartners('clients', 200, false, 1, 0);

        $posts = Partner::where('post_type', 'clients')
        ->where('type', 'education')
        ->with(['user'])->page(12);

        return view($this->path . '.clients.index', compact('posts'));
    }

    public function webinars(){//$post_id

        // $posts = Webinar::with(['user'])->page(12);
        $posts = Webinar::whereHas('upload', function(Builder $query){
            $query->where('post_type', 'image');
        })
        ->where('show_in_website', 1)
        ->with(['user', 'upload']);

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

        $RelatedHelper = new RelatedHelper();
        $SessionHelper = new SessionHelper();

        $post = Webinar::with(['upload'])
//        ->lang()
        ->whereSlug($slug)->first();

        $this->previewButton($post);


        $RelatedCourses = $RelatedHelper->Courses($post->id, 471, 475);
        $relatedArticles = $RelatedHelper->Articles($post->id, 472, 475);


//        if (!$post) {
//            abort(404);
//        }
//        RedirectTransSlug::checkPostSlug($post->locale,$post->basic_id,'education.static.webinars.single');

        if(!is_null($post)) {
            return view($this->path . '.webinars.single', compact('post', 'RelatedCourses', 'SessionHelper', 'relatedArticles'));
        }
        return redirect()->back();
    }

    public function reports(){

        // dd('test');
        $posts = Report::whereHas('upload', function(Builder $query){
            $query->where('post_type', 'image');
        })
        ->where('show_in_website', 1)
        ->with(['user', 'upload'])
        ->page();
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
//        ->lang()
        ->whereSlug($slug)->first();

        $this->previewButton($post);

        $RelatedHelper = new RelatedHelper();
        $SessionHelper = new SessionHelper();
        $RelatedCourses = $RelatedHelper->Courses($post->id, 471, 476);
        $relatedArticles = $RelatedHelper->Articles($post->id, 472, 476);

        if(!is_null($post)) {

            return view($this->path . '.reports.single', compact('post', 'RelatedCourses', 'SessionHelper', 'relatedArticles'));
        }
        return redirect()->back();
    }

    public function newsletter(){
        // if(request()->has('email')){
        if(request()->email){
            // Add the user into the MailChimp list members
            $Mailchimp = new Mailchimp;
            $user = null;
            $response = $Mailchimp->sync($user, request()->email, "knowledge Hub");
            $responseData = json_decode($response, true);
            if($responseData['status']=='subscribed'){
                echo true;
            }else{
                echo false;
            }
        }else{
            echo true;
        }
    }

    public function webinarRegistrationSubmit(){

        // if(!Recaptcha::run()) {
        //     return false;
        //     exit();
        // }

        $validator = Validator::make(request()->all(), [
            'email'=>'required|email'
        ]);

        if(!$validator->fails()){

            $user = User::where('email', request()->email)->first();

            $is_register_before = null;
            if($user){
                $is_register_before = WebinarsRegistration::where('user_id', $user->id)
                    ->where('webinar_id', request()->webinar_id)
                    ->count();
                    // echo($is_register_before);
            }

            if($is_register_before){
                // This user registered in the same webinar before
                echo 'exist';
                exit();
            }else{

                $args = [
                    'name'=>null,
                    'mobile' => request()->mobile,
                    'gender_id' => -1,
                    'country_id' => -1,
                    'mail_subscribe' => 1,
                ];

                if(is_null($user)){

                    $user = User::create(array_merge($args, [
                        'email' => request()->email
                    ]));

                    // Add the user into the MailChimp list members
                    $Mailchimp = new Mailchimp;
                    $Mailchimp->sync($user, null, "Webinar");
                    // event(new MailChimpEvent($user, "Webinar"));
                    // event(new MailChimpEvent($user));
                }

                $added = WebinarsRegistration::create([
                    'user_id' => $user->id,
                    'webinar_id' => request()->webinar_id,
                ]);

                if ($added->exists) {
                    // This user added successfully in the webinar

                    // Send email here
                    $WebinarsRegistration = WebinarsRegistration::where('user_id', $user->id)
                                            ->where('webinar_id', request()->webinar_id)->first();

                    Mail::to($user->email)->send(new WebinarEmail($WebinarsRegistration));

                    // if(count(Mail::failures()) == 0){}

                    echo 'success';
                    exit();
                    // send email to user
                }else {
                    // Fail when trying add this user in the webinar
                    echo 'failure';
                    exit();
                }
            }

        }else{
            echo 'invalid_data';
            exit();
            // echo false;
        }
    }

    public function reportsDownload(){
        // echo 'aaaa'.request()->name.'  ----- '.request()->email;
        // if(request()->has('email')){
        //     // Add the user into the MailChimp list members
        //     $Mailchimp = new Mailchimp;
        //     $user = null;
        //     $response = $Mailchimp->sync($user, request()->email, "Report");
        //     $responseData = json_decode($response, true);
        //     if($responseData['status']=='subscribed'){
        //         echo true;
        //     }else{
        //         echo false;
        //     }
        // }else{
        //     echo true;
        // }
    }

    function algolia()
    {
        if(request()->has('q'))
        {
            if(!empty(request()->q))
            {
                $q = request()->q;

                //$posts = Post::search($q)->get();
                // $courses = Course::search($q)->get();//last work
                $courses = Course::search($q)->get()->map(function($data){
                    if(!is_null($data->excerpt)){
                        return $data;
                    }
                })->reject(function ($data) {
                    return empty($data);
                });

                // $local = app()->getLocale();
                // $posts = Post::search('pmp')->get()->map(function($data)use($local){
                //     if($data->locale==$local){
                //         return $data;
                //     }
                // })->reject(function ($data) {
                //     return empty($data);
                // });
                // dd($posts);

                //$posts->union($courses);
                //return $posts;
                return $courses;
            }
        }
        return response()->json('not found');
    }

    // Redirect Preview
    private function previewButton($var){
        if($var->show_in_website!=1 && !request()->has('preview')){
            throw new HttpResponseException(redirect(route('education.index')));
            }
        }

}
