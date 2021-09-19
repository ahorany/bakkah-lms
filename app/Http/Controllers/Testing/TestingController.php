<?php

namespace App\Http\Controllers\Testing;

use App\Jobs\WebinarJob;
use App\Jobs\InterestJob;
use App\Mail\MessageEmail;
use App\Mail\WebinarEmail;
use App\Models\Admin\Post;
use App\Mail\InterestEmail;
use App\Mail\InvoiceMaster;
use App\Mail\RetargetEmail;
use Illuminate\Http\Request;
use App\Models\Training\Cart;
use App\Jobs\InterestEmailJob;
use App\Models\Training\Payment;
use App\Models\Training\Session;
use App\Models\Training\Question;
use App\Jobs\Retarget\DiscountJob;
use App\Mail\WebinarEmailRetarget;
use Illuminate\Support\Facades\DB;
use App\Models\Training\CartMaster;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Helpers\RedirectRegisterPath;
use Illuminate\Support\Facades\Cookie;
use App\Models\Training\CourseInterest;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Training\WebinarsRegistration;
use App\Helpers\Models\Training\SessionHelper;
use App\Mail\RetargetEmail\SingleRetarget;
use App\Models\Training\Discount\DiscountDetail;
use App\Models\Training\Discount\RetargetDiscount;
use App\User;
use Illuminate\Database\Query\Builder as QueryBuilder;
use App\Models\Training\Course;

