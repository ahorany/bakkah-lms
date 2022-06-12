<?php

namespace App\Http\Controllers\Training;

use App\Constant;
use App\Http\Controllers\Controller;
use App\Models\Training\Cart;
use App\Models\Training\Course;
use App\Models\Training\CourseRegistration;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\CertificateEmail;
// use PDF;
// use TCPDF_FONTS;

use App\Models\Training\Certificate;
use App\Http\Requests\Training\CertificateRequest;
use Illuminate\Http\Request;

// require __DIR__ . '/tcpdf/tcpdf_barcodes_2d.php';
use App\Http\Controllers\Training\tcpdf\TCPDF2DBarcode;
use App\Jobs\CertificateJob;
use App\Jobs\LetterOfAttendanceJob;

// require __DIR__ . '/vendor/autoload.php';
// use \ConvertApi\ConvertApi;
use App\Helpers\Active;

use DB;

class CertificateControllerH extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training.certificates.index', ['except' => ['certificate_dynamic']]);

        Active::$namespace = 'training';
        Active::$folder = 'certificates';
    }

    public function index(){
        $post_type = 'certificate';
        $trash = GetTrash();
        $certificates = Certificate::whereNull('parent_id')->where('branch_id',getCurrentUserBranchData()->branch_id);

        if(!is_null(request()->title)) {
            $certificates = $certificates->where(function($query){
                $query->where('title', 'like', '%'.request()->title.'%');
            });
        }

        // $show_in_website = request()->has('show_in_website')?1:0;
        // $certificates = $certificates->where('show_in_website', $show_in_website);

        $count = $certificates->count();
        $certificates = $certificates->page();
        return Active::Index(compact('certificates', 'count', 'post_type', 'trash'));
    }

    public function create(){
        $aligns = Constant::where('post_type','align')->get();
        $directions      = Constant::where('post_type','direction')->get();
        $fonts           = Constant::where('post_type','font_type')->get();

        return Active::Create(compact('aligns','directions','fonts'));
    }

    public function store(CertificateRequest $request){

        $validated = $this->Validated($request->validated());
        $validated['created_by'] = auth()->user()->id;
        $validated['title'] = null;
        $validated['branch_id'] = getCurrentUserBranchData()->branch_id;

        $certificate = Certificate::create($validated);
        $post_type = request()->post_type;

        $this->uploadsCertificate($certificate, 'background', 'en');

        return Active::Inserted($certificate->trans_title, [
            'post_type' => $post_type,
        ]);
    }

    public function edit(Certificate $certificate){
        if($certificate->branch_id != getCurrentUserBranchData()->branch_id){
            abort(404);
        }
        $childs     = Certificate::where('parent_id',$certificate->id)->get();

        $aligns = Constant::where('post_type','align')->get();
        $directions = Constant::where('post_type','direction')->get();
        $fonts      = Constant::where('post_type','font_type')->get();

        // dd($kinds);
        return Active::Edit([
            'eloquent'  => $certificate,
            'post_type' => $certificate->post_type,
            'childs'    => $childs,
            'aligns'    => $aligns,
            'directions'=> $directions,
            'fonts'     => $fonts,

        ]);
    }

    public function update(CertificateRequest $request, Certificate $certificate){
        if($certificate->branch_id != getCurrentUserBranchData()->branch_id){
            abort(404);
        }
        $validated = $this->validated($request->validated());
        $validated['updated_by'] = auth()->user()->id;
        $validated['branch_id'] = getCurrentUserBranchData()->branch_id;

        Certificate::find($certificate->id)->update($validated);
        $this->uploadsCertificate($certificate, 'background', 'en');

        foreach(request()->all() as $key => $value)
        {
            // dd(substr($key,1,7));
            if(substr($key,0,8) == 'content_')
            {
                $arr = explode('_',$key);
                $content_id = $arr[1];
                $content_id = Certificate::find($content_id);
                $value=  str_replace('left','',$value);
                $value=  str_replace('right','',$value);
                $value=  str_replace('center','',$value);
                $content_id->content = $value;
                $content_id->save();
            }
            if(substr($key,0,6) == 'align_')
            {
                $arr = explode('_',$key);
                $c_id = $arr[1];

                $c_id = Certificate::find($c_id);
                // dd($c_id);
                $c_id->align = $value;
                $c_id->save();
            }
        }

        return Active::Updated($certificate->trans_title);
    }

    public function destroy(Certificate $certificate, Request $request){
        Certificate::where('id', $certificate->id)->where('branch_id',getCurrentUserBranchData()->branch_id)->SoftTrash();
        return Active::Deleted($certificate->trans_title);
    }

    public function restore($certificate){
        Certificate::where('id', $certificate)->where('branch_id',getCurrentUserBranchData()->branch_id)->RestoreFromTrash();

        $certificate = Certificate::where('id', $certificate)->first();
        return Active::Restored($certificate->title);
    }

    private function Validated($validated){
        $validated['title'] = null;
        $validated['updated_by'] = auth()->user()->id;
        return $validated;
    }

    private function uploadsCertificate($model, $name='pdf', $locale='en'){

        if(request()->has($name)){

            $upload = $model->uploads()->where('post_type',$name)->first();

            $pdf = request()->file($name);
            $title = $pdf->getClientOriginalName();

            $fileName = $title;
            $fileName = strtolower($fileName);

            if($pdf->move(public_path('certificates/img/'), $fileName)){
                if(is_null($upload))
                {
                    $model->uploads()->create([
                        'title'=>$title,
                        'file'=>$fileName,
                        'extension'=>'pdf',
                        'post_type'=>'pdf',
                        'created_by'=>$model->created_by,
                        'updated_by'=>$model->updated_by,
                        'locale'=>$locale,
                    ]);
                }
                else
                {
                    $this->unlinkPDF($name, $upload->file);
                    $model->uploads()->update([
                        'title'=>$title,
                        'file'=>$fileName,
                        'extension'=>'pdf',
                        'post_type'=>'pdf',
                        'created_by'=>$model->created_by,
                        'updated_by'=>$model->updated_by,
                        'locale'=>$locale,
                    ]);
                }
            }
        }
    }

    public function preview()
    {
        $certificate =  Certificate::whereId(\request()->id)
                      ->where('branch_id',getCurrentUserBranchData()->branch_id)->first();
        if (!$certificate){
            abort(404);
        }
        // dd(request()->all());
        foreach(request()->all() as $key => $value)
        {
            // dump(substr($key,1,7));
            if(substr($key,0,2) == 'x_')
            {
                $arr = explode('_',$key);
                $content_id = $arr[1];
                $content_id = Certificate::find($content_id);
                if(isset($content_id->id))
                {
                    $content_id->x_axis = $value;
                    $content_id->save();
                }
            }
            elseif(substr($key,0,2) == 'y_')
            {
                $arr = explode('_',$key);
                $content_id = $arr[1];
                $content_id = Certificate::find($content_id);
                if(isset($content_id->id))
                {
                    $content_id->y_axis = $value;
                    $content_id->save();
                }
            }
            elseif(substr($key,0,5) == 'xpdf_')
            {
                $arr = explode('_',$key);
                $content_id = $arr[1];
                $content_id = Certificate::find($content_id);

                if(isset($content_id->id))
                {
                    $content_id->xpdf_axis = $value;
                    $content_id->save();
                }
            }
            elseif(substr($key,0,5) == 'ypdf_')
            {
                $arr = explode('_',$key);
                $content_id = $arr[1];
                $content_id = Certificate::find($content_id);
                if(isset($content_id->id))
                {
                    // dump($value.'--'.$content_id->id);
                    $content_id->ypdf_axis = $value;
                    $content_id->save();
                }
            }

        }

        $certificate = Certificate::find(request()->id);
        $childs      = Certificate::where('parent_id',request()->id)->where('content','!=',null)->get();
        // dd($childs);
        $parent_id = request()->id;
        return view('training.certificates.preview', compact('certificate','childs','parent_id'));
    }

    public function add_new()
    {
        $certificate =  Certificate::whereId(\request()->parent_id)
            ->where('branch_id',getCurrentUserBranchData()->branch_id)->first();
        if (!$certificate){
            abort(404);
        }
        $certificate = new Certificate;
        $certificate->parent_id = request()->parent_id;
        $certificate->save();

        $eloquent   = Certificate::find(request()->parent_id);
        return redirect()->route('training.certificates.edit', [
            'certificate'=>$eloquent->id,
        ]);
    }

    public function delete_rich()
    {
        $parent_id =  Certificate::where('id', request()->id)->pluck('parent_id')->first();
         Certificate::where('id', request()->id)->delete();

         $eloquent   = Certificate::find($parent_id);
         return redirect()->route('training.certificates.edit', [
            'certificate'=>$eloquent->id,
        ]);
    }

    public function save_position()
    {
        // dd(request()->all());
        foreach(request()->all() as $key => $value)
        {
            // dump(substr($key,1,7));
            if(substr($key,0,2) == 'x_')
            {
                // dd($value);
                $arr = explode('_',$key);
                $content_id = $arr[1];
                $content_id = Certificate::find($content_id);
                $content_id->x_axis = $value;
                $content_id->save();
            }
            elseif(substr($key,0,2) == 'y_')
            {
                $arr = explode('_',$key);
                $content_id = $arr[1];
                $content_id = Certificate::find($content_id);
                $content_id->y_axis = $value;
                $content_id->save();
            }
            elseif(substr($key,0,5) == 'xpdf_')
            {
                $arr = explode('_',$key);
                $content_id = $arr[1];
                $content_id = Certificate::find($content_id);
                $content_id->xpdf_axis = $value;
                $content_id->save();

            }
            elseif(substr($key,0,5) == 'ypdf_')
            {
                $arr = explode('_',$key);
                $content_id = $arr[1];
                $content_id = Certificate::find($content_id);
                $content_id->ypdf_axis = $value;
                $content_id->save();

            }
        }

        // return request()->all();
    }

    public function replicate()
    {
        $parent_id = request()->certificate;
        $c = Certificate::where('id',$parent_id)->where('branch_id',getCurrentUserBranchData()->branch_id)->first();
        if (!$c){
            abort(404);
        }

        $json = $c->title;
        $json = json_decode($json, true);
        $en = $json['en'];$ar = $json['ar'];

        $insertQuery = "insert into certificates (title,created_by,direction,font_type) SELECT title,'".auth()->user()->id."',direction,font_type
                            FROM certificates where id = $parent_id " ;

        DB::insert($insertQuery);
        $inserted_id = DB::getPdo()->lastInsertId();

        DB::table('certificates')
            ->where('id', $inserted_id)
            ->update(['title->en' => $en.'_'.$inserted_id,
                    'title->ar' => $ar.'_'.$inserted_id]);

        $insertQuery = "insert into certificates (parent_id,align,x_axis,y_axis,xpdf_axis,ypdf_axis,content,created_by)
                            SELECT $inserted_id,align,x_axis,y_axis,xpdf_axis,ypdf_axis,content,'".auth()->user()->id."' FROM certificates where parent_id = $parent_id " ;
        DB::insert($insertQuery);


        $insertUpload = "insert into uploads (uploadable_id,uploadable_type,title,excerpt,name,file,locale,post_type,created_by)
                            SELECT $inserted_id,uploadable_type,title,excerpt,name,file,locale,post_type,'".auth()->user()->id."'
                            FROM uploads where uploadable_id = $parent_id " ;
        DB::insert($insertUpload);

        return redirect()->route('training.certificates.index');
    }

    /************************************************************************** */
    public function certificate_userProfile($id) {

        $body = $this->certificate_body(['cart_id'=>$id]);
        return view('userprofile::users.certifications_preview', [
            'cart'=>$body['cart'],
            'data_for_qr'=>$body['data_for_qr'],
            'file_name_pdf'=>$body['file_name_pdf'],
        ]);
    }

    public function certificate($id) {
        // dd($id);
        $body = $this->certificate_body(['cart_id'=>$id]);
        return view('training.certificates.certificate.index', [
            'cart'=>$body['cart'],
            'data_for_qr'=>$body['data_for_qr'],
            'file_name_pdf'=>$body['file_name_pdf'],
        ]);

    }

    public function certificate_dynamic() {

        // $course = Course::find(request()->course_registration_id);
        $course_registration = CourseRegistration::leftJoin('sessions', function($query){
            $query->on('sessions.id','=','courses_registration.session_id')
            ->whereNotNull('courses_registration.session_id')
            ->whereNull('sessions.deleted_at');
        })
        ->select('courses_registration.*'
        , 'sessions.date_from', 'sessions.date_to', 'sessions.ref_id')
        ->find(request()->course_registration_id);
        // dd($course_registration);
        // dd(request()->course_registration_id);

        $course = Course::find($course_registration->course_id);
        // dump($course_registration->course_id);
        // dd($course);
        $body = $this->certificate_body(['certificate_id'=>$course->certificate_id,
                                        'course_registration'=>$course_registration]);
                                        // dd('dxdd');

        return view('training.certificates.certificate.index', [
            'cart'=>$body['cart'],
            'data_for_qr'=>$body['data_for_qr'],
            'file_name_pdf'=>$body['file_name_pdf'],
            'course_title'=>$course->trans_title,
            'course'=>$body['course'],
        ]);
    }

    public function certificate_body($array=null) {
        // ============ Start of Data will be in certificate ==================
        $cart = '';$data_for_qr='';
        // if(isset($array['cart_id']) && $array['cart_id'] != '')
        // {
        //     $id = $array['cart_id'];
        //     $cart = Cart::findOrFail($id);
        //     // dd($cart);
        //     // $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
        //     $course_title = $cart->course->ar_disclaimer??$cart->course->en_title;
        //     $data_for_qr = $course_title;

        //     if($cart->course->PDUs!=0)
        //     {
        //         $data_for_qr .= "\n"."With ".$cart->course->PDUs." PDUs";
        //     }
        //     if(!is_null($cart->userId->trans_name))
        //     {
        //         $data_for_qr .= " for"."\n".$cart->userId->trans_name;
        //     }
        //     $data_for_qr .= "\n"."www.bakkah.com";
        //     $certificate_id  = $cart->course->certificate_id;
        //     // dd($certificate_id);
        // }
        // else

        if(isset($array['certificate_id']) && $array['certificate_id'] != '')
        {
            $certificate_id = $array['certificate_id'];
        }
        // ============ End of Data will be in certificate ==================
        if($certificate_id != '' && $certificate_id != -1)
        {
            $certificate = Certificate::find($certificate_id);
            if(is_null($certificate)){
                return 0;
            }

            $orientation = 'L';
            if($certificate->direction == 490)
                $orientation = 'P';
            $fontName = $this->get_fontName($certificate->font_type);
            // dd($certificate->font_type);
            $mpdf = new \Mpdf\Mpdf([
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 0,
                'margin_header' => 0,
                'margin_footer' => 0,
                'default-font' =>  $fontName,
                'orientation' => $orientation,
            ]);

            $mpdf->SetProtection(array('print'));
            $mpdf->SetTitle("Certificate");//Letter Of Attendance
            $mpdf->SetAuthor(__('education.app_title'));
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetFont('cairo');
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



            // ============ Start of generate the certificate and save it as a file ==================
            ob_start();



            // $body = view('training.certificates.certificate.content', compact('cart', 'data_for_qr'))->render();
            // $body = view('training.certificates.attendance.content', compact('cart'))->render();


            $childs   = Certificate::where('parent_id',$certificate_id)->where('content','!=',null)->get();

            $parent_id = $certificate_id;
            $user = '' ;$course_registration='';$course='';
            if(isset($array['course_registration']))
            {

                $course_registration = $array['course_registration'];
                $course = Course::find($course_registration->course_id);
                // $course = Course::find($array['course_id']);
                // $user = User::find($course_registration->user_id);
                $user = User::getUser($course_registration->user_id);

                $data_for_qr = $course->en_title;

                if($course->PDUs!=0)
                {
                    $data_for_qr .= "\n"."With ".$course->PDUs." PDUs";
                }

                if(!is_null($user[0]->name))
                {
                    $data_for_qr .= " for"."\n".$user[0]->name;
                }

                $data_for_qr .= "\n"."stage.bakkah.com/";
                // $s = strtotime($course_registration->expire_date);
                // dump($s->format('d F,Y'));
                // dd($user->created_at->format('d F,Y'));
            }

            // dd( $data_for_qr);

            $body = view('training.certificates.preview_pdf', compact('user','course_registration','certificate','childs','parent_id','cart','data_for_qr','course'))->render();
            try{
                $mpdf->WriteHTML($body);
            }catch(\Mpdf\MpdfException $e){
                die($e->getMessage());
            }
            ob_end_clean();
            if(isset($array['cart_id']) && $array['cart_id'] != '')
            {
                $file_name_pdf = $this->GetCertFileName($cart);
                $file_name = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
                $mpdf->Output($file_name,'F');
            }
            elseif(isset($array['certificate_id']) && $array['certificate_id'] != '')
            {
                $file_name_pdf = 'certificate';//$this->GetCertFileName($cart);
                $file_name = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
                $mpdf->Output($file_name,'F');
            }

            // dd($course);
            if(isset($array['certificate_id']) && $array['certificate_id'] != '')
                return compact('cart', 'data_for_qr', 'file_name_pdf','course');
            else
                return view('training.certificates.certificate.index', compact('cart', 'data_for_qr','file_name_pdf','course'));
        }
        else
            return 0;

    }

    public function preview_pdf()
    {
        // $body = $this->preview_body(request()->id);
        $body = $this->certificate_body(['certificate_id'=>request()->id]);
        return view('training.certificates.certificate.index', [
            'cart' => $body['cart'],
            'data_for_qr' => $body['data_for_qr'],
            'file_name_pdf' => $body['file_name_pdf'],
        ]);

    }

    // public function preview_body()
    // {

    //         $id = request()->id;

    //         // ============ Start of generate certification pdf function ==================
    //         // https://github.com/mpdf/mpdf
    //         // https://mpdf.github.io/css-stylesheets/supported-css.html
    //         // ============ Start of PDF sesstings ==================

    //             $mpdf = new \Mpdf\Mpdf([
    //                 'margin_left' => 0,
    //                 'margin_right' => 0,
    //                 'margin_top' => 0,
    //                 'margin_bottom' => 0,
    //                 'margin_header' => 0,
    //                 'margin_footer' => 0,
    //                 'default-font' => 'Lato',
    //                 'orientation' => 'L',//P//
    //             ]);

    //             $mpdf->SetProtection(array('print'));
    //             $mpdf->SetTitle("Certificate");//Letter Of Attendance
    //             $mpdf->SetAuthor(__('education.app_title'));
    //             $mpdf->SetDisplayMode('fullpage');
    //             $mpdf->SetFont('lato');
    //             $mpdf->autoScriptToLang = true;
    //             $mpdf->baseScript = 1;
    //             $mpdf->autoVietnamese = true;
    //             $mpdf->autoArabic = true;
    //             $mpdf->autoLangToFont = true;



    //                 // $mpdf->SetDirectionality('rtl');

    //                 // $mpdf->SetWatermarkText("Paid");
    //                 // $mpdf->showWatermarkText = true;
    //                 // $mpdf->watermark_font = 'Lato';
    //                 // $mpdf->watermarkTextAlpha = 0.1;
    //                 // $mpdf->setAutoTopMargin = 'stretch';
    //             // ============ End of PDF sesstings ==================

    //             // ============ Start of Data will be in certificate ==================
    //                 $cart = Certificate::findOrFail($id);
    //                 // $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
    //                 // $course_title = $cart->course->ar_disclaimer??$cart->course->en_title; ++
    //                 // $data_for_qr = $course_title;
    //                 $data_for_qr  = '';
    //                 // if($cart->course->PDUs!=0){ ++
    //                 //     $data_for_qr .= "\n"."With ".$cart->course->PDUs." PDUs"; ++
    //                 // }
    //                 // if(!is_null($cart->userId->trans_name)){++
    //                 //     $data_for_qr .= " for"."\n".$cart->userId->trans_name;
    //                 // }
    //                 // $data_for_qr .= "\n"."www.bakkah.com"; ++
    //             // ============ End of Data will be in certificate ==================

    //             // ============ Start of generate the certificate and save it as a file ==================

    //                 ob_start();
    //                 $certificate = Certificate::find($id);
    //                 $childs      = Certificate::where('parent_id',$id)->where('content','!=',null)->get();
    //                 // foreach($childs as $child)
    //                 // {
    //                 //     $mpdf->WriteFixedPosHTML($child->content, $child->y_axis, $child->x_axis, 300, 100, 'auto');
    //                 // }


    //                 $parent_id = $id;
    //                 $body = view('training.certificates.preview_pdf', compact('certificate','childs','parent_id'))->render();
    //                 // dd($body);
    //                 try{
    //                     $mpdf->WriteHTML($body);
    //                 }catch(\Mpdf\MpdfException $e){
    //                     die($e->getMessage());
    //                 }
    //                 // dd($body);
    //                 ob_end_clean();

    //                 // $mpdf->Output();
    //                 // $file_name_pdf = $cert_no.'_'.$cart->userId->trans_name;
    //                 $file_name_pdf = 'certificate';//$this->GetCertFileName($cart);
    //                 $file_name = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
    //                 $mpdf->Output($file_name,'F');
    //                 // $mpdf->WriteHTML(utf8_encode($html));
    //             // ============ End of generate the certificate and save it as a file ==================

    //                 // $show_pdf = env('APP_URL') . 'certificates/certificate/'.$file_name_pdf.'.pdf';
    //                 // var_dump($show_pdf);

    //     // ============ End of generate certification pdf function ==================
    //     return compact('cart', 'data_for_qr', 'file_name_pdf');
    // }
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


    // public function attendance($id) {

    //     // $cart = Cart::findOrFail($id);
    //     // $qr_image = $this->DrawBarcode($cart);
    //     // return view('training.certificates.attendance.index', compact('cart', 'qr_image'));

    //         // ============ Start of generate attendance pdf function ==================
    //             // https://github.com/mpdf/mpdf
    //             // https://mpdf.github.io/css-stylesheets/supported-css.html

    //             // ============ Start of PDF sesstings ==================
    //             $mpdf = new \Mpdf\Mpdf([
    //                 'margin_left' => 0,
    //                 'margin_right' => 0,
    //                 'margin_top' => 0,
    //                 'margin_bottom' => 0,
    //                 'margin_header' => 0,
    //                 'margin_footer' => 0,
    //                 'default-font' => 'Lato',
    //                 'orientation' => 'P',
    //             ]);

    //             $mpdf->SetProtection(array('print'));
    //             $mpdf->SetTitle("Letter Of Attendance");
    //             $mpdf->SetAuthor(__('education.app_title'));
    //             $mpdf->SetDisplayMode('fullpage');
    //             $mpdf->SetFont('lato');
    //             $mpdf->autoScriptToLang = true;
    //             $mpdf->baseScript = 1;
    //             $mpdf->autoVietnamese = true;
    //             $mpdf->autoArabic = true;
    //             $mpdf->autoLangToFont = true;
    //             // $mpdf->SetDirectionality('rtl');

    //             // $mpdf->SetWatermarkText("Paid");
    //             // $mpdf->showWatermarkText = true;
    //             // $mpdf->watermark_font = 'Lato';
    //             // $mpdf->watermarkTextAlpha = 0.1;
    //             // $mpdf->setAutoTopMargin = 'stretch';
    //         // ============ End of PDF sesstings ==================

    //         // ============ Start of Data will be in attendance ==================
    //             $cart = Cart::findOrFail($id);
    //             // $cert_no = !is_null($cart->cert_no)?$cart->cert_no:$cart->id;
    //         // ============ End of Data will be in attendance ==================

    //         // ============ Start of generate the attendance and save it as a file ==================
    //             ob_start();
    //                 $body = view('training.certificates.attendance.content', compact('cart'))->render();
    //                 try{
    //                     $mpdf->WriteHTML($body);
    //                 }catch(\Mpdf\MpdfException $e){
    //                     die($e->getMessage());
    //                 }
    //             ob_end_clean();

    //             // $mpdf->Output();
    //             // $file_name_pdf = $cert_no.'_'.$cart->userId->trans_name;
    //             $file_name_pdf = $this->GetCertFileName($cart);
    //             $file_name = public_path() . '/certificates/attendance/'.$file_name_pdf.'.pdf';
    //             $mpdf->Output($file_name,'F');
    //             // $mpdf->WriteHTML(utf8_encode($html));
    //         // ============ End of generate the attendance and save it as a file ==================

    //         // ============ End of generate attendance pdf function ==================
    //         return view('training.certificates.attendance.index', compact('cart','file_name_pdf'));

    //     // $cart = Cart::findOrFail($id);
    //     // return view('training.certificates.attendance.index', compact('cart'));
    // }

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


    public function get_fontName($type)
    {
        $font_name = 'Lato';
       if($type ==498 )
            $font_name = 'Cairo';

        return $font_name;
    }
}
