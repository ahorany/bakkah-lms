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

// use App\Http\Controllers\Training\CertificateController;

// require __DIR__ . '/tcpdf/tcpdf_barcodes_2d.php';
// use App\Http\Controllers\Training\tcpdf\TCPDF2DBarcode;


// require __DIR__ . '/vendor/autoload.php';
// use \ConvertApi\ConvertApi;


class CertificateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id;
    // public $file_name_pdf;

    public function __construct($id)
    {
        $this->id = $id;
        // $this->file_name_pdf = $file_name_pdf;
    }

    public function handle()
    {
        $id = $this->id;
        // $file_name_pdf = $this->file_name_pdf;
        $cart = Cart::findOrFail($id);
        // $FileName = $cart->cert_no.'_'.$cart->userId->en_name;
        // $file_name_pdf = $cart->cert_no.'_'.$cart->userId->en_name;
        $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
        $file_name_pdf = $cert_no.'_'.$cart->userId->en_name;

        // $file_name_pdf = $this->GetCertFileName($cart);
        // $FileName = $this->file_name_pdf;

        // $OldFile = public_path() . '/certificates/certificate/'.$FileName.'.pdf';
        // if(file_exists($OldFile)){
        //     unlink($OldFile);
        // }

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
        //     'ConversionDelay' => '15',
        //     ], 'web'
        // );

        // $file = $result->saveFiles(public_path() . '/certificates/certificate');
        // $file = $file[0];
        $file = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
        if (file_exists($file)) {

            if(!is_null($cart->userId->email)){
                $course_title = $cart->trainingOption->training_name??null;
                $subject = $course_title.' ('.$cart->session->certificate_from.')(CERTIFICATE)';
                Mail::to($cart->userId->email)
                    // ->cc(['dabukarsh@bakkah.net.sa', 'malashqar@bakkah.net.sa', 'yreyala@bakkah.net.sa'])
                    ->send(new CertificateEmail($cart, $subject, $file_name_pdf, 'certificate'));
                    Cart::where('id', $id)->update([
                        'certificate_sent_at'=>now(),
                        'certificate_file'=>$file_name_pdf.'.pdf',
                    ]);
            }
            // readfile($file);
            // exit;
        }
    }
}