<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Cart;
use Illuminate\Support\Facades\Mail;
use App\Mail\CertificateEmail;
// use PDF;
// use TCPDF_FONTS;

require __DIR__ . '/tcpdf/tcpdf_barcodes_2d.php';
use App\Http\Controllers\Training\tcpdf\TCPDF2DBarcode;
use App\Jobs\CertificateJob;
use App\Jobs\LetterOfAttendanceJob;

// require __DIR__ . '/vendor/autoload.php';
// use \ConvertApi\ConvertApi;
use App\Helpers\Active;

class CertificateController extends Controller
{
    public function certificate_userProfile($id) {

        $body = $this->certificate_body($id);
        return view('userprofile::users.certifications_preview', [
            'cart'=>$body['cart'],
            'data_for_qr'=>$body['data_for_qr'],
            'file_name_pdf'=>$body['file_name_pdf'],
        ]);
    }

    public function certificate($id) {

        $body = $this->certificate_body($id);
        return view('training.certificates.certificate.index', [
            'cart'=>$body['cart'],
            'data_for_qr'=>$body['data_for_qr'],
            'file_name_pdf'=>$body['file_name_pdf'],
        ]);
    }

    public function certificate_body($id) {

        // $cart = Cart::findOrFail($id);
        // $qr_image = $this->DrawBarcode($cart);
        // return view('training.certificates.certificate.index', compact('cart', 'qr_image'));

            // ============ Start of generate certification pdf function ==================
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
                    $cart = Cart::findOrFail($id);

                    // $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
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
                    // $file_name_pdf = $cert_no.'_'.$cart->userId->trans_name;
                    $file_name_pdf = $this->GetCertFileName($cart);
                    $file_name = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
                    $mpdf->Output($file_name,'F');
                    // $mpdf->WriteHTML(utf8_encode($html));
                // ============ End of generate the certificate and save it as a file ==================

                    // $show_pdf = env('APP_URL') . 'certificates/certificate/'.$file_name_pdf.'.pdf';
                    // var_dump($show_pdf);

        // ============ End of generate certification pdf function ==================
        return compact('cart', 'data_for_qr', 'file_name_pdf');
        // return view('training.certificates.certificate.index', compact('cart', 'data_for_qr','file_name_pdf'));
    }

    public function certificate_url($id) {

        $cart = Cart::findOrFail($id);
        $qr_image = $this->DrawBarcode($cart);

        return view('training.certificates.certificate.pdf', compact('cart', 'qr_image'));
    }

    public function certificate_pdf($id) {

        $job = (new CertificateJob($id))
                    ->delay(\Carbon\Carbon::now()->addSeconds(5));
        dispatch($job);

        Active::Flash("Certificate Sent Successfully", __('flash.empty'), 'success');
        // $cart = Cart::findOrFail($id);
        // $FileName = $this->GetCertFileName($cart);

        // ConvertApi::setApiSecret('KmKCe223BpWtWQFC');
        // $result = ConvertApi::convert('pdf', [
        //     'Url' => route('certificates.certificate.url', ['id'=>$id]),
        //     'FileName' => $FileName,
        //     'PageOrientation' => 'landscape',
        //     'PageSize' => 'a4',
        //     'MarginTop' => '0',
        //     'MarginRight' => '0',
        //     'MarginBottom' => '0',
        //     'MarginLeft' => '0',
        //     ], 'web'
        // );

        // $file = $result->saveFiles(public_path() . '/certificates/certificate');
        // $file = $file[0];
        // if (file_exists($file)) {
        //     // header('Content-Description: File Transfer');
        //     // header('Content-Type: application/octet-stream');
        //     // header('Content-Disposition: attachment; filename="'.basename($file).'"');
        //     // header('Expires: 0');
        //     // header('Cache-Control: must-revalidate');
        //     // header('Pragma: public');
        //     // header('Content-Length: ' . filesize($file));

        //     if(!is_null($cart->userId->email)){
        //         $course_title = $cart->trainingOption->training_name??null;
        //         $subject = $course_title.' ('.$cart->session->certificate_from.')(CERTIFICATE)';
        //         Mail::to($cart->userId->email)
        //             // ->cc(['dabukarsh@bakkah.net.sa', 'malashqar@bakkah.net.sa', 'yreyala@bakkah.net.sa'])
        //             ->send(new CertificateEmail($cart, $subject, $FileName, 'certificate'));
        //             Cart::where('id', $id)->update([
        //                 'certificate_sent_at'=>now(),
        //             ]);
        //     }
        //     // readfile($file);
        //     // exit;
        // }
        return back();
    }


