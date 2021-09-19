<?php

namespace App\Http\Controllers\Training;

use App\Constant;
use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Jobs\SurveyJob;
use App\Models\Training\Cart;
use App\Models\Training\Course;
use App\Models\Training\Session;
use Illuminate\Database\Eloquent\Builder;

class EvaluationController extends Controller
{
    public $exam_simulator_courses = array(32,35,36,37);

    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'evaluations';
    }

    public function sessionsJson(){

        $sessions = Session::whereNotNull('id');
        if(request()->has('course_id') && request()->course_id!=-1 && request()->course_id!=-0){
            $sessions = $sessions->whereHas('trainingOption.course', function (Builder $query){
                $query->where('course_id', request()->course_id);
            });
        }
        $sessions = $sessions->with('city','trainingOption.course')->orderBy('date_from')->get();

        $session_array = [];
        foreach($sessions as $session){

            $trining_short_name = $session->course->trining_short_name??null;
            $trining_option_name = $session->trainingOption->constant->trans_name??null;
            $evaluation_api_code = $session->evaluation_api_code??null;
            // $evaluation_api_code = $session->trainingOption->evaluation_api_code??null;

            if($session->trainingOption->constant_id??null == 383){
                $trining_city_name = $session->city->trans_name??null;
                $trining_option_name .= ' | '.$trining_city_name;
            }

            $trining_option_name .= ($session->type_id == 370) ? ' | B2B' : '';
            // $type = ($session->type_id == 370) ? ' | B2B' : '';

            array_push($session_array, [
                'id'=>$session->id,
                'json_title'=>'SID: '.$session->id.' | '.$trining_short_name.' ( '.$session->published_from.'-'.$session->published_to.' )'.' | '.$trining_option_name.' | '.$evaluation_api_code,
            ]);
        }
        return json_encode($session_array);
    }


    // ========================================================

    public function index(){

        $post_type = GetPostType('evaluation');
        Active::Link(GetPostType($post_type));
        Active::$folder = 'evaluations';

        $all_courses = $this->GetAllCourses();
        $session_array = $this->sessionsJson();

        // $status = Constant::where('parent_id', 62)->whereIn('id', [68, 315, 317, 332])->get();
        // $status = Constant::where('xero_code', 1)->get(); // completed, Employess, PO, Free
        $status = Constant::whereIn('id', [68, 315, 317, 332, 376])->get(); // completed, Employess, PO, Free
        $delivery_methods = Constant::where('parent_id', 10)->get();

        $evaluationsBody = $this->evaluationsBody();
        $array = compact('all_courses', 'session_array', 'status', 'delivery_methods', 'post_type');
        $evaluationsBody = array_merge($evaluationsBody, $array);


        return Active::Index($evaluationsBody);
    }

    public function search(){
        // if(request()->course_id!=-1) {
            $evaluationsBody = $this->evaluationsBody();
            $evaluationsBody = array_merge($evaluationsBody, [
                'post_type'=>request()->post_type??'evaluation',
            ]);
            return view('training.evaluations.table', $evaluationsBody);
        // }else{
        //     return '<div style="direction: ltr;width: fit-content;" class="alert alert-warning alert-dismissible" role="alert">
        //         <div><strong>Warning,</strong> Select Course Name & Session</div>
        //         <button type="button" class="close" data-dismiss="alert">
        //             <span aria-hidden="true">&times;</span>
        //         </button>
        //     </div>';
        // }
    }


    public function sending(){

        // if(!request()->has('check')){
        //     return back();
        // }
        $delay = 0;
        if((request()->has('check'))){

        // if((request()->has('check')) && (request()->has('collectortoken') && !is_null(request()->collectortoken))){

            Cart::whereIn('id', request()->check)
                ->whereNull('trashed_status')
                ->whereNull('deleted_at')
                ->orderBy('id')
                ->with(['userId', 'course', 'session', 'trainingOption'])
            ->chunk(1, function($carts) use(&$delay){

                $contacts = [];
                foreach($carts as $cart){
                    array_push($contacts, [
                        'email'=>$cart->userId->email??null,
                        'fName'=>$cart->userId->trans_name??null,
                        'mobile'=>$cart->userId->mobile??null,
                        'Course'=>$cart->course->trans_title??null,
                        'Session'=>$cart->session->session_date_from??null,
                        'DeliveryMethode'=>$cart->trainingOption->constant->trans_name??null,
                        'category'=>$cart->course->postMorph->constant->trans_name??null,
                    ]);

                    Cart::where('id', $cart->id)->update([
                        'evaluation_sent_at'=>now(),
                    ]);
                }

                // $this->SurveyAPI1(request()->collectortoken, json_encode($contacts));

                // $collectortoken = request()->collectortoken;  // $cart->trainingOption->constant->xero_code
                // // print_r('xxxxxxxxxxxxxxxxxxx ');
                // print_r(request()->collectortoken);

                $collectortoken = $cart->session->evaluation_api_code;
                // $collectortoken = $cart->trainingOption->evaluation_api_code;
                // print_r($collectortoken);
                // print_r('==');
                // $collectortoken = request()->collectortoken;
                $job = (new SurveyJob(json_encode($contacts), $collectortoken))
                        ->delay(\Carbon\Carbon::now()->addSeconds($delay));
                dispatch($job);
                $delay += 3;

            });
            return 'success';
        }else{
            return 'fail';
        }
    }

    // ================= private functions =============================
    private function GetAllCourses(){
        $all_courses = Course::has('trainingOptions.sessions')
            ->where('active',1)
            ->with('trainingOptions.sessions')
            ->orderBy('order')
            ->get();
        return $all_courses;
    }

    private function evaluationsBody(){
        $exam_simulator_courses = $this->exam_simulator_courses;
        $collectortoken = null;

        $trash = GetTrash();
        $carts = Cart::whereNotNull('id')
                ->whereNull('trashed_status')
                ->whereNull('deleted_at')
                ->whereHas('userId', function (Builder $query) {
                    if(request()->has('user_search') && !is_null(request()->user_search)){
                        $query->where('name', 'like', '%'.request()->user_search.'%')
                            ->orWhere('email', 'like', '%'.request()->user_search.'%')
                            ->orWhere('mobile', 'like', '%'.request()->user_search.'%')
                            ->orWhere('invoice_number', 'like', '%'.request()->user_search.'%')
                        ;
                    }
                });

        if(request()->course_id!=-1) {
            $carts = $carts->where('course_id', request()->course_id);
        }

        if(request()->session_id!=-1) {
            $carts = $carts->where('session_id', request()->session_id);

            $session = Session::find(request()->session_id);
            $collectortoken = $session->evaluation_api_code??null;
            // $collectortoken = $session->trainingOption->evaluation_api_code??null;
        }

        if(request()->payment_status==-1) {
            $carts = $carts->whereIn('payment_status', [68, 315, 317, 332, 376]);
        }else{
            $carts = $carts->where('payment_status', request()->payment_status);
            // $payment_status = request()->payment_status;
            // if($payment_status == 68){
            //     $carts = $carts->whereIn('payment_status', [68,376]);
            // }elseif($payment_status == 63){
            //     $carts = $carts->whereIn('payment_status', [63,377]);
            // }else{
            //     $carts = $carts->where('payment_status', request()->payment_status);
            // }
        }

        // if(request()->payment_status!=-1) {
        //     if(request()->payment_status==332){
        //         $carts = $carts->doesntHave('payment');
        //     }
        //     else{
        //         $carts = $carts->whereHas('payment', function (Builder $query) {
        //             // $query->where('payment_status', request()->payment_status);
        //             $payment_status = request()->payment_status;
        //             if($payment_status == 68){
        //                 $query->whereIn('payment_status', [68,376]);
        //             }elseif($payment_status == 63){
        //                 $query->whereIn('payment_status', [63,377]);
        //             }else{
        //                 $query->where('payment_status', request()->payment_status);
        //             }

        //         });
        //     }
        // }else{

        //     // Free and paid or PO or employee
        //     $carts = $carts->where(function($query){
        //         $query->whereNull('trashed_status')
        //               ->whereNull('deleted_at')
        //               ->doesntHave('payment')
        //               ->orWhere('total_after_vat', 0)
        //               ->orWhereHas('payment', function (Builder $query1) {
        //                 $query1->whereIn('payment_status', [68, 315, 317, 332, 376]);
        //               });
        //     });

        // }

        if(request()->training_option_id!=-1) {
            $carts = $carts->whereHas('trainingOption', function (Builder $query) {
                $query->where('constant_id', request()->training_option_id);
            });
        }

        if(!is_null(request()->date_from)) {
            $carts = $carts->whereDate('registered_at', '>=', request()->date_from);
        }
        if(!is_null(request()->date_to)) {
            $carts = $carts->whereDate('registered_at', '<=', request()->date_to);
        }

        $carts = $carts->with(['course:id,title', 'payment']);
        $count = $carts->count();
        $carts = $carts->page();

        return compact('carts', 'count', 'trash', 'exam_simulator_courses', 'collectortoken');
    }


    // private function SurveyAPI1($collectortoken, $contacts){

    //     $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //     CURLOPT_URL => 'https://az2.survey2connect.com/v1/send/multi/surveylink',
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 30,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'POST',
    //     CURLOPT_POSTFIELDS =>'{
    //         "apikey": "NWZiYjg5YzBiNWY2ZWUwYjVjYzBkYzgzLTQ5OWI2N2Uw",
    //         "collectortoken": "'.$collectortoken.'",
    //         "medium": "2",
    //         "type": 0,
    //         "contacts":'.$contacts.'
    //     }
    //     ',
    //     CURLOPT_HTTPHEADER => array(
    //         'Content-Type: application/json'
    //     ),
    //     ));


    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);

    //     curl_close($curl);
    //     if ($err) {
    //         echo "cURL Error #:" . $err;
    //     } else {
    //         // echo $response;
    //     }
    // }

}
