<?php

namespace App\Jobs;

use App\Models\Training\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\CertificateEmail;
use App\Models\Training\WebinarsRegistration;

// use App\Http\Controllers\Training\CertificateController;

// require __DIR__ . '/tcpdf/tcpdf_barcodes_2d.php';
// use App\Http\Controllers\Training\tcpdf\TCPDF2DBarcode;


// require __DIR__ . '/vendor/autoload.php';
// use \ConvertApi\ConvertApi;


class CertificateWebinarJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $selectedId;
    // public $file_name_pdf;

    public function __construct($selectedId)
    {
        $this->selectedId = $selectedId;
        // $this->file_name_pdf = $file_name_pdf;
    }

    public function handle()
    {
        // if(auth()->check()){
        //     if(auth()->user()->id!=2){
        //         dd($this->selectedId);
        //     }
        // }
        $seconds = 5;
        foreach($this->selectedId as $selectedId){

            $cart = WebinarsRegistration::findOrFail($selectedId);
            if(!is_null($cart->certificate_sent_at)){

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

                $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
                $file_name_pdf = $cert_no.'_'.$cart->userId->en_name;
                // $file_name_pdf = $this->GetCertFileName($cart);
                $file_name = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
                $mpdf->Output($file_name,'F');

                $file = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
                if (file_exists($file)) {

                    if(!is_null($cart->userId->email)){

                        $when = now()->addSeconds($seconds);
                        Mail::to($cart->userId->email)->later($when, new CertificateEmail($cart, "Completion Certificate", $file_name_pdf, 'certificate'));
                        // Mail::to($cart->userId->email)
                        // ->send(new CertificateEmail($cart, null, $file_name_pdf, 'certificate'));

                        WebinarsRegistration::where('id', $cart->id)->update([
                            'certificate_sent_at'=>now(),
                        ]);
                    }
                    // readfile($file);
                    // exit;
                }
                /////////////////////////////////////////////////////
                // $job = (new CertificateWebinarJob($selectedId))
                //     ->delay(\Carbon\Carbon::now()->addSeconds(5));
                // dispatch($job);

                // $certificate_attempt_number = $cart->certificate_attempt_number +1;
                // WebinarsRegistration::where('id', $selectedId)->update([
                //     'certificate_attempt_number'=>$certificate_attempt_number,
                // ]);
                $seconds += 5;
            }
        }
        // $id = $this->id;
        // $cart = WebinarsRegistration::findOrFail($id);

        // $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
        // $file_name_pdf = $cert_no.'_'.$cart->userId->en_name;

        // $file = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
        // if (file_exists($file)) {

        //     if(!is_null($cart->userId->email)){

        //         Mail::to($cart->userId->email)
        //         ->send(new CertificateEmail($cart, null, $file_name_pdf, 'certificate'));
        //         WebinarsRegistration::where('id', $id)->update([
        //             'certificate_sent_at'=>now(),
        //         ]);
        //     }
        //     // readfile($file);
        //     // exit;
        // }
    }
}
