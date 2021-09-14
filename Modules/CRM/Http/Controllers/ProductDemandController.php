<?php

namespace Modules\CRM\Http\Controllers;

use App\Constant;
use App\Helpers\Models\Training\SessionHelper;
use App\Http\Controllers\Front\Education\CartController;
use App\Mail\InvoiceMaster;
use App\Mail\PaymentReceiptMaster;
use App\Models\Admin\Note;
use App\Models\Training\Course;
use App\Models\Training\Session;
use App\Models\Training\TrainingOption;
// use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Models\Training\Cart;
use App\Models\Training\CartFeature;
use App\Models\Training\CartMaster;
use App\Models\Training\Payment;
use App\Models\Training\TrainingOptionFeature;
use App\User;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Checkout\CheckoutApi;
use Checkout\Library\Exceptions\CheckoutHttpException;

class ProductDemandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // $comments = Cart::find(3827);
        return view('crm::index');
    }

    public function show($cartMasterId)
    {
        $DateTimeNow = DateTimeNow();
        // dump($DateTimeNow);

        $cartMaster = CartMaster::with(['rfpGroup', 'rfpGroup.organization', 'userId', 'userId.countries'
        , 'rfpGroup.session.trainingOption.course', 'rfpGroup.userId', 'payment', 'coin', 'paymentStatus', 'notes.user', 'notes'=>function($query){ $query->orderBy('id', 'DESC'); }
        , 'carts'=>function($query)use($DateTimeNow){
            $query->whereNull('trashed_status')
                  ->whereNull('deleted_at')
                  ->with(['coin','trainingOption.sessions'=>function($query)use($DateTimeNow){
                        // $query->orderBy('date_from', 'desc');
                    //   $query->whereDate('date_from' ,'>=', $DateTimeNow);
                    //   $query->whereDate('date_to', '>', $DateTimeNow);
                  }, 'trainingOption.type', 'trainingOption.TrainingOptionFeature.feature', 'course:id,title,partner_id,certificate_type_id', 'session'=>function($query){ $query->withTrashed(); }, 'cartFeatures.trainingOptionFeature.feature', 'userId', 'course']);
        }])->findOrFail($cartMasterId);

        // dd($cartMaster);

        // if(!is_null($cartMaster))
        // {
        //     $master_id__field = 'carts.master_id';
        //     if($cartMaster->type_id==372){  // Group
        //         request()->post_type = 'group_invoices';
        //         $master_id__field = 'carts.group_invoice_id';
        //         $cartMaster = CartMaster::with(['rfpGroup', 'rfpGroup.organization', 'userId', 'userId.countries', 'rfpGroup.session.trainingOption.course', 'rfpGroup.userId', 'payment', 'coin', 'paymentStatus', 'notes.user', 'notes'=>function($query){ $query->orderBy('id', 'DESC'); }, 'carts'=>function($query)use($DateTimeNow){
        //             $query->whereNull('trashed_status')
        //                   ->whereNull('deleted_at')
        //                   ->with(['coin','trainingOption.sessions'=>function($query)use($DateTimeNow){
        //                     // $query->orderBy('date_from', 'desc');
        //                 //   $query->whereDate('date_from' ,'>=', $DateTimeNow);
        //                 //   $query->whereDate('date_to', '>', $DateTimeNow);
        //               }, 'trainingOption.type', 'trainingOption.TrainingOptionFeature.feature', 'course:id,title,partner_id,certificate_type_id', 'session'=>function($query){ $query->withTrashed(); }, 'cartFeatures.trainingOptionFeature.feature', 'userId', 'course']);
        //         }])->findOrFail($cartMasterId);
        //     }
        // }

        $payment = Payment::where('master_id', $cartMasterId)
                            ->whereNull('deleted_at')
                            ->select(DB::raw('sum(paid_in) as paid_in, sum(paid_out) as paid_out'))
                            ->groupBy('master_id')
                            ->first();

        $cartMasterId = $cartMaster->id??null;

        $paymentDetails = null;
        if($cartMaster->payment_rep_id){
            $paymentDetails = $this->getPaymentDetails($cartMasterId);
        }
        // dd($paymentDetails);

        $countries = Constant::where('post_type','countries')->get();
        $p_status = Constant::where('parent_id', 62)->where('id', '!=', 375)->get();

        return view('crm::products_demand.show', compact('cartMaster', 'payment', 'cartMasterId', 'countries', 'p_status', 'paymentDetails'));
    }

    public function paidTotals($cartMasterId){
        $summation_cart = Cart::where('master_id', $cartMasterId)
                                ->whereNull('trashed_status')
                                ->whereNull('deleted_at')
                                ->whereIn('payment_status', [68, 376]) //paid 315, 317,
                                ->select(DB::raw('sum(total) as total
                                , sum(vat_value) as vat_value
                                , (sum(total_after_vat)-sum(refund_value_after_vat)) as total_after_vat'))
                                ->groupBy('master_id')
                                ->first();

        // $summation_cart_ret = Cart::where('master_id', $cartMasterId)
        //                         ->whereNull('trashed_status')
        //                         ->whereNull('deleted_at')
        //                         ->where('payment_status', 377) //Transferred To
        //                         ->select(DB::raw('sum(retrieved_value) as retrieved_value,
        //                         max(vat),
        //                         sum(total_after_vat) as total_after_vat'))
        //                         ->groupBy('master_id')
        //                         ->first();

        $diff = 0;
        $diff_vat = 0;
        $diff_total = 0;

        // if($summation_cart_ret){
        //     if($summation_cart_ret->retrieved_value != $summation_cart_ret->total_after_vat){
        //         $diff = ($summation_cart_ret->total_after_vat??0) - ($summation_cart_ret->retrieved_value??0);
        //         $diff_vat = $diff * ($summation_cart_ret->vat / 100);
        //         $diff = $diff - $diff_vat;
        //         $diff_total = $diff + $diff_vat;
        //     }
        // }

        $total = ($summation_cart->total??0) + $diff;
        $vat_value = ($summation_cart->vat_value??0) + $diff_vat;
        $total_after_vat = ($summation_cart->total_after_vat??0) + $diff_total;

        $summation_cart_refund = Cart::where('master_id', $cartMasterId)
                                    ->whereNull('trashed_status')
                                    ->whereNull('deleted_at')
                                    ->whereIn('payment_status', [68, 376, 316]) //Refund,
                                    ->select(DB::raw('sum(refund_value_after_vat) as refund_value_after_vat'))
                                    ->groupBy('master_id')
                                    ->first();
        $total_after_vat_refund = $summation_cart_refund->refund_value_after_vat??0;

        return compact('total', 'vat_value', 'total_after_vat', 'total_after_vat_refund');
    }

    public function summations($cartMasterId, $payment_status, $cart_id){

        if($cartMasterId){

            CartMaster::UpdateSummation($cartMasterId);

            $cartMaster = CartMaster::where('id', $cartMasterId)->
                                      select('total', 'vat_value', 'total_after_vat', 'payment_status', 'user_id', 'transaction_id', 'coin_id', 'coin_price')
                                      ->first();

            $paidTotals = $this->paidTotals($cartMasterId);
            $total = $paidTotals['total'];
            $vat_value = $paidTotals['vat_value'];
            $total_after_vat = $paidTotals['total_after_vat'];
            $total_after_vat_refund = $paidTotals['total_after_vat_refund'];

            // echo 'sssssssssssssss'.$total_after_vat_refund;

            // (68,376); // Completed , Transferred From
            if($total_after_vat > 0){

                // Create or Update Payment
                $update_payment = Payment::updateOrCreate([
                    'master_id'=>$cartMasterId,
                    'user_id' => $cartMaster->user_id??auth()->id(),
                    ],[
                        'paid_in'=>NumberFormat($total_after_vat),
                        'transaction_id'=>$cartMaster->transaction_id,
                        'payment_status'=>$payment_status,
                        // 'description'=>$description,
                        // 'code'=>$total_after_vat_master->code,
                        'coin_id'=>$cartMaster->coin_id,
                        'coin_price'=>$cartMaster->coin_price,
                        'paid_at'=>DateTimeNow(),
                        'updated_by'=>auth()->user()->id??1,
                ]);

                Cart::updateOrCreate([
                    'master_id'=>$cartMasterId,
                ],[
                    'registered_at'=>DateTimeNow(),
                ]);
            }

            // $summation_cart_refund = Cart::where('master_id', $cartMasterId)
            //                         ->whereNull('trashed_status')
            //                         ->whereNull('deleted_at')
            //                         ->whereIn('payment_status', [316]) //Refund,
            //                         ->select(DB::raw('sum(total) as total
            //                         , sum(vat_value) as vat_value
            //                         , sum(total_after_vat) as total_after_vat'))
            //                         ->groupBy('master_id')
            //                         ->first();
            // return ('aaaaaaaaaa'.$total_after_vat_refund);
            if($total_after_vat_refund > 0){
                $update_payment = Payment::updateOrCreate([
                        'master_id'=>$cartMasterId,
                        'user_id' => $cartMaster->user_id??auth()->id(),
                    ],[
                        'paid_out'=>NumberFormat($total_after_vat_refund),
                        'transaction_id'=>$cartMaster->transaction_id,
                        'payment_status'=>$payment_status,
                        'coin_id'=>$cartMaster->coin_id,
                        'coin_price'=>$cartMaster->coin_price,
                        'paid_at'=>DateTimeNow(),
                        'updated_by'=>auth()->user()->id??1,
                ]);

            }

            $summation_cart_no_payment = Cart::where('master_id', $cartMasterId)
                                    ->whereNull('trashed_status')
                                    ->whereNull('deleted_at')
                                    ->whereIn('payment_status', [63,332,315,317]) // Not completed, Free, Bakkah Employee, PO
                                    ->select(DB::raw('sum(total) as total
                                    , sum(vat_value) as vat_value
                                    , sum(total_after_vat) as total_after_vat'))
                                    ->groupBy('master_id')
                                    ->first();

            if($summation_cart_no_payment){
                $payment = Payment::where('master_id', $cartMasterId)
                                    ->whereIn('payment_status', [63,332,315,317])
                                    ->first();
                if($payment){
                    Payment::where('id', $payment->id)->forceDelete();
                    // Payment::findOrFail($payment->id)->delete();
                }
            }

            // // // ->whereIn('payment_status', [68, 315, 317, 332, 376])
            // // $total_after_vat_master = CartMaster::where('id', $cartMasterId)->select('total', 'vat_value', 'total_after_vat', 'payment_status', 'user_id', 'transaction_id', 'coin_id', 'coin_price')->first();


            // $no_payment_row = array(63,332,315,317); // Not completed, Free, Bakkah Employee, PO

            // // if ($total_after_vat_master->payment_status==63 || $total_after_vat_master->payment_status==332){
            // if (in_array($payment_status, $no_payment_row)){
            //     $payment = Payment::where('master_id', $cartMasterId)->first();
            //     if($payment){
            //         Payment::where('id', $payment->id)->forceDelete();
            //         // Payment::findOrFail($payment->id)->delete();
            //     }
            // }else{

            //     $payment_row = array(68,376); // Completed , Transferred From
            //     if (in_array($payment_status, $payment_row)){

            //         // Create or Update Payment
            //         $update_payment = Payment::updateOrCreate([
            //             'master_id'=>$cartMasterId,
            //             'user_id' => $total_after_vat_master->user_id??auth()->id(),
            //             ],[
            //                 'paid_in'=>NumberFormat($total_after_vat),
            //                 // 'paid_in'=>NumberFormat($total_after_vat_master->total_after_vat),
            //                 'transaction_id'=>$total_after_vat_master->transaction_id,
            //                 'payment_status'=>$payment_status,
            //                 // 'description'=>$description,
            //                 // 'code'=>$total_after_vat_master->code,
            //                 'coin_id'=>$total_after_vat_master->coin_id,
            //                 'coin_price'=>$total_after_vat_master->coin_price,
            //                 'paid_at'=>DateTimeNow(),
            //                 'updated_by'=>auth()->user()->id??1,
            //         ]);

            //         Cart::updateOrCreate([
            //             'master_id'=>$cartMasterId,
            //         ],[
            //             'registered_at'=>DateTimeNow(),
            //         ]);
            //     }elseif($payment_status = 316){ // Refund
            //         $cart = Cart::where('id', $cart_id)->select('total', 'vat_value', 'total_after_vat')->first();

            //         $update_payment = Payment::updateOrCreate([
            //             'master_id'=>$cartMasterId,
            //             'user_id' => $total_after_vat_master->user_id??auth()->id(),
            //             'payment_status' => 316,
            //             ],[
            //                 'paid_in'=>0,
            //                 'paid_out'=>NumberFormat($cart->total_after_vat),
            //                 // 'paid_in'=>NumberFormat($total_after_vat_master->total_after_vat),
            //                 // 'transaction_id'=>$total_after_vat_master->transaction_id,
            //                 // 'payment_status'=>$payment_status,
            //                 'description'=>'Refunded from cart#: '.$cart->id,
            //                 // 'code'=>$total_after_vat_master->code,
            //                 'coin_id'=>$total_after_vat_master->coin_id,
            //                 'coin_price'=>$total_after_vat_master->coin_price,
            //                 'paid_at'=>DateTimeNow(),
            //                 'updated_by'=>auth()->user()->id??1,
            //         ]);
            //     }
            // }

            // return $total_after_vat_master;

            $cartMaster_total = $cartMaster->total??0;
            $cartMaster_vat_value = $cartMaster->vat_value??0;
            $cartMaster_total_after_vat = $cartMaster->total_after_vat??0;
            $payment_paid_in = $paidTotals['total_after_vat']??0;
            $payment_paid_out = $paidTotals['total_after_vat_refund']??0;

            return compact('cartMaster_total', 'cartMaster_vat_value', 'cartMaster_total_after_vat', 'payment_paid_in', 'payment_paid_out');

            // return $cartMaster;

        }else{
            return 0;
        }
    }

    public function addNote($cartMasterId=null, $comment=null, Request $request=null){

        $cartMasterId = $cartMasterId??request()->cartMasterId;
        $comment = $comment??request()->comment;

        $cartMaster = cartMaster::findOrFail($cartMasterId);
        $cartMaster->notes()->create([
            'user_id' => auth()->user()->id,
            'comment' => $comment,
        ]);

    }

    public function addCartFeature(Request $request){
        // return $request;

        $cartMasterId = request()->cartMasterId;
        $cart_id = request()->cart_id;
        $feature_id = request()->feature_id;
        $price = request()->price;
        $payment_status = request()->payment_status;
        $event = request()->event;

        $TrainingOptionFeature = TrainingOptionFeature::where('id', $feature_id)->first();
        $feature_title = $TrainingOptionFeature->feature->en_title;

        $comment = '';
        if($event){
            $comment = '('.$feature_title.') was added for the cart ID '.$cart_id;
        }else{
            $comment = '('.$feature_title.') was removed for the cart ID '.$cart_id;
        }

        if(!empty($comment)) {
            $this->addNote($cartMasterId, $comment);
        }

        // $comments = $cartMaster->notes()->create([
        //     'user_id' => auth()->user()->id,
        //     'comment' => $comment,
        // ]);

        // $coin_id = request()->coin_id;
        // $event = request()->event;
        // request()->feature_id = request()->training_option_feature_id;

        $CartController = new CartController();
        $CartController->addCartFeature();

        // $total_after_vat_master = $this->summations($cartMasterId, $payment_status, $cart_id);

        $summations = $this->summations($cartMasterId, $payment_status, $cart_id);
        $cartMaster_total = $summations['cartMaster_total'];
        $cartMaster_vat_value = $summations['cartMaster_vat_value'];
        $cartMaster_total_after_vat = $summations['cartMaster_total_after_vat'];
        $payment_paid_in = $summations['payment_paid_in'];
        $payment_paid_out = $summations['payment_paid_out'];

        if($summations){
            $cart = Cart::where('id', $cart_id)->select('total', 'vat_value', 'total_after_vat')->first();

            return response()->json([
                'msg'=>'success',
                'status'=>'true',
                'cart_total'=>$cart->total,
                'cart_vat_value'=>$cart->vat_value,
                'cart_total_after_vat'=>$cart->total_after_vat,
                'cartMaster_total'=>$cartMaster_total,
                'cartMaster_vat_value'=>$cartMaster_vat_value,
                'cartMaster_total_after_vat'=>$cartMaster_total_after_vat,
                'payment_paid_in'=>$payment_paid_in,
                'payment_paid_out'=>$payment_paid_out,
                // 'total_after_vat_master'=>$total_after_vat_master,
            ]);
        }
        return response()->json([
            'msg'=>'error',
            'errors'=>'No master record!',
        ]);
    }

    public function changeCartPaymentStatus_forCalc(Request $request){
        $cartMasterId = request()->cartMasterId;
        $cart_id = request()->cart_id;
        $payment_status = request()->payment_status;
        // $coin_id = request()->coin_id;
        // $event = request()->event;

        if($cartMasterId){

            $paidTotals = $this->paidTotals($cartMasterId);
            $total = $paidTotals['total'];
            $vat_value = $paidTotals['vat_value'];
            $total_after_vat = $paidTotals['total_after_vat'];
            $total_after_vat_refund = $paidTotals['total_after_vat_refund'];

            $cart = Cart::where('id', $cart_id)->select('total', 'vat_value', 'total_after_vat', 'payment_status')->first();

            return response()->json([
                'msg'=>'success',
                'status'=>'true',
                'cart_payment_status'=>$cart->payment_status,
                // 'cartMaster_total'=>$total,
                // 'cartMaster_vat_value'=>$vat_value,
                // 'cartMaster_total_after_vat'=>$total_after_vat,
                'payment_paid_in'=>$total_after_vat??0,
                'payment_paid_out'=>$total_after_vat_refund??0,
                // 'total_after_vat_master'=>$total_after_vat_master,
            ]);

        }
        return response()->json([
            'msg'=>'error',
            'errors'=>'No master record!',
        ]);
    }

    public function updateData(Request $request)
    {
        // return $request;
        // return $request->carts[0]['retrieved_code'];

        $cartMasterId = $request->cartMasterId??null;
        $validator = Validator::make(request()->all(), [
            'email' =>'email',
            'mobile'=>'required|numeric',
            'en_name'=>'required',
            // 'username_lms'=>'max:5',//required
            // 'password_lms'=>'max:5',//required
            // 'invoice_number'=>'max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'msg'=>'error',
                'errors'=>$validator->errors(),
            ]);
        }

        if($request->has('cartMasterId')){

            $name = json_encode([
                'en'=>$request->en_name,
                'ar'=>$request->ar_name??$request->en_name
            ], JSON_UNESCAPED_UNICODE);

            $cartMaster = CartMaster::find($cartMasterId);

            $comment = '';
            if($cartMaster->userId->name != $name){
                $comment = 'Name of candidates was updated and the old data was En('.json_decode($cartMaster->userId->name)->en.') and Ar('.json_decode($cartMaster->userId->name)->ar.')';
            }
            if($cartMaster->userId->mobile != $request->mobile){
                $comment .= ' Mobile of candidates was updated and the old data was ('.$cartMaster->userId->mobile.')';
            }
            if($cartMaster->userId->job_title != $request->job_title){
                $comment .= " Job title of candidates was updated and the old data was (".$cartMaster->userId->job_title.')';
            }
            if($cartMaster->userId->company != $request->company){
                $comment .= ' Company of candidates was updated and the old data was ('.$cartMaster->userId->company.')';
            }
            if($cartMaster->userId->country != $request->en_country){
                $comment .= ' Country of candidates was updated and the old data was ('.$cartMaster->userId->country.')';
            }
            if($cartMaster->userId->username_lms != $request->username_lms){
                $comment .= ' Username_lms of candidates was updated and the old data was ('.$cartMaster->userId->username_lms.')';
            }
            if($cartMaster->userId->password_lms != $request->password_lms){
                $comment .= ' Password_lms of candidates was updated and the old data was ('.$cartMaster->userId->password_lms.')';
            }
            if($cartMaster->userId->retrieved_code != $request->retrieved_code){
                $comment .= ' Retrieved code of candidates was updated and the old data was ('.$cartMaster->userId->retrieved_code.')';
            }
            if($cartMaster->userId->balance != $request->balance){
                $comment .= ' Balance of candidates was updated and the old data was ('.$cartMaster->userId->balance.')';
            }

            if(!empty($comment)) {
                $this->addNote($cartMasterId, $comment);
            }

            $cartMaster->userId()->update([
                'name'=>$name,
                'mobile'=>$request->mobile,
                'username_lms'=>$request->username_lms,
                'password_lms'=>$request->password_lms,
                'job_title'=>$request->job_title,
                'company'=>$request->company,
                'country_id'=>$request->country_id,
                'country'=>$request->en_country,
                'retrieved_code'=>$request->retrieved_code??null,
                'balance'=>$request->balance??0,
            ]);

            $payment_status = $request->payment_status;

            $comment = '';
            if($cartMaster->payment_status != $payment_status){
                $comment = ' Payment status of Master was updated and the old data was ('.json_decode($cartMaster->paymentStatus->name)->en.')';
            }
            if($cartMaster->total != $request->total){
                $comment .= ' SubTotal of Master was updated and the old data was ('.$cartMaster->total.')';
            }
            if($cartMaster->vat_value != $request->vat_value){
                $comment .= ' Vat value of Master was updated and the old data was ('.$cartMaster->vat_value.')';
            }
            if($cartMaster->total_after_vat != $request->total_after_vat){
                $comment .= ' Net Total of Master was updated and the old data was ('.$cartMaster->total_after_vat.')';
            }

            // aaaaaaaaaaaaaaaaaaa

            if(!empty($comment)) {
                $this->addNote($cartMasterId, $comment);
            }

            // CartMaster::where('id', $cartMasterId)->update([
            $cartMaster->update([
                'payment_status'=>$payment_status,
                'reminder_date'=>$request->reminder_date,
            ]);

            // return $request->carts
            foreach($request->carts as $cart)
            {

                $refund_value_before_vat = ($cart['refund_value_after_vat'] * 100) / (100 + $cart['vat']);
                $refund_value_vat = ($refund_value_before_vat * $cart['vat']) / 100;

                Cart::where('id', $cart['id'])->update([
                    'payment_status' => $cart['payment_status'],
                    'session_id' => $cart['session_id'],
                    'training_option_id' => $cart['training_option_id'],
                    'coin_id' => $cart['coin_id'],
                    'price' => NumberFormat($cart['price']),
                    'discount_id' => $cart['discount_id'],
                    'discount' => NumberFormat($cart['discount']),
                    'discount_value' => NumberFormat($cart['discount_value']),
                    // 'promo_code' => $cart['code'],
                    'retrieved_code' => $cart['retrieved_code']??null,
                    'retrieved_value' => $cart['retrieved_value']??0,
                    'exam_price' => NumberFormat($cart['exam_price']),
                    'take2_price' => $cart['take2_price'],
                    'exam_simulation_price' => NumberFormat($cart['exam_simulation_price']),
                    'total' => NumberFormat($cart['total']),
                    'vat' => NumberFormat($cart['vat']),
                    'vat_value' => NumberFormat($cart['vat_value']),
                    'total_after_vat' => NumberFormat($cart['total_after_vat']),
                    'refund_value_before_vat' => NumberFormat($refund_value_before_vat),
                    'refund_value_vat' => NumberFormat($refund_value_vat),
                    'refund_value_after_vat' => NumberFormat($cart['refund_value_after_vat']),
                    'updated_by' => auth()->user()->id??1,
                    // 'registered_at'=>DateTimeNow(),
                ]);
                // return $is_updated;
            }

            // $total_after_vat_master = $this->summations($cartMasterId, $payment_status, 0);

            $summations = $this->summations($cartMasterId, $payment_status, 0);
            $cartMaster_total = $summations['cartMaster_total'];
            $cartMaster_vat_value = $summations['cartMaster_vat_value'];
            $cartMaster_total_after_vat = $summations['cartMaster_total_after_vat'];
            $payment_paid_in = $summations['payment_paid_in'];
            $payment_paid_out = $summations['payment_paid_out'];

            if($summations){
                return response()->json([
                    'msg'=>'success',
                    'status'=>'true',
                    'cartMaster_total'=>$cartMaster_total,
                    'cartMaster_vat_value'=>$cartMaster_vat_value,
                    'cartMaster_total_after_vat'=>$cartMaster_total_after_vat,
                    'payment_paid_in'=>$payment_paid_in,
                    'payment_paid_out'=>$payment_paid_out,
                    // 'total_after_vat_master'=>$total_after_vat_master,
                ]);
                // return response()->json(['msg'=>'success']);
            }else{
                return response()->json([
                    'msg'=>'error',
                    'errors'=>'There is something error!',
                ]);
            }

        }
        return response()->json([
            'msg'=>'error',
            'errors'=>'No master record!',
        ]);
    }

    public function SessionsDetailsJson(Request $request){
    // public function SessionsDetailsJson($session_id, $coin_id){

        $cartMasterId = $request->cartMasterId;
        $session_id = $request->session_id;
        $cart_id = $request->cart_id;

        $cart = Cart::find($cart_id);
        $cartMaster = CartMaster::where('id', $cartMasterId)->first();
        $coin_id = $cartMaster->coin_id;

        if($cart->session_id==$session_id)
        {
            $sub_total = $cart->total;
            $vat = $cart->vat;
            $vat_value = $cart->vat_value;
            $total_after_vat = $cart->total_after_vat;

            return response()->json([
                'msg'=>'success',
                'status'=>'true',
                'training_option_id' => $cart->training_option_id,
                'price' => NumberFormat($cart->price),
                'discount_id' => $cart->discount_id,
                'discount' => NumberFormat($cart->discount),
                'discount_value' => NumberFormat($cart->discount_value),
                // 'exam_price' => NumberFormat($session_exam_price),//
                // 'take2_price' => NumberFormat($cart->take2_price),//
                // 'exam_simulation_price' => NumberFormat($cart->exam_simulation_price),//
                'total' => NumberFormat($sub_total),
                'vat' => NumberFormat($vat),
                'vat_value' => NumberFormat($vat_value),
                'total_after_vat' => NumberFormat($total_after_vat),
                // 'cartMaster_total'=>$total_after_vat_master->total,
                // 'cartMaster_vat_value'=>$total_after_vat_master->vat_value,
                // 'cartMaster_total_after_vat'=>$total_after_vat_master->total_after_vat,
            ]);
        }

        if(!is_null($session_id) && $session_id != -1){

            request()->training_option_id = -1;
            $SessionHelper = new SessionHelper($coin_id);
            $session = $SessionHelper->TrainingOption()->where('session_id', $session_id)->first();

            if(!is_null($session))
            {
                request()->training_option_id = $session->training_option_id;
                $SessionHelper->SetCourse($session);

                // echo $request->exam_price.'<br>';
                // echo $request->take2_price.'<br>';
                // echo $request->exam_simulation_price.'<br>';
                // echo $session->exam_simulation_price.'<br>';
                $exam_simulation_price = ($request->exam_simulation_price!=0) ? $request->exam_simulation_price : $session->exam_simulation_price;
                // $take2_price = ($request->take2_price!=0) ? $request->take2_price : $session->take2_price;
                $take2_price = $request->take2_price;

                $session_exam_price = $SessionHelper->ExamPrice();
                // $ExamIsIncluded = $SessionHelper->ExamIsIncluded();
                // if($ExamIsIncluded!=1){
                //     $session_exam_price = $request->exam_price;
                // }
                $sub_total = $SessionHelper->PriceWithExamPrice();
                $sub_total += $exam_simulation_price;
                $sub_total += $take2_price;

                $discount_value = $SessionHelper->DiscountValue();
                $vat = $SessionHelper->VAT();

                $_vat = ($vat / 100);
                $vat__exam_simulation_price = $exam_simulation_price * $_vat;
                $vat__take2_price = $take2_price * $_vat;

                /////////////////////
                $vat_value = $SessionHelper->VATFortPriceWithExamPrice();
                $vat_value += $vat__exam_simulation_price;
                /////////////////////
                $total_after_vat = $SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT();
                $total_after_vat += $exam_simulation_price;
                $total_after_vat += $vat__exam_simulation_price;
                $total_after_vat += $take2_price;
                $total_after_vat += $vat__take2_price;

                return response()->json([
                    'msg'=>'success',
                    'status'=>'true',
                    'training_option_id' => $session->training_option_id,
                    'price' => NumberFormat($session->session_price),
                    'discount_id' => $session->discount_id,
                    'discount' => NumberFormat($session->discount_value),
                    'discount_value' => NumberFormat($discount_value),
                    // 'promo_code' => $session->code,
                    'exam_price' => NumberFormat($session_exam_price),
                    // 'take2_price' => NumberFormat($session->take2_price),
                    'take2_price' => NumberFormat($take2_price),
                    'exam_simulation_price' => NumberFormat($exam_simulation_price),
                    'total' => NumberFormat($sub_total),
                    'vat' => NumberFormat($vat),
                    'vat_value' => NumberFormat($vat_value),
                    'total_after_vat' => NumberFormat($total_after_vat),
                    // 'cartMaster_total'=>$total_after_vat_master->total,
                    // 'cartMaster_vat_value'=>$total_after_vat_master->vat_value,
                    // 'cartMaster_total_after_vat'=>$total_after_vat_master->total_after_vat,
                ]);
            }
        }
        return response()->json([
            'msg'=>'error',
            'errors'=>'No session!',
        ]);
    }

    public function SendEmailMaster(Request $request){
        // return $request;
        $cartMasterId = $request->cartMasterId??null;
        $user = $request->user;

        Mail::to($user['email'])->send(new InvoiceMaster($cartMasterId));
        Mail::to($user['email'])
            ->cc(['accounting@bakkah.net.sa'])
            ->send(new PaymentReceiptMaster($cartMasterId));//, 'mkassem@bakkah.net.sa'

            // check for failures
        if (Mail::failures()) {
            // return response showing failed emails
            return response()->json([
                'msg'=>'error',
                'errors'=>'Email doesn\'t send, there is something error!',
            ]);
        }else{
            CartMaster::where('id', $cartMasterId)->update(['invoice_sent_date'=>DateTimeNow()]);
        }

        // if($cartMasterId && $user){
        return response()->json([
            'msg'=>'success',
            'status'=>'true',
        ]);
            // return response()->json(['msg'=>'success']);
        // }else{
        //     return response()->json([
        //         'msg'=>'error',
        //         'errors'=>'There is something error!',
        //     ]);
        // }
    }

    public function create()
    {
        return view('crm::create');
    }

    public function store(Request $request)
    {
        //
    }

    // public function show($id)
    // {
    //     $cartMaster = CartMaster::with(['payment', 'notes.user', 'userId.countries', 'paymentStatus', 'carts'=>function($query){
    //             $query->with(['trainingOption', 'trainingOption.type', 'course:id,title,partner_id,certificate_type_id',
    //             'course'=>function($query){
    //             }, 'session'=>function($query){
    //             }, 'cartFeatures.trainingOptionFeature.feature'=>function($query){
    //                 // $query->select('title');
    //             }]);
    //     }])
    //     ->findOrFail($id);

    //     // dd($cartMaster);

    //     $countries = Constant::where('post_type','countries')->get();
    //     // $courses = Course::orderBy('order')->get();
    //     $trainingOptions = TrainingOption::with(['type', 'course'])->get();
    //     $p_status = Constant::where('parent_id', 62)->get();
    //     //

    //     // $sessions = Session::where('training_option_id', $cartMasters->cart->training_option_id)->get();

    //     // $sessions = Session::whereHas('trainingOption.course', function (Builder $query){
    //     //     $query->where('training_option_id', $cart->training_option_id);
    //     // })->get();
    //     // $session_array = Session::GetJson($sessions);
    //     // 'courses', 'status', 'session_array', 'delivery_methods'
    //     return view('crm::products_demand.show', compact('trainingOptions', 'cartMaster', 'countries', 'p_status'));//histories
    // }

    public function getNotes($cartMasterId)
    {
        $comment = cartMaster::with(['notes', 'notes.user'])->orderBy('id', 'DESC')->findOrFail($cartMasterId);
        return response()->json($comment);
    }

    public function comment($id)
    {
        $cartMaster = cartMaster::findOrFail($id);
        $comments = $cartMaster->notes()->create([
            'user_id' => auth()->user()->id,
            'comment' => request()->comment
        ]);

        $comment = cartMaster::with(['notes'=>function($query)use($comments){
            $query->where('id', $comments->id);
        }, 'notes.user'])
        ->findOrFail($id);

        return response()->json($comment->notes);
    }

    public function deleteComments($ids) {
        Note::whereIn('id', request()->ids)->delete();
        return response()->json('success');
    }

    public function edit($id)
    {
        return view('crm::edit');
    }

    public function update()
    {
        // return request()->params['id'].'vvvvvvvvvvvvvvvvv';
        // return request()->params['cartMaster_edit']['en_name'];
        // foreach(request()->params['carts'] as $cart){
        //     return $cart['payment_status'];
        // }
        // return request()->all();
        $validator = Validator::make(request()->all(), [
            'email' => 'email',
            'mobile'=>'numeric|max:15',
            'username_lms'=>'',//required
            'invoice_number'=>'max:15|numeric',
        ]);

        if ($validator->fails()) {
            return 'Error in Validation!';
        }

        if(request()->params['id'] > 0){
        // if(request()->has('id')){
            // $cart_edit = json_decode(request()->params['cartMaster_edit']);
            $cart_edit = request()->params['cartMaster_edit'];

            $name = json_encode([
                'en'=>$cart_edit['en_name'],
                'ar'=>$cart_edit['ar_name']??$cart_edit['en_name']
            ], JSON_UNESCAPED_UNICODE);

            $cartMaster = CartMaster::find(request()->params['id']);
            $cartMaster->userId()->update([
                'name'=>$name,
                'mobile'=>$cart_edit['mobile'],
                'username_lms'=>$cart_edit['username_lms'],
                'password_lms'=>$cart_edit['password_lms'],
                'job_title'=>$cart_edit['job_title'],
                'company'=>$cart_edit['company'],
                'country_id'=>$cart_edit['country_id'],
                'country'=>$cart_edit['en_country'],
                // 'retrieved_code'=>$cart_edit['retrieved_code'],
                // 'balance'=>$cart_edit['balance'],
            ]);

            $cartMaster->update([
                'invoice_number'=>$cart_edit['invoice_number'],
                'payment_status'=>$cart_edit['payment_status'],
            ]);

            // ===== Work Here =========================
            // &&&&&&&&&&&&&&&&& Update carts &&&&&&&&&&&&&&&&&&7


            // $trainingOption = TrainingOption::find($cart_edit->training_option_id);
            // $cart->update([
            //     'course_id'=>$trainingOption->course_id,
            //     'training_option_id'=>$cart_edit->training_option_id,
            //     'session_id'=>$cart_edit->session_id,
            // ]);
        }
        // $cart_edit = json_decode(request()->cart_edit);
        // var_dump($cart_edit->en_name);
        return response()->json(['msg'=>'success']);
//         $cart->update([
//             'course_id'=>\request()->course_id,
//             'session_id'=>\request()->session_id,
//             'price'=>\request()->price,
//             'invoice_number'=>\request()->invoice_number,
//             'training_option_id'=>\request()->training,
//             'status_id' => \request()->status
//         ]);
//         return response()->json(['msg'=>'success']);
    }

    public function GetSessionInfoJson(){

    }

    public function destroy($id)
    {
        //
    }

    public function getPaymentDetails($cartMasterId)
    {
        $cartMaster = CartMaster::where('id', $cartMasterId)->select('payment_rep_id')->first();
        // dd($cartMaster);
        if($cartMaster) {
            $checkout = new CheckoutApi(env('CHECHOUT_SECRET_KEY'), false);
            // $checkout = new CheckoutApi("sk_test_8d7c9bd1-ed3b-44b4-a0a8-b2db38e17dde");
            // $threeDsSessionId = 'sid_y3oqhf46pyzuxjbcn2giaqnb44';
            $threeDsSessionId = $cartMaster->payment_rep_id??null;
            // dd($threeDsSessionId);
            try {

                $details = $checkout->payments()->details($threeDsSessionId);
            //  return $details->getSourceId();
                // dd($details);
                return $details;
                // return response()->json([
                //     'msg'=>'success',
                //     'status'=>'true',
                // ]);

            } catch (CheckoutHttpException $ex) {
                dd($ex);
                // return $ex->getErrors();
            }
        }

    }

    public function GetDeliver(){

        $delivery_methods = TrainingOption::where('course_id', \request()->course_id)->with('constant')->get();
//        dd($delivery_methods);
        return response()->json($delivery_methods);
    }
}
