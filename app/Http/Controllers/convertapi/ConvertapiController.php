<?php

namespace App\Http\Controllers\convertapi;

use App\Http\Controllers\Controller;
use App\Models\Training\Cart;

require __DIR__ . '/vendor/autoload.php';
use \ConvertApi\ConvertApi;

require __DIR__ . '/tcpdf/tcpdf_barcodes_2d_include.php';
use App\Http\Controllers\convertapi\tcpdf\TCPDF2DBarcode;

class ConvertapiController extends Controller
{
    public function certificate_url($id) {
        // 127.0.0.1:8000/training/certificates-url/1254
        // dump('aa');
        // dd(__DIR__);
        $post = Cart::find($id);
        // //$data_for_qr = $cert_course_title_en."\n"."With ".$ql_pdus." PDUs for"."\n".$name."\n"."www.bakkah.net.sa";
        $data_for_qr = 'This is test content';
        $barcodeobj = new TCPDF2DBarcode($data_for_qr, 'QRCODE,L');
        dd($barcodeobj);

        // // $barcodeobj_html =  $barcodeobj->getBarcodeHTML(3, 3, 'black');
        // // $qr_image = '<div style="margin-bottom: 5px;padding: 5px;position:absolute;left: 41px;bottom: 80px;position: absolute; left: 50%;"><div style="position: relative; left: -50%;">';
        // // $qr_image .= $barcodeobj_html;
        // // $qr_image .= '</div></div>';

        // // return $qr_image;

        //return view('training.certificates.certificate-content', compact('post'));
    }

    public function certificate($id) {

        ConvertApi::setApiSecret('KmKCe223BpWtWQFC');
        // dd(__DIR__);
        // dd(11);
        // $post = Cart::find($id);
        // return view('training.certificates.certificate', compact('post'));
        $result = ConvertApi::convert('pdf', [
            'File' => 'html_certificate.html',
            'FileName' => 'html_certificate',
            'PageOrientation' => 'landscape',
            'PageSize' => 'a4',
            'MarginTop' => '0',
            'MarginRight' => '0',
            'MarginBottom' => '0',
            'MarginLeft' => '0',
            ], 'html'
        );
        // $result->saveFiles(__DIR__ . '\pdf');
    }
}
