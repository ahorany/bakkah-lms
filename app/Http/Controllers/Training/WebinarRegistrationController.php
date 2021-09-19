<?php

namespace App\Http\Controllers\Training;

use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Jobs\CertificateJob;
use App\Jobs\CertificateWebinarJob;
use App\Models\Training\Cart;
use App\Models\Training\WebinarsRegistration;
use Illuminate\Http\Request;
use App\Models\Training\Webinar;
use Illuminate\Database\Eloquent\Builder;

class WebinarRegistrationController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'webinarsRegistrations';
    }

    public function index(){

        $all_webinars = Webinar::get();

        $post_type = GetPostType('webinarsRegistrations');
        $trash = GetTrash();
        $webinarsRegistrations = WebinarsRegistration::whereNotNull('id');

        if(request()->has('webinar_id') && (request()->webinar_id != -1)) {
            $webinarsRegistrations = $webinarsRegistrations->where('webinar_id', request()->webinar_id);
        }

        if(request()->has('user_search') && !is_null(request()->user_search)){
            $webinarsRegistrations = $webinarsRegistrations->whereHas('userId', function (Builder $query) {
                    $query->where('name', 'like', '%'.request()->user_search.'%')
                        ->orWhere('email', 'like', '%'.request()->user_search.'%')
                        ->orWhere('mobile', 'like', '%'.request()->user_search.'%')
                    ;
            });
        }

        if(request()->has('status') && (request()->status != -1) && (request()->status == 'sending')) {
            $webinarsRegistrations = $webinarsRegistrations->whereNotNull('certificate_sent_at');
        }

        if(request()->has('status') && (request()->status != -1) && (request()->status == 'not_sending')) {
            $webinarsRegistrations = $webinarsRegistrations->whereNull('certificate_sent_at');
        }

        // return request();

        $count = $webinarsRegistrations->count();
        $webinarsRegistrations = $webinarsRegistrations->page(100);
        return Active::Index(compact('webinarsRegistrations', 'trash', 'count', 'post_type', 'all_webinars'));
    }

    public function destroy(WebinarsRegistration $WebinarsRegistration){
        WebinarsRegistration::where('id', $WebinarsRegistration->id)->SoftTrash();
    }

    public function restore($WebinarsRegistration){
        WebinarsRegistration::where('id', $WebinarsRegistration)->RestoreFromTrash();
    }

    public function webi_register_certificate() {

        if(request()->has('selectedId')){

            // if(auth()->check()){
                // if(auth()->user()->id==2){

                    // dd(request()->selectedId);
                    // $cart = WebinarsRegistration::with('userId')->findOrFail(request()->selectedId);
                    foreach(request()->selectedId as $selectedId){
                        $cart = WebinarsRegistration::with('userId')->findOrFail($selectedId);
                        // $cart = WebinarsRegistration::with('userId')->findOrFail(1963);
                        dump(trim($cart->userId->email));

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
                    // ============ Start of Data will be in certificate ==================

                        $course_title = $cart->webinar->ar_disclaimer??$cart->webinar->en_title;
                        $data_for_qr = $course_title;
                        if(!is_null($cart->userId->trans_name)){
                            $data_for_qr .= " for"."\n".$cart->userId->trans_name;
                        }
                        $data_for_qr .= "\n"."www.bakkah.com";
                    // ============ End of Data will be in certificate ==================
                    // ============ Start of generate the certificate and save it as a file ==================
                        ob_start();
                            $body = view('training.certificates.certificate.webinar-content', compact('cart', 'data_for_qr'))->render();
                            try{
                                $mpdf->WriteHTML($body);
                            }catch(\Mpdf\MpdfException $e){
                                die($e->getMessage());
                            }
                        ob_end_clean();

                        $cert_no = !is_null($cart->id)?$cart->id:'';
                        $file_name_pdf = $cert_no.'_'.$cart->userId->en_name??'';
                        // dump($file_name_pdf);
                        // $file_name_pdf = $this->GetCertFileName($cart);
                        $file_name = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
                        $is_created = $mpdf->Output($file_name,'F');
                        // dump($file_name);
                        // dump($is_created);
                        $file = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
                        if (file_exists($file)) {

                            if(!is_null($cart->userId->email)){

                                // $when = now()->addSeconds(1);
                                Mail::to($cart->userId->email)->send(new CertificateEmail($cart, "Completion Certificate", $file_name_pdf, 'certificate'));
                                // Mail::to($cart->userId->email)
                                // ->send(new CertificateEmail($cart, null, $file_name_pdf, 'certificate'));

                                WebinarsRegistration::where('id', $cart->id)->update([
                                    'certificate_sent_at'=>now(),
                                ]);
                                // dd('Sent Successfully....');
                                Active::Flash("Certification Sent Successfully", __('flash.empty'), 'success');
                            }
                            // readfile($file);
                            // exit;
                        }
                    }

                // }
            // }

            $job = (new CertificateWebinarJob(request()->selectedId))
                ->delay(\Carbon\Carbon::now()->addSeconds(2));
            dispatch($job);
            Active::Flash("Certification Sent Successfully", __('flash.empty'), 'success');
        }
        return back();
        // dd(request()->all());
        // dd('test');
        // // ============ Start of generate certification pdf function ==================
        // // https://github.com/mpdf/mpdf
        // // https://mpdf.github.io/css-stylesheets/supported-css.html
        // // ============ Start of PDF sesstings ==================
        //     $mpdf = new \Mpdf\Mpdf([
        //         'margin_left' => 0,
        //         'margin_right' => 0,
        //         'margin_top' => 0,
        //         'margin_bottom' => 0,
        //         'margin_header' => 0,
        //         'margin_footer' => 0,
        //         'default-font' => 'Lato',
        //         'orientation' => 'L',
        //     ]);

        //     $mpdf->SetProtection(array('print'));
        //     $mpdf->SetTitle("Certificate");
        //     $mpdf->SetAuthor(__('education.app_title'));
        //     $mpdf->SetDisplayMode('fullpage');
        //     $mpdf->SetFont('lato');
        //     $mpdf->autoScriptToLang = true;
        //     $mpdf->baseScript = 1;
        //     $mpdf->autoVietnamese = true;
        //     $mpdf->autoArabic = true;
        //     $mpdf->autoLangToFont = true;
        //     // $mpdf->SetDirectionality('rtl');
        //     // $mpdf->SetWatermarkText("Paid");
        //     // $mpdf->showWatermarkText = true;
        //     // $mpdf->watermark_font = 'Lato';
        //     // $mpdf->watermarkTextAlpha = 0.1;
        //     // $mpdf->setAutoTopMargin = 'stretch';
        // // ============ End of PDF sesstings ==================

        // // ============ Start of Data will be in certificate ==================
        //     $cart = WebinarsRegistration::findOrFail($id);

        //     // $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
        //     $course_title = $cart->webinar->ar_disclaimer??$cart->webinar->en_title;
        //     $data_for_qr = $course_title;

        //     // if($cart->webinar->PDUs!=0){
        //     //     $data_for_qr .= "\n"."With ".$cart->webinar->PDUs." PDUs";
        //     // }
        //     if(!is_null($cart->userId->trans_name)){
        //         $data_for_qr .= " for"."\n".$cart->userId->trans_name;
        //     }
        //     $data_for_qr .= "\n"."www.bakkah.com";
        // // ============ End of Data will be in certificate ==================

        // // ============ Start of generate the certificate and save it as a file ==================
        //     ob_start();
        //         $body = view('training.certificates.certificate.webinar-content', compact('cart', 'data_for_qr'))->render();
        //         try{
        //             $mpdf->WriteHTML($body);
        //         }catch(\Mpdf\MpdfException $e){
        //             die($e->getMessage());
        //         }
        //     ob_end_clean();
        //     // $mpdf->Output();
        //     // $file_name_pdf = $cert_no.'_'.$cart->userId->trans_name;
        //     $file_name_pdf = $this->GetCertFileName($cart);
        //     $file_name = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
        //     $mpdf->Output($file_name,'F');
        //     // $mpdf->WriteHTML(utf8_encode($html));
        // // ============ End of generate certification pdf function ==================
        // // return 'success';
        // return view('training.certificates.certificate.webinar-index', compact('cart', 'data_for_qr','file_name_pdf'));
    }

    // public function certificate_pdf($id) {

    //     $job = (new CertificateWebinarJob($id))
    //                 ->delay(\Carbon\Carbon::now()->addSeconds(5));
    //     dispatch($job);

    //     Active::Flash("Registeration Sent Successfully", __('flash.empty'), 'success');
    //     // $cart = Cart::findOrFail($id);
    //     // $FileName = $this->GetCertFileName($cart);

    //     // ConvertApi::setApiSecret('KmKCe223BpWtWQFC');
    //     // $result = ConvertApi::convert('pdf', [
    //     //     'Url' => route('certificates.certificate.url', ['id'=>$id]),
    //     //     'FileName' => $FileName,
    //     //     'PageOrientation' => 'landscape',
    //     //     'PageSize' => 'a4',
    //     //     'MarginTop' => '0',
    //     //     'MarginRight' => '0',
    //     //     'MarginBottom' => '0',
    //     //     'MarginLeft' => '0',
    //     //     ], 'web'
    //     // );

    //     // $file = $result->saveFiles(public_path() . '/certificates/certificate');
    //     // $file = $file[0];
    //     // if (file_exists($file)) {
    //     //     // header('Content-Description: File Transfer');
    //     //     // header('Content-Type: application/octet-stream');
    //     //     // header('Content-Disposition: attachment; filename="'.basename($file).'"');
    //     //     // header('Expires: 0');
    //     //     // header('Cache-Control: must-revalidate');
    //     //     // header('Pragma: public');
    //     //     // header('Content-Length: ' . filesize($file));

    //     //     if(!is_null($cart->userId->email)){
    //     //         $course_title = $cart->trainingOption->training_name??null;
    //     //         $subject = $course_title.' ('.$cart->session->certificate_from.')(CERTIFICATE)';
    //     //         Mail::to($cart->userId->email)
    //     //             // ->cc(['dabukarsh@bakkah.net.sa', 'malashqar@bakkah.net.sa', 'yreyala@bakkah.net.sa'])
    //     //             ->send(new CertificateEmail($cart, $subject, $FileName, 'certificate'));
    //     //             Cart::where('id', $id)->update([
    //     //                 'certificate_sent_at'=>now(),
    //     //             ]);
    //     //     }
    //     //     // readfile($file);
    //     //     // exit;
    //     // }
    //     return back();
    // }

    // private function GetCertFileName($cart){
    //     $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
    //     return $cert_no.'_'.$cart->userId->en_name;
    // }

}