class TestingController extends Controller
{
    public function index() {

        dump('start');

            $carts = Cart::where('payment_status',68)
            ->where('user_id','!=',null)
            ->whereHas('course')
            ->whereHas('userId')
            ->with('userId', 'course')
            ->take(100)
            ->get();
            // dd($carts);

            $folder = null;
            foreach($carts as $cart){
                dump($cart);
                if(isset($cart->course) && isset($cart->userId)){

                    if($cart->course->certificate_type_id == 324){
                        $folder = 'certificate';
                    }elseif($cart->course->certificate_type_id == 325){
                        $folder = 'attendance';
                    }
                    dump($cart->course->certificate_type_id);

                    if($folder){

                        $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
                        $file_name_pdf = $cert_no.'_'.$cart->userId->en_name;
                        $file = public_path("certificates/$folder/").$file_name_pdf.'.pdf';

                        dump($file);
                        if (file_exists($file)) {
                            $path = $folder.'/'.$file_name_pdf.'.pdf';
                            // $path = $folder.'/'.$cart->certificate_file;
                            dump($cart->id, $path);

                            // Cart::where('id', $cart->id)->update([
                            //     'certificate_file'=>$path,
                            // ]);
                        }

                    }

                }

            }

        dd('Done');

        // dd('test');

        // $carts = Cart::join('users', 'users.id', 'carts.user_id')
        // ->join('users_mail', 'users_mail.mail', 'users.email')
        // ->where('carts.session_id', 1325)
        // ->whereIn('users_mail.id', [244, 245])//, 244
        // ->with(['userId', 'trainingOption', 'session', 'course'])
        // ->select('carts.*', 'users.email', 'users.name')
        // ->get();

        // dump($carts);
        // $seconds = 15;
        // $i = 1;
        // foreach($carts as $cart){
        //     dump($i++);
        //     // $when = now()->addSeconds($seconds);
        //     dump($cart->id);
        //     $id = $cart->id;
        //     $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
        //     $file_name_pdf = $cert_no.'_'.$cart->userId->en_name;
        //     // dd($file_name_pdf);

        //     // $file = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';

        //     // if(!file_exists($file)){
        //         $mpdf = new \Mpdf\Mpdf([
        //             'margin_left' => 0,
        //             'margin_right' => 0,
        //             'margin_top' => 0,
        //             'margin_bottom' => 0,
        //             'margin_header' => 0,
        //             'margin_footer' => 0,
        //             'default-font' => 'Lato',
        //             'orientation' => 'L',
        //         ]);

        //         $mpdf->SetProtection(array('print'));
        //         $mpdf->SetTitle("Certificate");
        //         $mpdf->SetAuthor(__('education.app_title'));
        //         $mpdf->SetDisplayMode('fullpage');
        //         $mpdf->SetFont('lato');
        //         $mpdf->autoScriptToLang = true;
        //         $mpdf->baseScript = 1;
        //         $mpdf->autoVietnamese = true;
        //         $mpdf->autoArabic = true;
        //         $mpdf->autoLangToFont = true;

        //         // ============ Start of Data will be in certificate ==================
        //         $cart = Cart::findOrFail($id);

        //         // $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
        //         $course_title = $cart->course->ar_disclaimer??$cart->course->en_title;
        //         $data_for_qr = $course_title;

        //         if($cart->course->PDUs!=0){
        //             $data_for_qr .= "\n"."With ".$cart->course->PDUs." PDUs";
        //         }
        //         if(!is_null($cart->userId->trans_name)){
        //             $data_for_qr .= " for"."\n".$cart->userId->trans_name;
        //         }
        //         $data_for_qr .= "\n"."www.bakkah.com";
        //         // ============ End of Data will be in certificate ==================

        //         // ============ Start of generate the certificate and save it as a file ==================
        //         ob_start();
        //         $body = view('training.certificates.certificate.content', compact('cart', 'data_for_qr'))->render();
        //         try{
        //             $mpdf->WriteHTML($body);
        //         }catch(\Mpdf\MpdfException $e){
        //             die($e->getMessage());
        //         }
        //         ob_end_clean();

        //         // $mpdf->Output();
        //         // $file_name_pdf = $cert_no.'_'.$cart->userId->trans_name;
        //         $file_name_pdf = $this->GetCertFileName($cart);
        //         $file_name = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
        //         // ob_clean();
        //         $mpdf->Output($file_name, 'F');
        //     // }

        //     // $file = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';

        //     if (file_exists($file_name)) {
        //         dump($file_name);
        //         if(!is_null($cart->userId->email)){
        //             $course_title = $cart->trainingOption->training_name??null;
        //             dump($course_title);
        //             // $subject = $course_title.' ('.$cart->session->certificate_from.')(CERTIFICATE)';
        //             // Mail::to($cart->userId->email)
        //             // ->send(new CertificateEmail($cart, $subject, $file_name_pdf, 'certificate'));

        //             $job = (new CertificateJob($cart->id))
        //             ->delay(\Carbon\Carbon::now()->addSeconds(5));
        //             dispatch($job);
        //             $seconds += 15;

        //             Cart::where('id', $id)->update([
        //                 'certificate_sent_at'=>now(),
        //             ]);
        //         }
        //         // readfile($file);
        //         // exit;
        //     }
        //     // $job = (new CertificateJob($cart->id))
        //     //         ->delay(\Carbon\Carbon::now()->addSeconds(5));
        //     // dispatch($job);
        //     // $seconds += 15;
        // }
        // dd('finish');

        // $SessionHelper = new SessionHelper(334);
        // $session = $SessionHelper->TrainingOption()->where('session_id', 1326)->first();
        // dd($session);
        // dd('Done');
        // // $cart = Cart::find(14486);
        // $cart = Cart::with(['course', 'trainingOption.constant', 'userId'])->find(14490);

        // dd('test');
        // // dump($cart);
        // // $retargetDiscount = RetargetDiscount::where('current_retarget_email_id', $cart->retarget_email_id??361)
        // // ->where('has_discount', 0)
        // // ->first();

        // // dump($cart);
        // // dump($retargetDiscount);
        // // $GetRetarget = RetargetDiscount::GetRetarget($cart, $retargetDiscount, null);
        // // dd($GetRetarget);
        // // Mail::to($cart->userId->email)
        // // ->send(new SingleRetarget($cart, $retargetDiscount));
        // // dd('Test');

        // /*
        // $discountDetail = DiscountDetail::where('course_id', $cart->course_id)
        // ->whereNull('session_id')
        // ->whereNotNull('training_option_id')
        // ->with(['discount'=>function($query){
        //     $query->where('post_type', 'retarget_discounts')
        //     ->where('type_id', 362);
        // }])->first();
        // dd($discountDetail);*/
        // /*
        // $retargetDiscount = RetargetDiscount::where('current_retarget_email_id', 362)->first();
        // dump($retargetDiscount);
        // $GetRetarget = RetargetDiscount::GetRetarget($cart, $retargetDiscount, null);
        // dd($GetRetarget);*/

        // // dump($cart);
        // // $retargetDiscount = RetargetDiscount::where('current_retarget_email_id', 361)->first();
        // // $course_name = $cart->course->trans_title;
        // // dd($course_name);
        // // dd(str_replace('${course_name}',"Peter",$retargetDiscount->trans_title));
        // // dd($retargetDiscount->trans_title);
        // /*
        // dump($cart->id);
        // dump($cart);
        // $Redirect = new RedirectRegisterPath($cart->id);
        // $getFunction = $Redirect->getFunction();
        // $Redirect->$getFunction();
        // */
        // // dd($Redirect->$getFunction());
        // // dump($cart);
        // RetargetDiscount::DispatchJob($cart->id, 361);
        // dd('Test');
        // // Mail::to('m.naji1512@gmail.com')->send(new RetargetEmail($cart, $cart->retarget_email_id));


        // $this->RetargetEmail();
        // // $this->WebinarsEmailRetarget();
        // dd('WebinarsEmailRetarget');
        // // ALTER TABLE `questions` CHANGE `question` `title` VARCHAR(700) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
        // $exam_id = 1;
        // $cart_id = 3;

        // $questions = Question::where('master_id', $exam_id)
        // ->with(['exams'])
        // ->orderby('order')
        // ->get();
        // // dd($questions);

        // // foreach($questions as $question){
        // //     // dump($WebinarsRegistration->userId->email);
        // //     dump($question->trans_question);
        // // }
        // return view(FRONT.'.education'.'.products.register.questions', compact('exam_id', 'cart_id', 'questions'));

        // // Mail::to('hsalah@bakkah.net.sa')->send(new InvoiceMaster(4));
        // // // Mail::to($user->email)
        // // //     ->cc(['accounting@bakkah.net.sa'])
        // // //     ->send(new PaymentReceiptMaster($master_id));//, 'mkassem@bakkah.net.sa'

        // dd('zzzzzz');


        // // $this->GenerateCartMastersNOTNULL();
        // dd('zzzzzz');
        // echo "start";
        // // $carts = Cart::whereNotNull('id')
        // //     ->with(['course:id,title', 'session'=>function($query){
        // //         $query->withTrashed();
        // //     }]);
        // // $cartMasters = CartMaster::whereNull('xero_invoice')
        // // ->whereNull('xero_invoice_created_at')
        // // ->whereHas('payment', function (Builder $query){
        // //     $query->where('payment_status', 68);
        // // })
        // // ->with('carts.session.trainingOption.course', 'payment')->get();

        // $cartMasters = CartMaster::whereNull('xero_invoice')
        // ->whereNull('xero_invoice_created_at')
        // ->whereHas('payment', function (Builder $query){
        //     $query->where('payment_status', 68);
        // })
        // ->with(['payment', 'carts'=>function($query){
        //     $query->withTrashed()
        //     ->with(['trainingOption', 'course'=>function($query){
        //         // $query->withTrashed();
        //     }, 'session'=>function($query){
        //         // $query->withTrashed();
        //     }, 'cartFeatures.feature'=>function($query){
        //         // $query->withTrashed();
        //     }]);
        // }])
        // // ->whereNotIn('id', [4300,4327])
        // ->orderBy('id', 'asc')
        // // ->where('id', 1901)
        // // ->take(2)
        // ->get();

        // // dd($cartMasters);

        // // ->with(['userId', 'carts.userId', 'carts.cartFeatures.feature', 'carts.course', 'carts.session'])

        // foreach($cartMasters as $cartMaster){
        //     echo $cartMaster->user_id.' aa = '.$cartMaster->id.'<br>';
        //     foreach($cartMaster->carts as $cart){
        //         dump($cart->course->en_title??null);
        //         dump($cart->trainingOption->course->xero_code??null);
        //         // dump(date_format(date_create($cart->session->date_from), 'Y-m-d'));
        //         // Here add new row in xero
        //         foreach($cart->cartFeatures as $cartFeature){
        //             dump($cartFeature->feature->title);
        //             dump($cartFeature->cart->trainingOption->ExamSimulation->course->xero_code??null);
        //             // dump($cartFeature->cart->trainingOption->ExamSimulation->id??null);
        //             // Here add new row in xero
        //         }
        //         // dump($cart->id.'====>'.$cart->course->xero_code.'==>'.$cart->course->xero_exam_code);
        //     }
        // }
        //     // dd($carts);

        // //$this->InterestingEmail();
        // // $this->WebinarsEmail();

        // // $this->generate_certificate_pdf();
        // // $this->generate_letter_of_attendance_pdf();
    }

