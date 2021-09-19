<?php

namespace App\Http\Controllers\Front\Education;

use App\Notifications\sendTestNotfication;
use PDO;
// use App\Events\NewTraineeHasRegisteredEvent;
// use App\Events\NewPrepareRetargetDiscountRegisterTraineeEvent;
// use App\Events\NewRetargetDiscountRegisterTraineeEvent;
use App\User;
use App\Constant;
use Carbon\Carbon;
use App\Mail\Invoice;
use App\Helpers\Active;
use App\Jobs\SurveyJob;
use App\Helpers\Mailchimp;
use App\Helpers\Recaptcha;
use App\Models\Admin\Post;
use App\Mail\RetargetEmail;
use Jenssegers\Agent\Agent;
use App\Models\Admin\Partner;
use App\Models\Training\Cart;
use App\Models\Training\Exam;
use App\Events\MailChimpEvent;
use App\Models\Training\Answer;
use App\Models\Training\Course;
use App\Models\Training\Option;
use App\Models\Training\Session;
use App\Models\Admin\RelatedItem;
use App\Models\Admin\Testimonial;
use App\Models\Training\Question;
use App\Helpers\Models\UserHelper;
// use Illuminate\Support\Facades\Cache;

// use App\Mail\RetargetEmail;
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Database\Eloquent\Model;
// use Stevebauman\Location\Facades\Location;
// use App\Mail\PaymentReceipt;
// use App\Mail\MoodleLms;
use App\Jobs\Retarget\DiscountJob;
use App\Mail\RetargetCampainEmail;
use App\Models\Training\CartTrace;
use Illuminate\Support\Facades\DB;
use App\Models\Training\CartMaster;
use App\Http\Controllers\Controller;
use App\Models\Training\CartFeature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Models\RelatedHelper;
use App\Mail\RetargetEmail\LastChance;
use App\Models\Training\CourseInterest;
use App\Jobs\LastChanceRetargetEmailJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Models\Training\Discount\Discount;
use App\Helpers\Controllers\RegisterHelper;
use App\Helpers\Models\Training\CartHelper;
use App\Http\Requests\Education\UserRequest;
use App\Helpers\Models\Training\CourseHelper;
use App\Helpers\Models\Training\SessionHelper;
use App\Models\Training\TrainingOptionFeature;
use App\Models\Training\Discount\DiscountDetail;
use App\Helpers\Jobs\Retarget\ControlDiscountJob;
use App\Models\Training\Discount\RetargetDiscount;
use App\Http\Requests\Education\UserIntrestRequest;
use App\Models\Training\Feature;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class EducationController extends Controller
{
    public function __construct(){
        $this->path = FRONT.'.education';
    }

    public function changeCurrency($currency='SAR'){

        // if(auth()->check()){
        //     if(auth()->user()->id==1){
        //         session()->put('coinID', 334);
        //     }
        // }
        if(auth()->check())
        {
            if(auth()->user()->user_type==315 || auth()->user()->user_type==31)
            {
                session()->forget('change-currency');
                session()->forget('countryCode');
                session()->forget('coinID');
                session()->forget('countryID');
                session()->forget('coinPrice');
                if($currency=='SAR')
                {
                    session()->put('change-currency', 1);
                    session()->put('countryCode', 'SA');
                    session()->put('coinID', 334);
                    session()->put('countryID', 58);
                    session()->put('coinPrice', 1);
                }
                else if($currency=='USD')
                {
                    session()->put('change-currency', 1);
                    session()->put('countryCode', 'PS');
                    session()->put('coinID', 335);
                    session()->put('countryID', 235);
                    session()->put('coinPrice', 3.8);
                }

                if(request()->has('slug') && request()->has('session_id')){
                    return redirect()->route('education.courses.register', [
                        'slug'=>request()->slug,
                        'session_id'=>request()->session_id,
                    ]);
                }
            }
        }
        return back();
    }

    public function convert($size)
    {
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }

    public function Anonymous($key, $value=null){
        $args = [
            'sliders'=>['assertNotEmpty', 'assertSee'],
        ];
        foreach($args[$key] as $fns){
            dump($fns);
        }
        // $this->assertNotEmpty($value);
        // $response->assertSee($value->first()->title);
    }

    public function lastChance(){

        /** We need another condition to exception paid status for the same course */
        $carts = Cart::whereNotNull('id')
        ->whereHas('payment', function (Builder $query) {
            $query->where('payment_status', 63);
        })
        ->whereNotIn('user_id', Cart::where('retarget_email_id', 352)->pluck('user_id'))
        ->with(['course:id,title,certificate_type_id', 'payment', 'userId.countries', 'trainingOption']);
        // $cart = $carts->where('retarget_email_id', '328');
        // $carts = $carts->take(20);
        // $carts = $carts->where('id', 286);
        $minute = 1;
        $carts = $carts->chunk(25, function($data) use (&$minute){
            // foreach($data as $cart){
            //     $constant_id = $cart->trainingOption->constant_id;
            //     dump($cart->id.'==>'.$constant_id);
            //     // $discount = Discount::GetDiscount($cart->course_id, $constant_id);
            //     // $discountOBJ = null;
            //     // $discount_rate = 0;
            //     // if(isset($discount->id)){
            //     //     $discountOBJ = Discount::find($discount->id);
            //     //     $discount_rate = $discount->value;
            //     // }

            //     // $course = Course::where('id', $cart->course_id)
            //     // ->with(['trainingOption'=>function($query) use($constant_id){
            //     //     $query->where('constant_id', '=', $constant_id);
            //     // }, 'trainingOption.session'=>function($query){
            //     //     $query->whereDate('date_from', '>=', now());
            //     //     $query->where('session_start_time', '>=', DateTimeNowAddHours());
            //     // }])
            //     // ->first();
            //     // dump($cart.'===>'.$cart->trainingOption->session->exam_price);
            // }
            $job = (new LastChanceRetargetEmailJob($data));
                        //->delay(\Carbon\Carbon::now()->addMinutes($minute));
            dispatch($job);

            $minute += 2;
            // dump($minute);
        });

        dd('Test');
    }

    public function pdf(){
        // $mpdf = new \Mpdf\Mpdf();

        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);

        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle("Certificate");
        $mpdf->SetAuthor(__('education.app_title'));
        $mpdf->SetWatermarkText("Paid");
        $mpdf->showWatermarkText = true;
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage');

        // Beginning Buffer to save PHP variables and HTML tags
        ob_start();

        $mpdf->WriteHTML('<h1>Hello world Testing!</h1>');

        ob_end_clean();

        $mpdf->Output();

        // $mpdf->WriteHTML(utf8_encode($html));
        // $content = $mpdf->Output('', 'S');
        exit();
        dd('ddd');
    }

    private function RetargetEmail()
    {
        // $carts = $carts = Cart::whereBetween('registered_at', ['2021-01-01', '2021-01-31'])
        // ->where('payment_status', 63)
        // ->with(['userId', 'course', 'trainingOption'])
        // ->get();
        // dd($carts);
        $seconds = 1;
        $carts = DB::table('webinar_users_temp')->get();
        foreach($carts as $cart){

            // dump($cart->trainingOption->type->post_type);
            // dump($cart->course_id.'===>'.$cart->session_id.'===>'.$cart->userId->email);

            // Mail::to("abed_348@hotmail.com")->queue(new RetargetCampainEmail($cart));
            Mail::to("abed_348@hotmail.com")->queue(new RetargetCampainEmail());
            dd('test');
            // $job = (new RetargetCampain($cart))
            //             ->delay(\Carbon\Carbon::now()->addSecond($seconds));
            // dispatch($job);

            $seconds += 2;
        }
        dd(null);
        // LastChanceRetargetEmailJob
    }

    public function index(){

        if (auth()->check()){
            auth()->user()->notify(new sendTestNotfication());
        }
        // dd(bcrypt('anasser@bakkah11234'));
        // $carts = Cart::where('user_id', 1)->withTrashed()->get();
        // foreach($carts as $cart){
        //     CartFeature::where('master_id', $cart->id)->forceDelete();
        //     CartMaster::where('id', $cart->master_id)->forceDelete();
        // }
        // Cart::where('user_id', 1)->withTrashed()->forceDelete();
        // dd('Done');

        // $carts = Cart::whereNotNull('id')
        //                 ->whereHas('userId', function (Builder $query) {
        //                         $query->where('name', 'like', '%'.trim('han').'%')
        //                             ->orWhere('email', 'like', '%'.trim('han').'%');
        //                     });

        //                 $carts = $carts->orWhere('invoice_number', 'like', '%'.trim('han').'%');

        //                 $carts = $carts->with(['course:id,title', 'payment', 'userId'])->get();

        //                 // dd($carts);

        //                 $count = $carts->count();
        //                 if($count){
        //                     $ret_data = '<ul id="search_can_ul">';
        //                     foreach($carts as $cart){
        //                         // $ret_data .= $cart;

        //                         $ret_data .= '<li><span>' . $cart->userId->trans_name??null . ' - ' .  $cart->userId->email??null . ' - ' . $cart->invoice_number??null . ' - <span style="color:#fb4400"><b>' . $cart->trainingOption->training_name??null . ' | ' . $cart->session->published_from??null . ' - ' . $cart->session->published_to??null . '</b></span></span><span class="btn" data-id="' . $cart->id . '"><i class="fa fa-plus"></i></span></li>';

        //                     }

        //                     dd($ret_data);
        //                 }

        // dd('zzzzzz');

        // $this->RetargetEmail();
        // dd('done');
        // $cart = Cart::find(3351);
        // $ControlDiscountJob = new ControlDiscountJob($cart->id);
        // $ControlDiscountJob->RunControlJob();

        // dd('Done');
        // $cart_id = $cart->id;
        // // dd($cart);
        // // // $session = Session::find($cart->session_id);
        // $session = $cart->session()
        // ->whereDate('session_start_time', '>=', DateTimeNowAddHours())
        // ->first();
        // // dd($session);

        // //Active
        // if(!is_null($session))
        // {
        //     $discount = null;
        //     if(!is_null($cart->discount_id)){
        //         $discount = DiscountDetail::with('discountType')->find($cart->discount_id);
        //     }

        //     //We have a discount (or active)
        //     if(!is_null($discount))
        //     {
        //         dump($discount->discountType->slug);
        //         if($discount->discountType->slug=='auto-date')//By Sessions
        //         {
        //             //We need discount before start date
        //             //We are not need anything, just apply the same discount
        //             //Send DiscountJob

        //             // $job = (new DiscountJob($cart_id, $RetargetDiscount->value))
        //             //     ->delay(\Carbon\Carbon::now()->addMinutes($delay));
        //             // dispatch($job);
        //         }
        //         else if($discount->discountType->slug=='custom-date')//By date_from and date_to
        //         {
        //             //We need discount between start date and end date
        //             //Then Discount is Active
        //             if($discount->date_from >= DateTimeNow() && $discount->date_to <= DateTimeNow())
        //             {
        //                 $delay = 30;

        //                 $job = (new ControlDiscountJob($cart_id))
        //                 ->delay(\Carbon\Carbon::now()->addMinutes($delay));
        //                 dispatch($job);
        //             }
        //         }
        //     }
        //     else
        //     {
        //         //We don't have a discount
        //     }
        //     dd($discount);
        // }
        // else
        // {
        //     //InActive: Go to single page
        // }
        // dd('Test');
        // // RetargetDiscount::DispatchJob($cart->id, 328);

        // if(auth()->check()){
        //     if(auth()->user()->id==2){
        //         // $when = now()->addSeconds(5);
        //         // $cart = Cart::find(2628);
        //         // Mail::to("abed_348@hotmail.com")->queue(new Invoice($cart));
        //         \Artisan::call('queue:work');
        //         dd('Test');
        //     }
        // }

        // dd((int)(72.19));

        // dd('Doneee');
         // --------- Survey -----------
        //  Get data for survey
        // dd('survey');
        // $users = User::whereIn('id', [1,2,5390, 3])->chunk(2, function($data){
        // $minute = 0;
        // DB::table('user_surveys')
        // ->where('type_id', 0)
        // // ->where('id', 57)
        // ->orderBy('id', 'asc')
        // ->chunk(2, function($data)use(&$minute){

        //     $contacts = [];
        //     foreach($data as $user){
        //         array_push($contacts, [
        //             'email'=>$user->email,
        //             'fName'=>$user->fName,
        //             'mobile'=>$user->mobile,
        //             'Course'=>$user->Course,
        //             'Session'=>$user->Session,
        //             'DeliveryMethode'=>$user->DeliveryMethode,
        //             'category'=>$user->category,
        //         ]);
        //     }
        //     $job = (new SurveyJob(json_encode($contacts), 'A/baf87a8f'))
        //                 ->delay(\Carbon\Carbon::now()->addMinutes($minute));
        //     dispatch($job);
        //     dump($minute);
        //     $minute += 1;
        //     // $this->SurveyAPI1('A/baf87a8f', json_encode($contacts));
        // });
        // // $users = User::whereIn('id', [1,2])->get();
        // // dump(json_encode($contacts));
        // dd('Test');

        // ==============================

        // $when = now()->addSeconds(5);
        // $cart = Cart::find(286);
        // Mail::to("abed_348@hotmail.com")->later($when, new Invoice($cart));
        // dd($cart);
        // dd(bcrypt(''));
        // $carts = Cart::whereNotNull('id')
        // ->whereHas('payment', function (Builder $query) {
        //     $query->where('payment_status', 63);
        // })
        // ->with(['course:id,title,certificate_type_id', 'payment', 'userId.countries']);
        // // $cart = $carts->where('retarget_email_id', '328');
        // // $carts = $carts->take(20);
        // // $carts = $carts->where('id', 286);
        // $minute = 1;
        // $carts = $carts->chunk(20, function($data) use (&$minute){
        //     $job = (new LastChanceRetargetEmailJob($data))
        //                 ->delay(\Carbon\Carbon::now()->addMinutes($minute));
        //     dispatch($job);

        //     $minute += 2;
        // });

        // dd('Test');
        // // $carts = $carts->get();
        // /*dd('test');
        // foreach($carts as $cart){
        //     dump($cart->id);
        // }
        // // dd('Test');
        // $carts = $carts = Cart::whereNotNull('id');
        // $carts = $carts->whereHas('payment', function (Builder $query) {
        //     $query->where('payment_status', 63);
        // });
        // $carts = $carts->with(['course:id,title,certificate_type_id', 'payment', 'userId.countries']);
        // // $cart = $carts->where('retarget_email_id', '328');
        // // $carts = $carts->take(20);
        // $carts = $carts->where('id', 286);
        // $carts = $carts->get();*/
        // // dd($carts);
        // $seconds = 1;
        // foreach($carts as $cart){
        //     // $cart = Cart::find(286);
        //     $constant_id = $cart->trainingOption->constant_id;
        //     $discount = Discount::GetDiscount($cart->course_id, $constant_id);
        //     $discountOBJ = null;
        //     $discount_rate = 0;
        //     if(isset($discount->id)){
        //         $discountOBJ = Discount::find($discount->id);
        //         $discount_rate = $discount->value;
        //     }

        //     $course = Course::where('id', $cart->course_id)
        //     ->with(['trainingOption'=>function($query) use($constant_id){
        //         $query->where('constant_id', '=', $constant_id);
        //     }, 'trainingOption.session'=>function($query){
        //         $query->whereDate('date_from', '>=', now());
        //         $query->where('session_start_time', '>=', DateTimeNowAddHours());
        //     }])
        //     ->first();

        //     if(isset($course->trainingOption->session->exam_price)){
        //         $discount_value = ($discount_rate/100) * $course->trainingOption->session->price;
        //         //where('id', $cart->id)->
        //         $cart = Cart::updateOrCreate([
        //             'user_id'=>$cart->user_id,
        //             'course_id'=>$course->id,
        //             'session_id'=>$course->trainingOption->session->id,
        //             'training_option_id' => $course->trainingOption->session->training_option_id,//??
        //             'status_id'=>327,
        //         ],[
        //             'price' => NumberFormat($course->trainingOption->session->price),
        //             'discount_id' => $discount->id??null,
        //             'discount' => NumberFormat($discount_rate),
        //             'discount_value' => NumberFormat($discount_value),
        //             'exam_price' => NumberFormat($course->trainingOption->session->exam_price),
        //             'take2_price' => NumberFormat($cart->take2_price),
        //             'total' => $course->trainingOption->session->GetSubTotal($discountOBJ, $course->exam_is_included, $cart->take2_price),
        //             'vat' => NumberFormat($course->trainingOption->session->vat),
        //             'vat_value' => $course->trainingOption->session->GetVatValue($discountOBJ, $course->exam_is_included, $cart->take2_price),
        //             'total_after_vat' => $course->trainingOption->session->GetTotalValue($discountOBJ, $course->exam_is_included, $cart->take2_price),
        //             'retarget_email_id'=>352,
        //             'retarget_email_date'=>now(),
        //             'invoice_number' => date("His").rand(1234, 9632),//??
        //             'coin_id'=>1,
        //             'coin_price'=>1,
        //         ]);
        //     }

        //     if(isset($course->trainingOption->session->exam_price)){
        //         $discount_value = ($discount_rate/100) * $course->trainingOption->session->price;
        //         $args = [
        //             'course_id'=>$course->id.'==>'.$cart->course->en_title,
        //             'course_title'=>$course->trans_title,
        //             'session_id'=>$course->trainingOption->session->id,
        //             'price' => NumberFormat($course->trainingOption->session->price),
        //             'discount_id' => $discount->id??null,
        //             'discount' => NumberFormat($discount_rate),
        //             'discount_value' => NumberFormat($discount_value),
        //             'exam_price' => NumberFormat($course->trainingOption->session->exam_price),
        //             'take2_price' => NumberFormat($cart->take2_price),
        //             'total' => $course->trainingOption->session->GetSubTotal($discountOBJ, $course->exam_is_included, $cart->take2_price),
        //             'vat' => NumberFormat($course->trainingOption->session->vat),
        //             'vat_value' => $course->trainingOption->session->GetVatValue($discountOBJ, $course->exam_is_included, $cart->take2_price),
        //             'total_after_vat' => $course->trainingOption->session->GetTotalValue($discountOBJ, $course->exam_is_included, $cart->take2_price),
        //             'retarget_email_id'=>352,
        //             'retarget_email_date'=>now(),
        //             'href'=>route('epay.checkout', ['cart'=>$cart->id]),
        //         ];
        //         $seconds++;
        //         dump($args);

        //         // Mail::to($cart->userId->email)
        //         //     ->send(new LastChance($cart, 341));
        //     }
        //     // $job = (new LastChanceRetargetEmailJob($cart))
        //     //             ->delay(\Carbon\Carbon::now()->addSecond($seconds));
        //     // dispatch($job);
        // }
        // dd($seconds);
        // dd('Done');
        ///////////////////////
        // $carts = $carts = Cart::whereNotNull('id');
        // $carts = $carts->whereHas('payment', function (Builder $query) {
        //     $query->where('payment_status', 63);
        // });
        // $carts = $carts->with(['course:id,title,certificate_type_id', 'payment', 'userId.countries']);
        // // $cart = $carts->where('retarget_email_id', '328');
        // $carts = $carts->take(5);
        // $carts = $carts->get();
        // // dd($carts);
        // $seconds = 1;
        // foreach($carts as $cart){

        //     dump($cart->id.'==>'.$cart->course_id.'===>'.$cart->session_id.'====>'.$cart->total.'=>'.$cart->discount.'==>'.$cart->discount_value.'==>'.$cart->retarget_discount);
        //     // $job = (new LastChanceRetargetEmailJob($cart))
        //     //             ->delay(\Carbon\Carbon::now()->addSecond($seconds));
        //     // dispatch($job);

        //     $seconds += 2;
        // }
        // dd(null);
        // LastChanceRetargetEmailJob

        // $course = Course::find(1);
        // dd($course->ar_short_excerpt);

        // --------- Survey -----------
        // $this->SurveyAPI1();
        // dd('Test');

        // $cart = Cart::find(2526);
        // Mail::to("abed_348@hotmail.com")->send(new Invoice($cart));
        // dd('Invoice');

        // $this->RunCurl();
        // dd('RunCurl');

        // RetargetDiscount::DispatchJob(2533, 328);
        // dd('Tets');

        // event(new NewPrepareRetargetDiscountRegisterTraineeEvent($cart));
        // if(auth()->check()){
        //     if(auth()->user()->id==1){
        //         $cart = Cart::find(999);
        //         event(new NewPrepareRetargetDiscountRegisterTraineeEvent($cart));
        //         dd('Sent Email');
        //     }
        // }

        // $this->Anonymous('sliders');

        // $discount_id = $cart->discount_id;
        // ->where('course_id', $cart->course_id)->first()
        // $discountMethod = $cart->whereHas('discountMethod', function(Builder $query)use($discount_id){
        //     $query->where('end_date', '>=', now());
        //     // $query->where('id', $discount_id);
        // })
        // ->with('discountMethod')
        // ->get();
        // foreach($discountMethod as $discountMetho){
        //     dump($discountMetho->discount_id);
        // }
        // dd($cart);
        // $this->PrepareRetargetDiscountRegisterTraineeListener($cart);
        // // // Mail::to($cart->userId->email)
        // // //         ->send(new RetargetEmail($cart, 5));
        // // // event(new NewPrepareRetargetDiscountRegisterTraineeEvent($cart));
        // dd('Sent Email');

        // dd('Test');

        /*
        update training_options to1
        inner join post_morphs pm on pm.postable_id = to1.course_id
        set pm.postable_type='App\\Models\\Training\\TrainingOption'
        ,pm.postable_id=to1.id
        where to1.constant_id=13
        and pm.postable_type='App\\Models\\Training\\Course'
        and pm.constant_id=23;

        insert into post_morphs(postable_id, postable_type, constant_id, created_at, updated_at, table_id)
        select id, 'App\\Models\\Training\\TrainingOption', 23, now(), now(), 25
        from training_options
        where constant_id = 11;
        */

        // select to1.id, to1.course_id
        // from training_options to1
        // inner join courses c on to1.course_id = c.id
        // where to1.constant_id=13
        $array = $this->__index_param();

        return view($this->path.'.index', $array);
    }

    public function sessions($category=null){

        // $constant = Constant::find(13);
        // $constant = Feature::find(2);
        // dd($constant->xeroAccount->prepayment_account??null);

        $category_id = null;
        $method = null;
        $title = __('education.Professional Training Programs');
        if(!is_null($category)){

            $methods = ['self-paced', 'live-online', 'exam-simulation'];
            if(!in_array($category, $methods)){

                $category_type = explode( '-courses', $category )[0]??$category;
                $category = Constant::where('post_type', 'course')
                ->where('slug', $category_type)
                ->first();

                $category_id = $category->id??null;
                if(is_null($category_id)){
                    return $this->single($category_type, $method='online-training');
                }
                $title = $category->trans_name;
            }
            else{
                $method = $category;
                $category = null;
            }
        }

        $SessionHelper = new SessionHelper();
        // $courses = DB::select('select * from users where active = ?', [1]);
        $constants = Constant::Categories();
        $courses = $SessionHelper->Sessions(null, $category_id);
        $route_name = 'education.courses';

        // ->where('courses.type_id', -1)
        // dd($courses);
        return view($this->path.'.products.index', compact('constants', 'courses', 'SessionHelper', 'title'
        , 'route_name', 'category', 'method'));
    }

    public function hot_deals($category=null) {

        $category_id = null;
        $method = null;
        $title = __('education.Hot Deals');
        if(!is_null($category)){

            $methods = ['self-paced', 'live-online', 'exam-simulation'];
            if(!in_array($category, $methods)){

                $category_type = explode( '-courses', $category )[0]??$category;
                $category = Constant::where('post_type', 'course')
                ->where('slug', $category_type)
                ->first();

                $category_id = $category->id??null;
                if(is_null($category_id)){
                    return $this->single($category_type, $method='online-training');
                }
                $title = $category->trans_name;
            }
            else{
                $method = $category;
                $category = null;
            }
        }

        $SessionHelper = new SessionHelper();
        $constants = Constant::Categories();
        $courses = $SessionHelper->HotDeails(null, $category_id);
        $route_name = 'education.hot-deals';

        return view($this->path.'.products.index', compact('constants', 'courses', 'SessionHelper', 'title'
        , 'route_name', 'category', 'method'));
    }

    public function single($slug=null, $method='online-training'){

        if($method != 'online-training' && $method != 'self-study') {
            abort(404);
        }
        $SessionHelper = new SessionHelper();

        $course = Course::where('slug', $slug)
        ->with('seo', 'postMorph')
        ->first();

        if(is_null($course)){
            return redirect()->route('education.index');
        }

        if($course->show_in_website!=1 && !request()->has('preview')){
            return redirect()->route('education.index');
        }

        $sessions = $SessionHelper->Single($slug, true);
        $CardsSingles = $SessionHelper->CardsSingle($course, $sessions->whereNotNull('session_id'));
        // foreach($CardsSingles as $CardsSingle){
        //     dump($CardsSingle->course);
        // }
        // dd($CardsSingles);
        $constants = Constant::whereIn('parent_id', [25,26])
            ->whereNotIn('id', [309, 310, 27])
            ->orderBy('order')
            ->get();
        // dump($CardsSingles);
        $USPs = Post::GetPost('critical-factor', 4, 'order', 'asc');
        $clients = Partner::GetPartners('clients', 12, true, 1, 0);
        $option_slug = $course->trainingOption->constant->slug??'auto-date';

        $RelatedCourses = $SessionHelper->Sessions(null, $course->postMorph->constant_id);

        $RelatedHelper = new RelatedHelper();
        $relatedArticles = $RelatedHelper->Articles($course->id, 472, 471, 'item_id', 'parent_id');
        // dd($relatedArticles);
        // 'selfCourse'
        return view($this->path.'.products.single.index', compact('course', 'sessions', 'CardsSingles', 'SessionHelper', 'constants', 'USPs', 'clients', 'option_slug', 'method', 'RelatedCourses', 'relatedArticles'));
    }
    //         }, 'trainingOptions.sessions'=>function($query){
    //             $query->whereDate('date_from', '>=', now());
    //             $query->where('session_start_time', '>=', DateTimeNowAddHours());
    //         }], ['trainingOptions.detail'])
    //         ->first();

    //     if(is_null($course)){
    //         return redirect()->route('education.hot-deals');
    //     }

    //     $course_id = $course->id;
    //         // ->find($course_id);

    //     $USPs = Post::GetPost('critical-factor', 4, 'order', 'asc');
    //     $clients = Partner::GetPartners('clients', 12, true, 1, 0);

    //     $selfCourse = Course::with('upload:uploadable_id,uploadable_type,file,title')
    //         ->with(['trainingOptions'=>function($query){
    //             $query->where('constant_id', '=', 11);
    //         }, 'trainingOption'=>function($query){
    //             $query->where('constant_id', '=', 11);
    //         }, 'trainingOptions.session', 'trainingOptions.detail'])
    //         ->find($course_id);

    //     return view($this->path.'.courses.single.index', compact('course_id', 'course'
    //         , 'constants', 'selfCourse', 'USPs', 'clients'));
    // }

    public function trainingSchedule(){

        $CourseHelper = new CourseHelper();
        $all_courses = $CourseHelper->AllCoursesExternal();

        $courses = null;
        $selfCourse = null;
        $SessionHelper = new SessionHelper();
        $sessions = $SessionHelper->TrainingOption();

        $all_courses_ar = [];
        $all_sessions = [];
        foreach($sessions as $session){
            if(!in_array($session->id, $all_courses_ar))
            {
                if(!is_null($session->session_id))
                {
                    array_push($all_courses_ar, $session->id);
                    array_push($all_sessions, [
                        'id'=>$session->id,
                        'slug'=>$session->slug,
                        'title'=>$session->title,
                        'order'=>$session->order,
                    ]);
                }
            }
        }
        $all_sessions = collect($all_sessions)->sortBy('order');
        // ->orderBy('order', 'asc')
        // dd($all_sessions);
        $selfCourse = null;
        // dump($courses);
        // dd($this->path);
        return view($this->path.'.products.training-schedule.index', compact('courses', 'sessions'
            , 'all_courses', 'selfCourse', 'SessionHelper', 'all_sessions'));
//         dd('New');

//         $all_courses = Course::has('trainingOptions.sessions')
//             ->with(['trainingOptions.sessions'=>function($query){
//                 $query->whereDate('date_from', '>=', now());
//                 $query->where('session_start_time', '>=', DateTimeNowAddHours());
//             }])
//             ->orderBy('order')
//             ->get();

//         $courses = Course::whereHas('trainingOptions.sessions', function (Builder $query) {
// //            if(request()->has('method_id') && request()->method_id != -1){
// //                $query->where('constant_id', request()->method_id);
// //            }
//             if(request()->has('date_from') && !is_null(request()->date_from)){
//                 $query->whereDate('date_from', '>=', request()->date_from);
//             }
//             if(request()->has('date_to') && !is_null(request()->date_to)){
//                 $query->whereDate('date_to', '<=', request()->date_to);
//             }
//         })
//         ->with(['trainingOptions.sessions'=>function($query){
//             $query->whereDate('date_from', '>=', now());
//             $query->where('session_start_time', '>=', DateTimeNowAddHours());
//             if(request()->has('date_from') && !is_null(request()->date_from)){
//                 $query->whereDate('date_from', '>=', request()->date_from);
//             }
//             if(request()->has('date_to') && !is_null(request()->date_to)){
//                 $query->whereDate('date_to', '<=', request()->date_to);
//             }
//         }]);
//         if(request()->has('course_id') && request()->course_id != -1){
//             $courses = $courses->where('id', request()->course_id);
//         }
//         $courses = $courses->get();
//         $selfCourse = null;

//     return view($this->path.'.courses.training-schedule.index', compact('courses'
//             , 'all_courses', 'selfCourse'));
    }


    public function interest($slug=null){

        $course = Course::where('slug', $slug)->first();

        $genders = Constant::where('parent_id', 42)->get();
        $countries = Constant::where('post_type', 'countries')->get();

        return view($this->path.'.products.register.interest',compact('genders','countries','course'));
    }

    public function interestSubmit(UserIntrestRequest $request){

        if(!Recaptcha::run()) {
            return back();
        }

        request()->ar_name = request()->en_name;
        $RegisterHelper = new RegisterHelper();

        $validated = $RegisterHelper->UserValidated($request->validated());
        $user = User::where('email', $validated['email'])->first();

        $data = json_encode([
            'en'=>$validated['en_name'],
            'ar'=>$validated['en_name']
        ], JSON_UNESCAPED_UNICODE);

        $args = [
            'name'=>$data,
            'country_id' => $validated['country_id'],
            'mail_subscribe' => 1,
            'mobile' => $validated['mobile'],
            'gender_id' => $validated['gender_id'],
        ];
        if(is_null($user)){

            $user = User::create(array_merge($args, [
                'email'=>$validated['email']
            ]));
            // Add the user into the MailChimp list members
            // $Mailchimp = new Mailchimp;
            // $Mailchimp->sync($user, null, "Course Interest");
            event(new MailChimpEvent($user, "Course Interest"));
        }

        CourseInterest::create([
            'user_id' => $user->id,
            'course_id' => request()->course_id,
        ]);

        Active::Flash($user->email, 'thanks for your interestings', 'success');

        return back();
    }

    private function GetSessionCourse($course_id){

        $training_optioArray=array();
        $training_option = DB::table('training_options')
            ->select('id')
            ->where('course_id', '=', $course_id)
            ->get()->toArray();
        $array = json_decode(json_encode($training_option), true);
        foreach ($array as $key=>$value){
            array_push($training_optioArray, $value['id']);
        }
        $sessions = Session::whereIn('training_option_id',$training_optioArray)
            ->orderBy('date_from', 'asc')
//            ->orderBy('training_option_id')
            ->get();
        return $sessions;
    }

    // private function random_str(
    //     $length,
    //     $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!#$%^*_'
    // ) {
    //     $str = '';
    //     $max = mb_strlen($keyspace, '8bit') - 1;
    //     if ($max < 1) {
    //         throw new Exception('$keyspace must be at least two characters long');
    //     }
    //     for ($i = 0; $i < $length; ++$i) {
    //         $str .= $keyspace[random_int(0, $max)];
    //     }
    //     return $str;
    // }

    // private function GenerateUserLMS($email)
    // {
    //     $email_array      =  explode('@', $email);
    //     $username_from_email  =  str_replace(".","",$email_array['0']);
    //     $e_portal_username    = strtolower($username_from_email);
    //     $e_portal_password    = $e_portal_username.'@'.$this->random_str(3);
    //     return [
    //         'username'=>$e_portal_username,
    //         'password'=>$e_portal_password,
    //     ];
    // }

    public function register($slug=null){

        if(request()->has('session_id')){

            $course = Course::where('slug', $slug)->first();

            $genders = Constant::where('parent_id', 42)->get();
            $countries = Constant::where('post_type', 'countries')->get();

            $SessionHelper = new SessionHelper();

            $session = $SessionHelper->SingleForCheckout($slug)
            ->where('session_id', request()->session_id)
            ->first()
            ;

            $trainingOptionFeatures = TrainingOptionFeature::TrainingOptionFeatures($session->training_option_id);

            if(!is_null($session)){
                $GetQuestions = Exam::GetQuestions($course);
                $exam_id = $GetQuestions['exam_id']??null;
                $questions = $GetQuestions['questions']??null;

                return view($this->path.'.products.register.index',compact('genders', 'countries', 'course', 'session'
                , 'SessionHelper', 'trainingOptionFeatures', 'exam_id', 'questions'));
            }
            else{
                return redirect()->route('education.courses.single', ['slug'=>$course->slug??null]);
            }
        }
    }

    public function registerSubmit(UserRequest $request){

        if(!Recaptcha::run()) {
            return back();
        }
        request()->ar_name = request()->en_name;

        $RegisterHelper = new RegisterHelper();
        $UserHelper = new UserHelper();

        $validated = $RegisterHelper->UserValidated($request->validated());

        $user = $UserHelper->UpdateOrCreate($validated);

        // dd('test');
        if(request()->has('session_id')) {

            if(request()->has('session_id') && !is_null(request()->session_id))
            {
                $session = Session::with('trainingOption')
                ->find(request()->session_id);

                if(!is_null($session)){

                    $RegisterHelperVal = $RegisterHelper->UpdateOrCreate($user, $session);
                    $cartMaster = $RegisterHelperVal['cartMaster'];
                    $cart = $RegisterHelperVal['cart'];

                    // $coin_id = Cache::get('coinID_'.request()->ip());
                    // $coin_price = ($coin_id==335) ? USD_PRICE : 1;
                    // ////////////////////// Start By Ahorany
                    // CartMaster::UpdateSummation($cartMaster->id);
                    ////////////////////// End By Ahorany

                    // CartTrace::Create([
                    //     'master_id'=>$cart->id,
                    //     'status_id'=>327,
                    // ]);
                    // $master_id = $cartMaster->id;

                    // $course = Course::find($session->course_id);
                    $cart = Cart::find($cart->id);
                    if($cart->total_after_vat!=0){

                        $send_email = true;
                        if(auth()->check()){
                            if(auth()->user()->user_type==315){
                                $send_email = false;
                            }
                        }

                        if($send_email){
                            // if($cart->retarget_discount==1)
                            {
                                // if($user->id!=1 && $user->id!=5390)
                                {
                                    RetargetDiscount::DispatchJob($cart->id, 361);
                                }
                            }
                        }
                    }

                    $quesionSubmit = $this->quesionSubmit($cart->id);
                    if(!is_null($quesionSubmit)){
                        if($quesionSubmit->fails()){
                            return back()->withInput()->withErrors($quesionSubmit->errors());
                        }
                    }

                    // if($cart->retrieved_code && request()->PaymentRemaining == 0 && request()->ValidRetrievedCode == 'yes') {
                    //     session()->put('code', '');
                    //     session()->put('payment_status', 68);

                    //     Cart::where('id', $cart->id)->update(['payment_status'=>68]);
                    //     CartMaster::where('id', $cartMaster->id)->update(['payment_status'=>68]);

                    //     return redirect()->route('epay.payment.final_thanks', [
                    //         'status' => 'success'
                    //     ]);
                    // }

                    if($cart->total==0){

                        session()->forget('zeroPaid');
                        session()->forget('code');
                        session()->put('zeroPaid', true);
                        session()->forget('payment_status');
                        session()->put('payment_status', 332);

                        // need condition to check if retrive code is null
                        Cart::where('id', $cart->id)->update(['payment_status'=>332]);
                        CartMaster::where('id', $cartMaster->id)->update(['payment_status'=>332]);

                        $promo_code = $cart->promo_code;
                        if($promo_code) {
                            $discountOBJ = Discount::where('code', $promo_code)->where('is_private', 1)->pluck('candidates_no')->first();
                            if($discountOBJ) {
                                $candidates_no = $discountOBJ - 1;
                                Discount::where('code', $promo_code)->where('is_private', 1)->update([
                                    'candidates_no' => $candidates_no
                                ]);
                            }
                        }

                        // Need to have $master_id
                        // event(new NewTraineeHasRegisteredEvent($cart));
                        // app('App\Http\Controllers\Front\Education\LMSController')->run($cart);
                        //app('App\Http\Controllers\Front\Education\LMSController')->run($master_id, $cart->id);
                        $zeroPaid = true;
                        //return view(FRONT.'.education.products.register.thanks', compact('zeroPaid'));
                        return redirect()->route('epay.payment.final_thanks', [
                            'status' => 'success'
                        ]);
                    }
                    // return redirect()->route('epay.checkout', ['cart'=>$cart->id]);

                    // if(!is_null($user->password)) {
                    //     Auth::login($user);
                    //     return redirect()->route('education.cart');
                    // }
                    return redirect()->route('epay.cartCheckout', ['cart' => $user->id, 'master_id' => $cartMaster->id??null]);
                }
                else{
                    return back();
                }

                // $course = Course::find($session->trainingOption->course->id);
                // $cart = Cart::find($cart->id);
                // if($cart->session->retarget_discount==1)
                // {
                //     // if($user->id!=1 && $user->id!=5390)
                //     {
                //         RetargetDiscount::DispatchJob($cart->id, 328);
                //     }
                //     // event(new NewPrepareRetargetDiscountRegisterTraineeEvent($cart));
                // }
            }

        //     $exam_price = 0;
        //     if(request()->has('exam_is_included')){
        //         if(request()->exam_is_included==1){
        //             $exam_price = $session->examPrice_by_currency;
        //         }
        //     }
        //     $course = Course::find($session->trainingOption->course->id);
        //     if($course->exam_is_included==1){
        //         request()->exam_is_included = 1;
        //         $exam_price = $session->examPrice_by_currency;
        //     }

        //     $take2_price = 0;
        //     if(request()->has('take2_option') && request()->take2_option==61){
        //         $take2_price = request()->take2_price;
        //     }

        //     $discountOBJ = isset(request()->discount_id) ? Discount::with('type')->find(request()->discount_id) : null;

        //     $discount = 0;
        //     $discount_value = 0;
        //     if(!is_null($discountOBJ)){
        //         $discount = $discountOBJ->value;
        //         $discount_value = $discountOBJ->GetDiscountForProduct($session->price_by_currency, $session->vat, 1);
        //     }

        //     $cart = Cart::HaveRegister($session->id, $user->id);
        //     // if(!is_null($cart) && $cart->status_id==51){
        //     //     Active::Flash(__('education.same_session_registration'), __('education.same_session_registration_msg'), 'danger');
        //     //     return redirect()->route('education.courses');
        //     // }

        //     if(is_null($cart)){

        //         $cart = Cart::create([
        //             'session_id' => $session->id,
        //             'user_id' => $user->id,
        //             'status_id' => 327,//51
        //             'price' => NumberFormat($session->price_by_currency),
        //             'discount_id' => $discountOBJ->id??null,
        //             'discount' => NumberFormat($discount),
        //             'discount_value' => NumberFormat($discount_value),
        //             'exam_price' => NumberFormat($exam_price),
        //             'take2_price' => NumberFormat($take2_price),
        //             'total' => $session->GetSubTotal($discountOBJ, request()->exam_is_included, $take2_price),
        //             'vat' => NumberFormat($session->vat),
        //             'vat_value' => $session->GetVatValue($discountOBJ, request()->exam_is_included, $take2_price),
        //             'total_after_vat' => $session->GetTotalValue($discountOBJ, request()->exam_is_included, $take2_price),
        //             'training_option_id' => $session->training_option_id,//??
        //             'course_id' => $session->trainingOption->course->id,//??
        //             'invoice_number' => date("His").rand(1234, 9632),//??
        //             'trying_count'=>1,
        //             'coin_id'=>Cache::get('coinID_'.request()->ip()),
        //             'coin_price'=>Cache::get('coinPrice_'.request()->ip()),
        //             'registered_at'=>DateTimeNow(),
        //             'locale'=>app()->getLocale(),
        //             // 'coin_price'=>$CurrencyConverterApi->GetContents()??1,
        //         ]);
        //     }
        //     else{

        //         $trying_count = $cart->trying_count + 1;
        //         Cart::where('id', $cart->id)->update([
        //             'price' => NumberFormat($session->price_by_currency),
        //             'discount_id' => $discountOBJ->id??null,
        //             'discount' => NumberFormat($discount),
        //             'discount_value' => NumberFormat($discount_value),
        //             'exam_price' => NumberFormat($exam_price),
        //             'take2_price' => NumberFormat($take2_price),
        //             'total' => $session->GetSubTotal($discountOBJ, request()->exam_is_included, $take2_price),
        //             'vat' => NumberFormat($session->vat),
        //             'vat_value' => $session->GetVatValue($discountOBJ, request()->exam_is_included, $take2_price),
        //             'total_after_vat' => $session->GetTotalValue($discountOBJ, request()->exam_is_included, $take2_price),
        //             'trying_count'=>$trying_count,
        //             'coin_id'=>Cache::get('coinID_'.request()->ip()),
        //             'coin_price'=>Cache::get('coinPrice_'.request()->ip()),
        //             'retarget_email_id'=>328,
        //             'retarget_email_date'=>null,
        //             'registered_at'=>DateTimeNow(),
        //             'locale'=>app()->getLocale(),
        //             // 'coin_id'=>GetCoinId(),
        //             // 'coin_price'=>$CurrencyConverterApi->GetContents()??1,
        //         ]);
        //     }

        //     CartTrace::Create([
        //         'master_id'=>$cart->id,
        //         'status_id'=>327,
        //     ]);

        //     $course = Course::find($session->trainingOption->course->id);
        //     // $session_last = $session;
        //     $cart = Cart::find($cart->id);
        //     if($cart->session->retarget_discount==1)
        //     {
        //         // if($user->id!=1 && $user->id!=5390)
        //         {
        //             RetargetDiscount::DispatchJob($cart->id, 328);
        //         }
        //         // event(new NewPrepareRetargetDiscountRegisterTraineeEvent($cart));
        //     }

        //     if($cart->total==0){

        //         // event(new NewTraineeHasRegisteredEvent($cart));
        //         app('App\Http\Controllers\Front\Education\LMSController')->run($cart);

        //         $zeroPaid = true;
        //         return view(FRONT.'.education.courses.register.thanks', compact('zeroPaid'));
        //     }
        //     return redirect()->route('epay.checkout', ['cart'=>$cart->id]);
        }
        return back();
    }

    public function autofill(){
        if(request()->has('email')){
            $user = User::where('email', request()->email)
                ->select('name', 'gender_id', 'job_title', 'mobile', 'company', 'country_id')
                ->first();
            if($user) {
                return [
                    'name' => $user->trans_name??'',
                    'gender_id' => $user->gender_id??-1,
                    'job_title' => $user->job_title??'',
                    'mobile' => $user->mobile??'',
                    'company' => $user->company??'',
                    'country_id' => $user->country_id??-1,
                ];
            }
            return [
                'name' => '',
                'gender_id' => -1,
                'job_title' => '',
                'mobile' => '',
                'company' => '',
                'country_id' => -1,
            ];
        }
    }

    public function __index_param(){

        $agent = new Agent();
        $partners_count = 9;
        $clients_count = 18;
        if($agent->isPhone()){
            $partners_count = 6;
            $clients_count = 6;
        }
        // $sliders = $this->GetPostSlider('education-slider', 4);
        $sliders = Post::GetSlider('education-slider', 4);
        // dd($sliders);
        $partners = Partner::GetPartners('partners', $partners_count, true, 1, 0);
//        $USPs = Post::where('post_type', 'USP')
        $USPs = Post::GetPost('critical-factor', 4, 'order', 'asc');

        $testimonials = Testimonial::where('post_type', 'education')
            ->with(['userId.upload:uploadable_id,uploadable_type,file,title', 'course:id,short_title'])
            ->whereNotNull('activated_at')
            ->take(2)
            ->orderBy('order')
            ->get();

        $clients = Partner::GetPartners('clients', $clients_count, true, 1, 0);
        $options = Option::where('parent_id', 1)->get();

        $SessionHelper = new SessionHelper();
        $courses = $SessionHelper->Sessions(6);
        // dd($courses);

        return [
            'sliders'=>$sliders,
            'partners'=>$partners,
            'USPs'=>$USPs,
            'testimonials'=>$testimonials,
            'clients'=>$clients,
            'options'=>$options,
            'courses'=>$courses,
            'SessionHelper' => $SessionHelper
        ];
    }

    private function GetPostSlider($post_type, $take, $order_field='posts.post_date', $order='desc'){

        $DateTimeNow = DateTimeNow();

        $GetCountryCode = GetcountryID();
        // if(Cache::has('countryID_'.request()->ip())){
        //     $GetCountryCode = Cache::get('countryID_'.request()->ip());
        // }

        $posts = Post::where('posts.post_type', $post_type)
        ->join('uploads', 'uploads.uploadable_id', 'posts.id')
        ->where('posts.post_date', '<=', $DateTimeNow)
        ->lang('posts.')
        ->take($take)
        ->orderBy($order_field, $order)
        ->select('posts.id', 'posts.title', 'posts.excerpt', 'posts.url', 'uploads.file', 'uploads.title as upload_title')
        ->whereIn('posts.country_id', [$GetCountryCode, -1])
        ->get();
        // dd($posts);

        // $GetCountryCode = 58;
        // if(Cache::has('countryID_'.request()->ip())){
        //     $GetCountryCode = Cache::get('countryID_'.request()->ip());
        // }
        // $posts = Post::where('post_type', $post_type)
        // ->with('upload:uploadable_id,uploadable_type,file,title')
        // ->where('post_date', '<=', Carbon::now()->format('Y-m-d H:i:s'))
        // ->where('date_to', '>=', Carbon::now()->format('Y-m-d H:i:s'))
        // ->orWhereNull('date_to')
        // ->lang()
        // ->take($take)
        // ->orderBy($order_field, $order)
        // ->select('id', 'title', 'excerpt', 'url')
        // ->whereIn('country_id', [$GetCountryCode, -1])
        // ->get();
        return $posts;
    }

    public function promocode() {
        $promocode = request()->PromoCode;
        $session_id = request()->SessionID;

        $session = Session::with('trainingOption')->find($session_id);

        $training_option_id = $session->training_option_id;

        $discount_detail = DiscountDetail::SmartPromocode($promocode, $training_option_id, $session_id);

        if($discount_detail){
            return $discount_detail;
        } else {
            return '';
        }
    }

    public function verify_code() {
        $RetrivedCode = request()->RetrivedCode;
        $Email = request()->Email;

        $user = User::where('email', $Email)->where('retrieved_code', $RetrivedCode)->select('balance')->first();
        if($user) {
            $balance = $user->balance;
            return $balance;
        }
        return '';

    }

    public function quesionSubmit($cart_id){

        $questions = Question::where('master_id', request()->exam_id)->get();

        if(!is_null($questions)){

            // ==== Start Validation ========
            $args_validation = [];
            $messages = [];
            foreach($questions as $question){
                $question_id = $question->id;

                if(!is_null($question->validation)){
                    $args_validation = array_merge($args_validation, [
                        "q_".$question_id => "$question->validation",
                    ]);
                    $messages = array_merge($messages, [
                        "q_"."$question_id.required" => "The $question->trans_question field is required.",
                    ]);
                }
            }
            $validated = Validator::make(request()->all(), $args_validation, $messages);
            if ($validated->fails())
            {
                return $validated;
                // return back()->withInput()->withErrors($validated->errors());
            }
            // ==== End Validation ========

            foreach($questions as $question){

                $question_id = $question->id;
                $req_name = 'q_'.$question_id;
                $actual_name = request()->$req_name;
                if(!is_null($actual_name)){

                    $args = [
                        'cart_id'=>$cart_id,
                        'question_id'=>$question_id,
                        'answer'=>$actual_name,
                    ];

                    Answer::updateOrCreate([
                        'cart_id'=>$cart_id,
                        'question_id'=>$question_id,
                    ],
                        $args
                    );
                    if($question->type == 'file' && request()->has($req_name)){
                        $this->uploadFile($req_name, $question_id, $cart_id, request()->exam_id);
                    }
                    Active::Flash('Success', 'Thanks for your submition', 'success');
                }
            }
        }
        // return back();
    }


    // public function quesionSubmit(Request $request){

    //     if(!Recaptcha::run()) {
    //         return back();
    //     }

    //     $questions = Question::where('master_id', request()->exam_id)->get();

    //     if(!is_null($questions)){

    //         // ==== Start Validation ========
    //         $args_validation = [];
    //         $messages = [];
    //         foreach($questions as $question){
    //             $question_id = $question->id;

    //             if(!is_null($question->validation)){
    //                 $args_validation = array_merge($args_validation, [
    //                     "q_".$question_id => "$question->validation",
    //                 ]);
    //                 $messages = array_merge($messages, [
    //                     "q_"."$question_id.required" => "The $question->trans_question field is required.",
    //                 ]);
    //             }
    //         }

    //         $validated = Validator::make($request->all(), $args_validation, $messages);

    //         if ($validated->fails())
    //         {
    //             return back()->withInput()->withErrors($validated->errors());
    //             // return redirect()->back()->withErrors($validated->errors());
    //         }
    //         // ==== End Validation ========

    //         foreach($questions as $question){

    //             $question_id = $question->id;
    //             $req_name = 'q_'.$question_id;
    //             $actual_name = request()->$req_name;
    //             if(!is_null($actual_name)){

    //                 $args = [
    //                     'cart_id'=>request()->cart_id,
    //                     'question_id'=>$question_id,
    //                     'answer'=>$actual_name,
    //                 ];

    //                 $answer = Answer::updateOrCreate([
    //                     'cart_id'=>request()->cart_id,
    //                     'question_id'=>$question_id,
    //                 ],
    //                     $args
    //                 );
    //                 if($question->type == 'file' && request()->has($req_name)){
    //                     $this->uploadFile($req_name, $question_id, request()->cart_id, request()->exam_id);
    //                 }
    //                 Active::Flash('Success', 'Thanks for your submition', 'success');
    //             }
    //         }
    //     }
    //     return back();
    // }

    private function uploadFile($name='pdf', $question_id, $cart=1, $exam=1){

        $full_name = $name;

        if(request()->has($full_name)){

            $pdf = request()->file($full_name);
            $title = $pdf->getClientOriginalName();

            $fileName = $exam . '-' . $cart . '-'. $question_id . '-' . date('Y-m-d-H-i-s') . '-' . $title;
            $fileName = strtolower($fileName);

            if($pdf->move(public_path('upload/cipd/exams/'), $fileName)){
                $args = [
                    'cart_id'=>$cart,
                    'question_id'=>$question_id,
                    'answer'=>$fileName,
                ];

                Answer::updateOrCreate([
                    'cart_id'=>$cart,
                    'question_id'=>$question_id,
                ],
                    $args
                );
            }

        }
    }

    public function escapeValue($text){
        $text = str_replace("<br>", "<w:br/>", $text);
        $text = str_replace("<br />", "<w:br/>", $text);
        $text = str_replace("<br/>", "<w:br/>", $text);
        $text = str_replace("<p>", "<w:br/>", $text);
        $text = str_replace("</p>", "<w:br/>", $text);
        $text = preg_replace('~[\r\n\t]+~', '', $text);
        $text = str_replace('&', '', $text);
        return $text;
    }

    public function exportCipdToDoc($cart_id)
    {
        $questions = DB::table('questions')
        ->leftJoin('answers', function($join) use($cart_id){
            $join->on('questions.id', 'answers.question_id');
            $join->whereNull('answers.deleted_at');
            $join->where('cart_id', $cart_id);
            $join->Join('carts', function($join_cart){
                $join_cart->on('answers.cart_id', 'carts.id');
                $join_cart->Join('users', function($join_cart){
                    $join_cart->on('users.id', 'carts.user_id');
                });
            });
        })
        ->select('questions.id as question_id','questions.question', 'answers.id', 'answers.cart_id', 'answers.question_id as ans_question_id', 'answers.answer', 'answers.deleted_at', 'users.name', 'users.email', 'users.mobile')
        ->orderBy('question_id')
        ->get();

        // dd($questions);
        if(is_null($questions)){
            Active::Flash(__('education.Warning'), __('education.There is no data to export'),'warning');
            return back();
        }

        // \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(false);
        // \PhpOffice\PhpWord\Settings::setCompatibility(false);

        $template_file = public_path() . '/upload/cipd/waston_cipd.docx';
        if (file_exists($template_file)) {
            $templateProcessor  = new \PhpOffice\PhpWord\TemplateProcessor($template_file);

            foreach($questions as $index => $question){
                $question_id = 'q_'.$question->question_id??0;
                $answer = $question->answer??'';

                if($index == 0){
                    $templateProcessor ->setValue('name', $this->escapeValue(json_decode($question->name)->en??$question->name));
                    $templateProcessor ->setValue('email', $this->escapeValue($question->email??''));
                    $templateProcessor ->setValue('mobile', $this->escapeValue($question->mobile??''));
                }

                if (str_contains($answer, 'q_')) {
                    $answer = 'Yes';
                }

                $templateProcessor ->setValue($question_id, $this->escapeValue($answer));

            }

            // $output_file_docx = $result['name'].' - CIPD Enrollment Form.docx';
            $output_file_docx = $cart_id.' - CIPD Enrollment Form.docx';
            $file_name = public_path() . '/upload/cipd/download/'.$output_file_docx.'_'.date('d-m-Y').'.docx';
            $templateProcessor ->saveAs($file_name);

            return response()->download($file_name);
            // return response()->download($file_name)->deleteFileAfterSend(true);
        }
    }

    public function cipd($cart_id)
    {
        $questions = DB::table('questions')
        ->leftJoin('answers', function($join) use($cart_id){
            $join->on('questions.id', 'answers.question_id');
            $join->whereNull('answers.deleted_at');
            $join->where('cart_id', $cart_id);
            $join->Join('carts', function($join_cart){
                $join_cart->on('answers.cart_id', 'carts.id');
                $join_cart->Join('users', function($join_cart){
                    $join_cart->on('users.id', 'carts.user_id');
                });
            });
        })
        ->select('questions.id as question_id','questions.question', 'answers.id', 'answers.cart_id', 'answers.question_id as ans_question_id', 'answers.answer', 'answers.deleted_at', 'users.name', 'users.email', 'users.mobile', 'carts.course_id','carts.training_option_id','carts.session_id')
        ->orderBy('question_id')
        ->get();

        // dd($questions);
        if(!is_null($questions)){
            return view('training.carts.table-parts.cipd', compact('questions'));
        }

        return back();
    }

}