    public function attendance($id) {

        // $cart = Cart::findOrFail($id);
        // $qr_image = $this->DrawBarcode($cart);
        // return view('training.certificates.attendance.index', compact('cart', 'qr_image'));

            // ============ Start of generate attendance pdf function ==================
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
                    'orientation' => 'P',
                ]);

                $mpdf->SetProtection(array('print'));
                $mpdf->SetTitle("Letter Of Attendance");
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

            // ============ Start of Data will be in attendance ==================
                $cart = Cart::findOrFail($id);
                // $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
            // ============ End of Data will be in attendance ==================

            // ============ Start of generate the attendance and save it as a file ==================
                ob_start();
                    $body = view('training.certificates.attendance.content', compact('cart'))->render();
                    try{
                        $mpdf->WriteHTML($body);
                    }catch(\Mpdf\MpdfException $e){
                        die($e->getMessage());
                    }
                ob_end_clean();

                // $mpdf->Output();
                // $file_name_pdf = $cert_no.'_'.$cart->userId->trans_name;
                $file_name_pdf = $this->GetCertFileName($cart);
                $file_name = public_path() . '/certificates/attendance/'.$file_name_pdf.'.pdf';
                $mpdf->Output($file_name,'F');
                // $mpdf->WriteHTML(utf8_encode($html));
            // ============ End of generate the attendance and save it as a file ==================

            // ============ End of generate attendance pdf function ==================
            return view('training.certificates.attendance.index', compact('cart','file_name_pdf'));

        // $cart = Cart::findOrFail($id);
        // return view('training.certificates.attendance.index', compact('cart'));
    }

    public function attendance_url($id) {

        $cart = Cart::findOrFail($id);
        return view('training.certificates.attendance.pdf', compact('cart'));
    }

    public function attendance_pdf($id) {

        $job = (new LetterOfAttendanceJob($id))
                    ->delay(\Carbon\Carbon::now()->addSeconds(5));
        dispatch($job);

        Active::Flash("Letter Of Attendance Sent Successfully", __('flash.empty'), 'success');

        // $cart = Cart::findOrFail($id);
        // $FileName = $this->GetCertFileName($cart);

        // ConvertApi::setApiSecret('KmKCe223BpWtWQFC');

        // $result = ConvertApi::convert('pdf', [
        //     'Url' => route('certificates.attendance.url', ['id'=>$id]),
        //     'FileName' => $FileName,
        //     'PageSize' => 'a4',
        //     'MarginTop' => '0',
        //     'MarginRight' => '0',
        //     'MarginBottom' => '0',
        //     'MarginLeft' => '0',
        //     ], 'web'
        // );
        // $file = $result->saveFiles(public_path() . '/certificates/attendance');
        // $file = $file[0];
        // if (file_exists($file)) {

        //     if(!is_null($cart->userId->email)){
        //         $course_title = $cart->trainingOption->training_name??null;
        //         $subject = $course_title.' ('.$cart->session->certificate_from.')(CERTIFICATE)';
        //         Mail::to($cart->userId->email)
        //             // ->cc(['dabukarsh@bakkah.net.sa', 'malashqar@bakkah.net.sa', 'yreyala@bakkah.net.sa'])
        //             ->send(new CertificateEmail($cart, $subject, $FileName, 'attendance'));
        //             Cart::where('id', $id)->update([
        //                 'certificate_sent_at'=>now(),
        //             ]);
        //     }
        // }
        return back();
    }

    private function GetCertFileName($cart){
        $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
        return $cert_no.'_'.$cart->userId->en_name;
    }


    // ==========================================
    // private function DrawBarcode($cart){
    //     $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
    //     $qr_image = public_path() . "/certificates/qrcodes/".$cert_no.".png";
    //     if (!file_exists($qr_image)) {

    //         // $course_title = $cart->trainingOption->training_name??null;
    //         $course_title = $cart->course->ar_disclaimer??$cart->course->en_title;
    //         $data_for_qr = $course_title;

    //         if($cart->course->PDUs!=0){
    //             $data_for_qr .= "\n"."With ".$cart->course->PDUs." PDUs";
    //         }
    //         if(!is_null($cart->userId->trans_name)){
    //             $data_for_qr .= " for"."\n".$cart->userId->trans_name;
    //         }
    //         $data_for_qr .= "\n"."www.bakkah.net.sa";

    //         // $barcodeobj = new TCPDF2DBarcode($data_for_qr, 'QRCODE,L');
    //         // $barcodeobj_html =  $barcodeobj->getBarcodeHTML(2, 2, 'black');
    //         // $qr_image = '<div style="bottom: 50px;position: absolute;left: 29%;"><div style="position: relative; left: -50%;">';
    //         // $qr_image .= $barcodeobj_html;
    //         // $qr_image .= '</div></div>';
    //         // return $qr_image;

    //         $barcodeobj = new TCPDF2DBarcode($data_for_qr, 'QRCODE,L');
    //         $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
    //         $qr_file = public_path(env('APP_ASSET')."/certificates/qrcodes/".$cert_no.".png");
    //         file_put_contents($qr_file, $barcodeobj->getBarcodePngData());

    //         $qr_image = CustomAsset("certificates/qrcodes/".$cert_no.".png");

    //     }
    //     $qr_image = CustomAsset("certificates/qrcodes/".$cert_no.".png");
    //     $qr_image = '<img src="'.$qr_image.'" style="width: 50px;display: block;margin-bottom: 5px;margin-left:70px;margin-top: 20px;">';

    //     return $qr_image;
    // }
    // ==========================================



    // public function certificate($cart_id) {

    //     $cart = Cart::with(['trainingOption', 'userId'])->findOrFail($cart_id);
    //     // $course_title = $cart->trainingOption->training_name??null;
    //     // $candidate = $cart->userId->trans_name??null;
    //     // $data_for_qr = $course_title."\n"."With ".$cart->course->PDUs." PDUs for"."\n".$candidate."\n"."www.bakkah.net.sa";

    //     // $barcodeobj = new TCPDF2DBarcode($data_for_qr, 'QRCODE,L');

    //     // $barcodeobj_html =  $barcodeobj->getBarcodeHTML(3, 3, 'black');

    //     // $qr_image = '<div style="margin-bottom: 5px;padding: 5px;position:absolute;left: 41px;bottom: 80px;position: absolute; left: 50%;"><div style="position: relative; left: -50%;">';
    //     // $qr_image .= $barcodeobj_html;
    //     // $qr_image .= '</div></div>';
    //     $qr_image = $this->GetBarcode($cart, false);

    //     return view('training.certificates.certificate', compact('cart', 'qr_image'));
    // }

    // public function certificate_pdf($cart_id) {

    //     TCPDF_FONTS::addTTFfont(public_path('Lato\Lato-Regular.ttf'), 'TrueTypeUnicode', '', 96);
    //     PDF::setFontSubsetting(true);
    //     // PDF::setRTL(true);
    //     PDF::SetFont('lato', '', 12, '', true);

    //     $cart = Cart::with(['trainingOption', 'userId'])->findOrFail($cart_id);

    //     PDF::SetTitle('Certificate');

    //     // @include('training.certificates.certificate-content')
    //     PDF::SetMargins(0, 0, 0, 0);
    //     PDF::setCellPaddings(0, 0, 0, 0);
    //     PDF::setFooterMargin(0);
    //     PDF::setPrintHeader(false);
    //     PDF::setPrintFooter(true);
    //     PDF::AddPage('L');//P

    //     // $html = view('training.certificates.test_pdf', compact('cart'));
    //     $qr_image = $this->GetBarcode($cart, true);

    //     $html = view('training.certificates.certificate-pdf', compact('cart', 'qr_image'))->render();

    //     PDF::writeHTML($html, true, false, true, false, '');
    //     // PDF::Output('hello_world.pdf');
    //     // PDF::Output('hello_world.pdf', 'S');
    //     PDF::Output(public_path('certificates/certificate/hello_world.pdf'), 'F');
    //     // Mail::to('abed_348@hotmail.com')->send(new CertificateEmail($cart));
    // }

    // private function GetBarcode($cart, $isPdf=false){

    //     $course_title = $cart->trainingOption->training_name??null;
    //     $data_for_qr = $course_title;

    //     if($cart->course->PDUs!=0){
    //         $data_for_qr .= "\n"."With ".$cart->course->PDUs." PDUs";
    //     }
    //     if(!is_null($cart->userId->trans_name)){
    //         $data_for_qr .= " for"."\n".$cart->userId->trans_name;
    //     }
    //     $data_for_qr .= "\n"."www.bakkah.net.sa";

    //     $barcodeobj = new TCPDF2DBarcode($data_for_qr, 'QRCODE,L');
    //     // $barcodeobj_html =  $barcodeobj->getBarcodeHTML(3, 3, 'black');
    //     // $qr_image = '<div style="margin-bottom: 5px;padding: 5px;position:absolute;left: 41px;bottom: 80px;position: absolute; left: 50%;"><div style="position: relative; left: -50%;">';
    //     // $qr_image .= $barcodeobj_html;
    //     // $qr_image .= '</div></div>';
    //     // $qr_image = $barcodeobj->getBarcodeHTML(3, 3, 'black');

    //     //start getBarcodePngData
    //     $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
    //     $barcodeobj_html = public_path(env('APP_ASSET')."/certificates/barcodes/".$cert_no.".png");
    //     file_put_contents($barcodeobj_html, $barcodeobj->getBarcodePngData());
    //     $qr_image = '<div style="margin-bottom: 5px;padding: 5px;position:absolute;left: 41px;bottom: 80px;position: absolute; left: 50%;"><div style="position: relative; left: -50%;">';
    //     if(!$isPdf){
    //         $barcodeobj_html = CustomAsset("certificates/barcodes/".$cert_no.".png");
    //     }
    //     $qr_image .= '<img src="'.$barcodeobj_html.'">';
    //     $qr_image .= '</div></div>';
    //     //end getBarcodePngData

    //     return $qr_image;
    // }

}