    public function RetargetEmail()
    {
        // $carts = DB::select("select distinct users.email
        // from carts
        // inner join users on users.id = carts.user_id
        // where carts.deleted_at is null
        // and carts.payment_status=63
        // and date(carts.registered_at) >= '2021-01-01'");
        $carts = DB::select("select distinct users.email
        from course_interests
        inner join users on users.id = course_interests.user_id
        where course_interests.deleted_at is null
        and users.email not in(
            select distinct users.email
            from carts
            inner join users on users.id = carts.user_id
            where carts.deleted_at is null
            and carts.payment_status=63
            and date(carts.registered_at) >= '2021-01-01'
        )");
        // dd($carts);
        $seconds = 3;
        foreach($carts as $cart){

            $when = now()->addSeconds($seconds);
            Mail::to($cart->email)->later($when, new MessageEmail());
            // Mail::to($cart->email)->later($when, new WebinarEmailRetarget($WebinarsRegistration));
            $seconds += 2;
        }
        dd("Done");
    }

    public function InterestingEmail()
    {
        $CourseInterests = CourseInterest::where('course_id', 18)
        // ->where('id', 257)
        ->with(['userId', 'course'])
        ->get();
        // dd($CourseInterests);
        foreach($CourseInterests as $CourseInterest){
            // dump($WebinarsRegistration->userId->email);
            // dump($CourseInterest->userId->email);
            Mail::to($CourseInterest->userId->email)->send(new InterestEmail($CourseInterest));
        }
        // $job = (new InterestEmailJob(1))
        //             ->delay(\Carbon\Carbon::now()->addSeconds(5));
        // dispatch($job);
        dd("Done");
    }

    public function WebinarsEmailRetarget()
    {
        $WebinarsRegistrations = WebinarsRegistration::where('webinar_id', 8)
        ->where('id', '=', 924)
        // ->where('id', '<=', 26)
        ->with(['userId', 'webinar'])
        ->get();
        dd($WebinarsRegistrations);

        $seconds = 5;
        foreach($WebinarsRegistrations as $WebinarsRegistration){

            $when = now()->addSeconds($seconds);
            // dump($WebinarsRegistration->userId->email);
            Mail::to($WebinarsRegistration->userId->email)->later($when, new WebinarEmailRetarget($WebinarsRegistration));
            $seconds += 10;
        }
        dd('Done.');
        // // LastChanceRetargetEmailJob
    }

    public function WebinarsEmail()
    {
        // // dd("Start");
        $webinar_id = 8; // 7 on live
        $job = (new WebinarJob($webinar_id))
                    ->delay(\Carbon\Carbon::now()->addSeconds(3));

        // $job = (new WebinarJob($webinar_id))
        //             ->delay(\Carbon\Carbon::now()->addMinutes(180));

        dispatch($job);

        // Active::Flash("Certificate Sent Successfully", __('flash.empty'), 'success');

        dd("Done");

        // $WebinarsRegistrations = WebinarsRegistration::where('webinar_id', 7)
        // ->where('id', '>=', 25)
        // ->where('id', '<=', 26)
        // ->with(['userId', 'webinar'])
        // ->get();
        // // dd($WebinarsRegistrations);

        // $seconds = 1;
        // foreach($WebinarsRegistrations as $WebinarsRegistration){

        //     // dump($WebinarsRegistration->userId->email);

        //     Mail::to($WebinarsRegistration->userId->email)->send(new WebinarEmail($WebinarsRegistration));

        //     $seconds += 2;
        // }
        // dd('Done.');
        // // LastChanceRetargetEmailJob
    }

    public function algolia()
    {
        // $posts = Post::where('locale', app()->getLocale())->search('10')->get();
        // $posts = Post::search('10')->where('created_by', '!=', 1)->get();
        //$posts = Post::search('pmp')->get();
        // dd($posts);
        // $posts = Post::where('locale', 'en');
        // dd($posts);

        return view('testing.algolia');
    }

    // ============ Start of generate certification pdf function ==================
        public function generate_certificate_pdf(){
            // https://github.com/mpdf/mpdf
            // https://mpdf.github.io/css-stylesheets/supported-css.html

            // ============ Start of PDF sesstings ==================
                $mpdf = new \Mpdf\Mpdf([
                    'margin_left' => 0,
                    'margin_right' => 0,
                    'margin_top' => 0,
                    'margin_bottom' => 0,
                    'margin_header' => 0,
                    'margin_footer' => 0,
                    'default-font' => 'Lato',
                    'orientation' => 'L',
                ]);

                $mpdf->SetProtection(array('print'));
                $mpdf->SetTitle("Certificate");
                $mpdf->SetAuthor(__('education.app_title'));
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->SetFont('lato');
                $mpdf->autoScriptToLang = true;
                $mpdf->baseScript = 1;
                $mpdf->autoVietnamese = true;
                $mpdf->autoArabic = true;
                $mpdf->autoLangToFont = true;
                // $mpdf->SetDirectionality('rtl');

                // $mpdf->SetWatermarkText("Paid");
                // $mpdf->showWatermarkText = true;
                // $mpdf->watermark_font = 'Lato';
                // $mpdf->watermarkTextAlpha = 0.1;
                // $mpdf->setAutoTopMargin = 'stretch';
            // ============ End of PDF sesstings ==================

            // ============ Start of Data will be in certificate ==================
                $cart = Cart::findOrFail(3222);

                $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
                $course_title = $cart->course->ar_disclaimer??$cart->course->en_title;
                $data_for_qr = $course_title;

                if($cart->course->PDUs!=0){
                    $data_for_qr .= "\n"."With ".$cart->course->PDUs." PDUs";
                }
                if(!is_null($cart->userId->trans_name)){
                    $data_for_qr .= " for"."\n".$cart->userId->trans_name;
                }
                $data_for_qr .= "\n"."www.bakkah.com";
            // ============ End of Data will be in certificate ==================

            // ============ Start of generate the certificate and save it as a file ==================
                ob_start();
                    $body = view('training.certificates.certificate.content', compact('cart', 'data_for_qr'))->render();
                    try{
                        $mpdf->WriteHTML($body);
                    }catch(\Mpdf\MpdfException $e){
                        die($e->getMessage());
                    }
                ob_end_clean();

                // $mpdf->Output();
                $file_name = $cert_no.'_'.$cart->userId->trans_name;
                $file_name = public_path() . '/certificates/certificate/'.$file_name.'.pdf';
                $mpdf->Output($file_name,'F');
                // $mpdf->WriteHTML(utf8_encode($html));
            // ============ End of generate the certificate and save it as a file ==================

            exit();
        }
    // ============ End of generate certification pdf function ==================

    // ============ Start of generate letter of attendance pdf function ==================
        public function generate_letter_of_attendance_pdf(){

            // ============ Start of PDF sesstings ==================
                $mpdf = new \Mpdf\Mpdf([
                    'margin_left' => 0,
                    'margin_right' => 0,
                    'margin_top' => 0,
                    'margin_bottom' => 0,
                    'margin_header' => 0,
                    'margin_footer' => 0,
                    'default-font' => 'Lato',
                    'orientation' => 'P',
                ]);

                $mpdf->SetProtection(array('print'));
                $mpdf->SetTitle("Letter Of Attendance");
                $mpdf->SetAuthor("Bakkah Inc.");
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->SetFont('lato');
                $mpdf->autoScriptToLang = true;
                $mpdf->baseScript = 1;
                $mpdf->autoVietnamese = true;
                $mpdf->autoArabic = true;
                $mpdf->autoLangToFont = true;
                // $mpdf->SetDirectionality('rtl');

                // $mpdf->SetWatermarkText("Paid");
                // $mpdf->showWatermarkText = true;
                // $mpdf->watermark_font = 'Lato';
                // $mpdf->watermarkTextAlpha = 0.1;
                // $mpdf->setAutoTopMargin = 'stretch';
            // ============ End of PDF sesstings ==================

            // ============ Start of Data will be in certificate ==================
                $cart = Cart::findOrFail(3220);

                $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
            // ============ End of Data will be in certificate ==================

            // ============ Start of generate the certificate and save it as a file ==================
                ob_start();
                    $body = view('training.certificates.attendance.content', compact('cart'))->render();
                    try{
                        $mpdf->WriteHTML($body);
                    }catch(\Mpdf\MpdfException $e){
                        die($e->getMessage());
                    }
                ob_end_clean();

                // $mpdf->Output();
                $file_name = $cert_no.'_'.$cart->userId->trans_name;
                $file_name = public_path() . '/certificates/attendance/'.$file_name.'.pdf';
                $mpdf->Output($file_name,'F');
                // $mpdf->WriteHTML(utf8_encode($html));
            // ============ End of generate the certificate and save it as a file ==================

            exit();
        }
    // ============ End of generate letter of attendance pdf function ==================

    public function retarget_discount($cart_id)
    {
        $cart = Cart::find($cart_id);
        // dump($cart);
        // Mail::to("abed_348@hotmail.com")->send(new \App\Mail\Invoice($cart));
        if(!is_null($cart))
        {
            $Redirect = new RedirectRegisterPath($cart->id);
            $getFunction = $Redirect->getFunction();
            dd($getFunction);

            // $job = (new DiscountJob($cart_id));
            //     // ->delay(\Carbon\Carbon::now()->addMinutes($minute));
            // dispatch($job);

            // dd($getFunction);
            // if($getFunction == 'checkout'){
            //     $transaction_id = $this->prepare_the_checkout($cart);
            //     return view(FRONT.'.education.products.checkout', compact('transaction_id', 'cart'));
            // }
            dd('Test');
        }
    }

    public function redirect_to_dontcom(Request $request){

        // dump(env('APP_URL'));
        // dump($request->fullUrl());
        $old_key = '127';
        $old_url = 'http://127.0.0.1:8000';
        $new_url = 'http://localhost:8000';
        $old_path = strpos($request->fullUrl(), $old_key, 1);
        if($old_path!=false)
        {
            $searchFor = ['learning', 'sessions', 'hot-deals', 'knowledge-center', 'redirect_to_dontcom'];
            // dump($this->strposa($request->fullUrl(), $searchFor, 1));
            if ($this->strposa($request->fullUrl(), $searchFor, 1)) {

                $res = strpos($request->fullUrl(), 'learning', 1);
                if($res!=false){
                    $new_path = $new_url;
                }
                else{
                    $new_path = str_replace($old_url, $new_url, $request->fullUrl());
                }
                return redirect()->away($new_path, 301);
            }
            else
            {
                echo 'false';
            }
            dd('Done');
        }
        dd('Test');
    }

    // https://stackoverflow.com/questions/6284553/using-an-array-as-needles-in-strpos
    private function strposa($haystack, $needles=array(), $offset=0) {
        $current_needle=false;
        foreach($needles as $needle) {
            $res = strpos($haystack, $needle, $offset);
            if ($res !== false) {
                $current_needle = $needle;
            }
        }
        return $current_needle;
    }
}
