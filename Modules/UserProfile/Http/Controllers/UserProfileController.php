<?php

namespace Modules\UserProfile\Http\Controllers;

use App\User;
use App\Constant;
use App\Http\Requests\ProfileRequest;
use App\Models\Admin\Upload;
use Illuminate\Http\Request;
use App\Models\Training\Cart;
use App\Models\Training\Course;
use App\Models\Training\Social;
use App\Models\Training\Wishlist;
use Illuminate\Routing\Controller;
use App\Models\Training\CartMaster;
use App\Models\Training\Experience;
use App\Models\Training\Payment;
use App\Profile;
use App\ProfileAnswer;
use App\ProfileAnswere;
use App\ProfileQuestion;
use App\ProfileQuestionUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'login', 'register', 'loginSubmit', 'registerSubmit', 'passwordReset', 'resetSubmit']]);

        $this->middleware('verified', ['except' => [
            'login', 'register', 'loginSubmit', 'registerSubmit', 'logout'
        ]]);
    }

    public function dashboard() {
        //$user = User::find(auth()->user()->id);
        $user = auth()->user();
        //$latestCart = Cart
//        dd($user->upload->file??null);
        return view('userprofile::users.index',compact('user'));
    }

    public function info() {
        $user = User::with(['upload', 'socials','experiences'])->findOrFail(auth()->user()->id);
        $genders = Constant::where('parent_id', 42)->get();
        $countries = Constant::where('post_type', 'countries')->get();
        // return $user;
        return view('userprofile::users.info',compact('user', 'genders', 'countries'));
    }

    public function home() {
        return view('userprofile::users.home');
    }

    public function payment_info() {
        $user = auth()->user();
        $payments = Payment::where('user_id',$user->id)
        ->where('paid_in','>',0)
        ->whereHas('cartMaster')
        ->with([
                'paymentStatus',
                'cartMaster.coin',
            ])->get();
        return view('userprofile::users.payment_info',compact('payments'));
    }

    public function referral() {
        return view('userprofile::users.referral');
    }

    public function invoice() {
        return view('userprofile::users.invoice');
    }

    public function request_tickets() {
        return view('userprofile::users.request_tickets');
    }

    public function complaintView($type) {
        $user = Auth::user();
        $courses = [];
        // return $type;
        if($user->carts->count() > 0) {
            foreach ($user->carts as $cart){
                $courses = Cart::where('course_id','!=',$cart->course->id)->with('course.uploads','session.trainingOption')->get();
            }
        }
        if($type == 'my_complaints'){
            $questions = ProfileQuestion::where('post_type','learn_complaints')->with('profile_answers.profile_question_users')->get();
        }else{
            $questions = ProfileQuestion::where('post_type','learn_refunds')->with('profile_answers.profile_question_users')->get();
        }

        return view('userprofile::users.complaint',compact('questions','user','type'));
    }

    public function complaintStore(ProfileRequest $request){
        $post_type = '';
        ($request->post_type == 'my_complaints') ? $post_type = 'learn_complaints' : $post_type = 'learn_refunds' ;
        $profile = ProfileQuestionUser::updateOrCreate([
            'user_id' => auth()->user()->id,
            'course_id' => $request->courses,
            'profile_answer_id' => $request->answer,
            'profile_answer_text' => $request->reason,
            'profile_question_id' => $request->question_id,
            'post_type' => $post_type,
        ]);

        if($profile) {
            session()->flash('success', __('education.Complaint has been sent successfully.'));
        }else {
            session()->flash('error', __('education.Failed to send complaint. Please send again.'));
        }
        return redirect()->route('user.my_complaints',$request->post_type);
    }

    public function myComplaints($type){
        $user = Auth::user();
        if($type == 'my_complaints'){
            $complaints = ProfileQuestionUser::where('post_type','learn_complaints')->with('products')->get();
        }else{
            $complaints = ProfileQuestionUser::where('post_type','learn_refunds')->with('products')->get();
        }
        return view('userprofile::users.my_complaints',compact('complaints','user','type'));
    }

    public function change_password() {
        return view('userprofile::users.change_password');
    }

    public function save_password() {

        request()->validate([
            'old_password' => 'required|password|min:7|max:16',
            'new_password' => 'required|min:7|max:16',
            'password_confirmation' => 'required|min:7|max:16|same:new_password',
        ]);
        $user = User::find(auth()->user()->id);
        $password = $user->password;

        if(Hash::check(request()->old_password, $password) &&(request()->new_password == request()->password_confirmation)){
            $password = Hash::make(request()->new_password);
        }

        $user->update([
            'password' => $password,
        ]);
        return view('userprofile::users.home');
    }

    public function update($id,Request $request){

        $request->validate([
            'new_password' => 'nullable|min:8|confirmed',
            'socials.*' => 'nullable',
            'experience' => 'nullable'
        ]);

        $user = User::findOrFail($id);
        $password = $user->password;

        if (Hash::check($request->password, $password)) {
            $password = Hash::make($request->new_password);
        }

        // $social->updateOrCreate([
        //     'link' => $request->socials->twitter,
        //     'user_id' => auth()->user()->id,
        // ]);
            // dd($request->experience);
        // $request->experience =  array_filter($request->experience, 'strlen');

        $user->update([
            'email'     => $request->email,
            'name'      => json_encode(["en" => $request->en_name, "ar" => $request->ar_name]),
            'headline'  => $request->headline,
            'lang'      => $request->language,
            'bio'       => $request->bio,
            'mobile'    => $request->mobile,
            'gender_id' => $request->gender_id,
            'password'  => $password
        ]);

        User::UploadFile($user, ['method'=>'update']);

        foreach($request->socials as $type => $link) {
            if($link) {
                Social::updateOrCreate([
                    'user_id'=>$id,
                    'type' => $type
                ], [
                    'user_id' => $id,
                    'type' => $type,
                    'link' => $link
                ]);
            }
        }

        if($request->experience != '')
        {
            foreach($request->experience as $experience) {
                Experience::updateOrCreate([
                    'user_id'=>$id,
                    'name' => $experience
                ], [
                    'user_id' => $id,
                    'name' => $experience,
                ]);
        }
        }


        return redirect()->back();
    }

    // public function notification() {
    //     return view('userprofile::users.notification');
    // }

    public function my_courses() {
        $user = Auth::user();
        $courses = [];
        if($user->carts->count() > 0) {
            foreach ($user->carts as $cart){
                $courses = Cart::where('course_id','!=',$cart->course->id)->with('course.uploads','session.trainingOption')->get();
            }
        }
        return view('userprofile::users.my_courses',compact('courses','user'));
    }

    public function wishlist() {
        $user = Auth::user();
        return view('userprofile::users.wishlist',compact('user'));
    }

    public function certifications() {

        // where('certificate_file','!=',null)->
        $carts = Cart::where('user_id', auth()->user()->id)->with('course')->get();
        return view('userprofile::users.certifications',compact('carts'));
    }

    public function downloadCertifications($id){
        $cart= Cart::where('certificate_file','!=',null)->where('id',$id)->first();
        $folder = null;
        if($cart->course->certificate_type_id == 324){
            $folder = 'certificate';
        }elseif($cart->course->certificate_type_id == 325){
            $folder = 'attendance';
        }
        if($folder){
            $path = public_path("certificates/$folder/").$cart->certificate_file;
            return response()->download($path);
        }
        return false;
    }

    public function previewCertifications($id){
        $cart= Cart::where('certificate_file','!=',null)->where('id',$id)->first();
        $folder = null;
        if($cart->course->certificate_type_id == 324){
            $folder = 'certificate';
        }elseif($cart->course->certificate_type_id == 325){
            $folder = 'attendance';
        }
        if($folder){
            $path = public_path("certificates/$folder/").$cart->certificate_file;
            return response()->file($path);
        }
        return false;
    }

    public function payment_history() {

        $users = \auth()->user();

        return view('userprofile::users.payment_info',['users'=>$users]);

    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/');
    }

    public function cart() {
        return view('userprofile::users.cart');
    }

    public function login()
    {
        if(Auth::check()){
            return redirect('/');
        }
        return view('userprofile::users.login');
    }

    public function loginSubmit(Request $request)
    {
        $data = Validator::validate($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($data)) {

            if(Cookie::get('user_token')) {

                Cart::whereIn('user_token', [Cookie::get('user_token')])
                ->where('payment_status', 63)
                ->whereNull('user_id')
                ->update([
                    'user_id' => auth()->id(),
                ]);

                CartMaster::whereIn('user_token', [Cookie::get('user_token')])
                ->where('payment_status', 63)
                ->whereNull('user_id')
                ->update([
                    'user_id' => auth()->id(),
                ]);

            }

            if(request()->has('redirectTo')) {
                return redirect()->route('education.cart');
            }

            if(request()->has('action') && request()->action == 'wishlist') {
                Wishlist::create([
                    'user_id' => auth()->id(),
                    'training_option_id' => request()->option,
                    'session_id' => request()->session_id
                ]);

                return redirect(request()->redirect);
            }

            return redirect()->route('user.my_courses');

        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);

        return back();
    }

    public function register()
    {
        if(Auth::check()){
            return redirect('/');
        }
        return view('userprofile::users.register');
    }

    public function registerSubmit(Request $request)
    {
        $request->validate([
            'en_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required',
            'password' => 'required|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();

        if($user) {

            if(is_null($user->password)) {
                $user->update(['password' => Hash::make($request->password)]);
            }

        }else {

            $user = User::create([
                'name' => $request->en_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'user_type' => 41
            ]);
        }

        // if(session('user_token')) {
        // if(Cookie::get('user_token')) {

        //     // Cart::whereIn('user_token', [session('user_token')])->update([
        //     Cart::whereIn('user_token', [Cookie::get('user_token')])->update([
        //         'user_id' => auth()->id()
        //     ]);

        //     // CartMaster::whereIn('user_token', [session('user_token')])->update([
        //     CartMaster::whereIn('user_token', [Cookie::get('user_token')])->update([
        //         'user_id' => auth()->id(),
        //     ]);
        // }

        if(request()->has('action') && request()->action == 'wishlist') {
            Wishlist::create([
                'user_id' => auth()->id(),
                'training_option_id' => request()->option,
                'session_id' => request()->session_id
            ]);

            return redirect(request()->redirect);
        }

        Auth::login($user);

        if(request()->has('redirectTo')) {
            return redirect()->route('education.cart');
        }

        if(request()->has('action') && request()->action == 'wishlist') {
            return redirect(request()->redirect);
        }

        return redirect()->route('user.my_courses');
    }

    // public function passwordReset() {
    //     return view('userprofile::users.password_reset');
    // }

    // public function resetSubmit() {
    //     dd(request()->all());
    // }

}