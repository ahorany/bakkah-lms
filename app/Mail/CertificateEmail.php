<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $cart;
    public $subject;
    public $FileName;
    public $folder;

    public function __construct($cart, $subject, $FileName, $folder='certificate')
    {
        $this->cart = $cart;
        $this->subject = $subject;
        $this->FileName = $FileName;
        $this->folder = $folder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $path = 'certificates/'.$this->folder.'/'.$this->FileName.'.pdf';

        if($this->cart->session_id != 1325){
            return $this->subject($this->subject)->view('emails.courses.certificate_email', [
                // 'pdf'=>public_path() . '/certificates/certificate-'.$this->cart->id.'.pdf',
                'pdf'=>CustomAsset($path),
                'cart'=>$this->cart,
            ])->attach(public_path($path), [
                'as' => $this->FileName.'.pdf',
                'mime' => 'application/pdf',
            ]);
        }else{
            return $this->subject($this->subject)->view('emails.courses.certificate_email', [
                // 'pdf'=>public_path() . '/certificates/certificate-'.$this->cart->id.'.pdf',
                'pdf'=>CustomAsset($path),
                'cart'=>$this->cart,
                'subject'=>$this->subject,
            ])->attach(public_path($path), [
                'as' => $this->FileName.'.pdf',
                'mime' => 'application/pdf',
            ])->attach("https://bakkah.com/public/upload/full/Bakkah-Offer.png", [
                'as' => 'Bakkah-Offer.png',
                'mime' => 'image/png',
            ]);
        }

        // return $this->view('emails.courses.test_pdf')->attach(public_path('certificates/certificate-'.$this->cart->id.'.pdf'), [
        //     'as' => 'name.pdf',
        //     'mime' => 'application/pdf',
        // ]);
    }
}
