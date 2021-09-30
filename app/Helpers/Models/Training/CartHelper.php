<?php

namespace App\Helpers\Models\Training;

use App\Models\Training\Bundle;
use App\Models\Training\Cart;
use App\Models\Training\CartFeature;
use App\Models\Training\CartMaster;
use App\Models\Training\Course;
use App\Models\Training\Session;
use App\Models\Training\TrainingOptionFeature;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\Eloquent\Builder;

class CartHelper {

    public function Query($hasPaginate=false, $xero=false){

        // https://fideloper.com/laravel-raw-queries
        // $someVariable = 1;
        // $results = DB::select( DB::raw("SELECT * FROM some_table WHERE some_col = :somevariable"), array(
        // 'somevariable' => $someVariable,
        // ));

        $where_cart_masters = "Where true";
        $where_cart_masters .= " and cart_masters.deleted_at is null";
        $where_cart_masters .= " and cart_masters.user_id is not null";

        if($xero){
            $where_cart_masters .= " and cart_masters.xero_prepayment is null";
            $where_cart_masters .= " and cart_masters.xero_prepayment_created_at is null";
            $where_cart_masters .= " and cart_masters.type_id = 374";
            $where_cart_masters .= " and cart_masters.wp_migrate is null";
            $where_cart_masters .= " and cart_masters.trashed_status is null";
        }


        // $master_id__field = 'master_id';
        $args = [];

        // &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& Start CartMaster &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
            // if(!is_null(request()->date_to)){
            if(request()->has('reminder_date') && !is_null(request()->reminder_date)){
                $reminder_date = request()->reminder_date;
                $where_cart_masters .= " and Date(cart_masters.reminder_date) = :reminder_date";
                $args = array_merge($args, ['reminder_date' => $reminder_date,]);
            }

            // request()->coin_id = 334;
            // if(request()->coin_id !=-1 ){
            if(request()->has('coin_id') && request()->coin_id!=-1){
                $coin_id = request()->coin_id;
                $where_cart_masters .= " and cart_masters.coin_id = :coin_id";
                $args = array_merge($args, ['coin_id' => $coin_id,]);
            }

            // request()->type_id = 374;
            // if(request()->type_id !=-1 ){
            if(request()->has('type_id') && request()->type_id!=-1){
                $type_id = request()->type_id;
                $where_cart_masters .= " and cart_masters.type_id = :type_id";
                $args = array_merge($args, ['type_id' => $type_id,]);

                // if($type_id==372){
                //     request()->post_type = 'group_invoices';
                //     $master_id__field = 'group_invoice_id';
                // }
            }

            // request()->payment_status = 68;
            // if(request()->payment_status !=-1 ){
            if(request()->has('payment_status') && request()->payment_status!=-1){
                $payment_status = request()->payment_status;
                if($payment_status == 68){
                    $where_cart_masters .= " and cart_masters.payment_status in (68,376)";
                }elseif($payment_status == 63){
                        $where_cart_masters .= " and cart_masters.payment_status in (63,377)";
                }else{
                    $where_cart_masters .= " and cart_masters.payment_status = :payment_status";
                    $args = array_merge($args, ['payment_status' => $payment_status,]);
                }
            }

            // request()->invoice_number = '1411071656';
            // if(request()->invoice_number !=-1 ){
            if(request()->has('invoice_number') && request()->invoice_number){
                $invoice_number = request()->invoice_number;
                $where_cart_masters .= " and cart_masters.invoice_number like :invoice_number";
                $args = array_merge($args, ['invoice_number' => '%'.$invoice_number.'%',]);
            }
        // &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& End CartMaster &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

        // &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& Start User &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

            $where_users = "Where true";
            $where_users_c = "Where true";

            // request()->user_search = 'hanisalah78@gmail.com';
            // if(request()->user_search !=-1 ){
            if(request()->has('user_search') && request()->user_search){
                $user_search = request()->user_search;
                $where_users .= " and (users.email like :user_search_email OR users.name like :user_search_name OR users.mobile like :user_search_mobile)";
                $args = array_merge($args, ['user_search_email' => '%'.$user_search.'%','user_search_name' => '%'.$user_search.'%','user_search_mobile' => '%'.$user_search.'%',]);

                $where_users_c .= " and (users.email like :user_search_email_c OR users.name like :user_search_name_c OR users.mobile like :user_search_mobile_c)";
                $args = array_merge($args, ['user_search_email_c' => '%'.$user_search.'%','user_search_name_c' => '%'.$user_search.'%','user_search_mobile_c' => '%'.$user_search.'%',]);
            }

            // request()->country_id = 58;
            // if(request()->country_id!=-1){
            if(request()->has('country_id') && request()->country_id!=-1){
                $country_id = request()->country_id;
                $where_users .= " and users.country_id = :country_id";
                $args = array_merge($args, ['country_id' => $country_id,]);

                $where_users_c .= " and users.country_id = :country_id_c";
                $args = array_merge($args, ['country_id_c' => $country_id,]);
            }
        // &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& End User &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

        // &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& Start Carts &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

            $where_carts = 'Where true';
            $where_carts .= " and carts.deleted_at is null";
            $where_carts .= " and carts.trashed_status is null";

            // request()->course_id = 1;
            // if(request()->course_id!=-1){
            if(request()->has('course_id') && request()->course_id!=-1){
                $course_id = request()->course_id;
                $where_carts .= " and carts.course_id = :course_id";
                $args = array_merge($args, ['course_id' => $course_id,]);
            }


            // request()->session_id = 1;
            // if(request()->session_id!=-1){
            if(request()->has('session_id') && request()->session_id!=-1){
                $session_id = request()->session_id;
                $where_carts .= " and carts.session_id = :session_id";
                $args = array_merge($args, ['session_id' => $session_id,]);
            }

            // request()->date_from = '2021-01-07';
            // request()->date_to = '2021-05-19';
            // if(!is_null(request()->date_from)){
            if(request()->has('date_from') && !is_null(request()->date_from)){
                $date_from = request()->date_from;
                $where_carts .= " and Date(carts.registered_at) >= :date_from";
                $args = array_merge($args, ['date_from' => $date_from,]);

                // if(!is_null(request()->date_to)){
                if(request()->has('date_to') && !is_null(request()->date_to)){
                    $date_to = request()->date_to;
                    $where_carts .= " and Date(carts.registered_at) <= :date_to";
                    $args = array_merge($args, ['date_to' => $date_to,]);
                }
            }

            $training_option_join = "";
            // request()->training_option_id = 1;
            // if(request()->training_option_id!=-1){
            if(request()->has('training_option_id') && request()->training_option_id!=-1){
                $training_option_join = " inner join training_options on training_options.id = carts.training_option_id";
                $training_option_id = request()->training_option_id;
                $where_carts .= " and training_options.constant_id = :training_option_id";
                $args = array_merge($args, ['training_option_id' => $training_option_id,]);
            }

            // request()->promo_code = 1;
            // if(request()->promo_code) {
            if(request()->has('promo_code') && request()->promo_code!=-1){
                $promo_code = request()->promo_code;
                $where_carts .= " and carts.promo_code <> ''";
                // $args = array_merge($args, ['promo_code' => $promo_code,]);
            }
            if(request()->has('promo_code_str') && request()->promo_code_str){
                $promo_code_str = request()->promo_code_str;
                $where_carts .= " and carts.promo_code like :promo_code_str";
                $args = array_merge($args, ['promo_code_str' => '%'.$promo_code_str.'%',]);
            }

            // ==============
            // Search for category

            $category_join = "";
            // request()->category_id = 15;
            // if(request()->category_id!=-1){
            if(request()->has('category_id') && request()->category_id!=-1){
                $category_join = " inner join post_morphs on post_morphs.postable_id = carts.course_id";
                $category_id = request()->category_id;
                $where_carts .= " and table_id IS NULL and postable_type like '%Course%' and post_morphs.constant_id = :category_id";
                $args = array_merge($args, ['category_id' => $category_id,]);
            }

            $sessions_join = "";
            // request()->session_from = '2025-01-01';
            // if(request()->session_from!=-1){
            if(request()->has('session_from') && !is_null(request()->session_from)){
                $sessions_join = " inner join sessions on sessions.id = carts.session_id";
                $session_from = request()->session_from;
                $where_carts .= " and Date(sessions.date_from) >= :session_from";
                $args = array_merge($args, ['session_from' => $session_from,]);

                // request()->session_to = '2025-01-01';
                // if(request()->session_to!=-1){
                if(request()->has('session_to') && !is_null(request()->session_to)){
                    $session_to = request()->session_to;
                    $where_carts .= " and Date(sessions.date_from) <= :session_to";
                    $args = array_merge($args, ['session_to' => $session_to,]);
                }
            }

            if(request()->has('cart_invoice_number') && request()->cart_invoice_number){
                $cart_invoice_number = request()->cart_invoice_number;
                $where_carts .= " and carts.invoice_number like :cart_invoice_number";
                $args = array_merge($args, ['cart_invoice_number' => '%'.$cart_invoice_number.'%',]);
            }

            // $attend_type_join = "";
            if(request()->has('attend_type_id') && request()->attend_type_id!=-1){
                // $attend_type_join = " inner join constants as attend_types on attend_types.id = carts.attend_type_id and attend_types.deleted_at is null";
                $attend_type_id = request()->attend_type_id;
                $where_carts .= " and carts.attend_type_id = :attend_type_id";
                $args = array_merge($args, ['attend_type_id' => $attend_type_id,]);
            }

            $where_cart_masters .= " and cart_masters.id in(
                    SELECT carts.master_id
                    FROM carts
                        $training_option_join
                        $category_join
                        $sessions_join
                        $where_carts
                    and carts.user_id in (
                    SELECT users.id
                    FROM users
                        $where_users_c
                    )
                )";

        // &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& End Carts &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

        // echo $where_cart_masters.'<br><br>';
        // echo $master_id__field.'<br><br>';

        $page = request()->page ?? 1;
        $Paginate = PAGINATE;
        $offset = ($page - 1) * $Paginate;

        $pa  = " ORDER BY cart_masters.id DESC ";
        // if($hasPaginate){
        //     $pa  = " ORDER BY cart_masters.updated_at DESC limit $offset, $Paginate";
        // }

        // if(auth()->check()){
        //     if(auth()->user()->id==2){
        //         DB::enableQueryLog(); // Enable query log
        //     }
        // }
        $cartMasters = DB::select( DB::raw("
            SELECT
            cart_masters.id, cart_masters.type_id, type.name as type_name, cart_masters.status_id, status.name as status_name, cart_masters.invoice_number, cart_masters.total, cart_masters.vat, cart_masters.vat_value, cart_masters.total_after_vat, cart_masters.registered_at, cart_masters.created_at, cart_masters.wp_migrate, cart_masters.xero_prepayment, cart_masters.xero_prepayment_created_at, cart_masters.trashed_status
            , cart_masters.coin_id, coin.name as coin_name
            , cart_masters.payment_status, paymentStatus.name as payment_status_name
            , payments.paid_in, payments.paid_out, payments.updated_at as payments_updated_at, payments.paid_at as payment_paid_at, payments.payment_status as payment_status_from_payment, paymentStatus_payment.name as payment_status_from_payment_name
            , cart_masters.user_id, users.name, users.email, users.mobile, users.job_title, users.company, users.username_lms, users.password_lms, gender.name as gender, countries.name as country, users.country_id

            FROM cart_masters

            left join (
                        SELECT
                        users.id, users.name, users.email, users.mobile, users.job_title, users.company, users.username_lms, users.password_lms, users.country_id, users.gender_id, users.deleted_at
            FROM users
                        $where_users
                    ) as users on users.id = cart_masters.user_id and users.deleted_at is null
                    left join constants as gender on gender.id = users.gender_id and gender.deleted_at is null
                    left join constants as countries on countries.id = users.country_id and countries.deleted_at is null

            left join   (
                            SELECT payments.master_id,
                                CASE WHEN payments.payment_status = 376 THEN 68
                                     WHEN payments.payment_status = 377 THEN 63
                                     ELSE payments.payment_status
                                END AS payment_status
                                , payments.paid_in
                                , payments.paid_out
                                , payments.paid_at
                                , payments.updated_at
                                , payments.deleted_at
                FROM payments
                        ) as payments on payments.master_id = cart_masters.id and payments.deleted_at is null
                        left join constants as paymentStatus_payment on paymentStatus_payment.id = payments.payment_status and paymentStatus_payment.deleted_at is null

            left join constants as type on type.id = cart_masters.type_id and type.deleted_at is null
            left join constants as status on status.id = cart_masters.status_id and status.deleted_at is null
            left join constants as paymentStatus on paymentStatus.id = cart_masters.payment_status and paymentStatus.deleted_at is null
            left join constants as coin on coin.id = cart_masters.coin_id and coin.deleted_at is null

            $where_cart_masters $pa"), $args);

            // if(auth()->check()){
            //     if(auth()->user()->id==2){
            //         dump(DB::getQueryLog());
            //         dump(request()->date_from);
            //         dump(request()->date_to);
            //     }
            // }

        return $cartMasters;
    }

    public function GetCarts()
    {
        if (auth()->check()) {
            $carts = Cart::where('user_id', auth()->id())
                ->where('payment_status', '!=', 68)
                ->where('payment_status', '!=', 332)
                ->whereNull('trashed_status')
                ->whereNull('deleted_at')
                ->where('coin_id', session('coinID'))
                ->whereHas('cartMaster', function (Builder $query) {
                    $query->where('user_id', auth()->id());
                })
                ->with([
                    'course',
                    'session',
                    'course.upload',
                    'trainingOption.TrainingOptionFeature.feature',
                    'trainingOption.type',
                    'cartFeatures.trainingOptionFeature',
                    'cartMaster',
                    'trainingOptionOrSession',
                ])
                ->orderBy('id', 'desc');
                // ->get();

            return $carts;
        } else {

            // dump(Cookie::get('user_token'));
            // if(session('user_token')) {
                // dd('user_token: '.Cookie::get('user_token'));
            if (Cookie::get('user_token')) {

                $carts = Cart::where('user_token', Cookie::get('user_token'))
                    // ->where('user_token', session('user_token'))
                    ->where('payment_status', '!=', 68)
                    ->where('payment_status', '!=', 332)
                    ->whereNull('trashed_status')
                    ->where('coin_id', session('coinID'))
                    ->with([
                        'course',
                        'session',
                        'course.upload',
                        'trainingOption.TrainingOptionFeature.feature',
                        'trainingOption.type',
                        'cartFeatures.trainingOptionFeature',
                        'trainingOptionOrSession',
                    ])
                    ->orderBy('id', 'desc');
                    // ->get();

                return $carts;
            }
        }
        return null;
    }

    public function Details(){

        $CartHelper = new CartHelper();

        $GetCarts = $CartHelper->GetCarts();

        if(is_null($GetCarts)){
            return;
        }
        $carts = $GetCarts->get();

        $cartMaster = null;
        $CartMasterHelper = new CartMasterHelper();
        $cartMaster = $CartMasterHelper->GetTotal($carts);

        $features = $this->GetFeatures($GetCarts);

        return compact('cartMaster', 'carts', 'features');
    }

    public function GetFeatures($GetCarts){

        $cart_id_array = $GetCarts->pluck('id');

        // dd($cart_id_array);
        $features = CartFeature::whereIn('master_id', $cart_id_array)
        ->select('id', 'master_id', 'training_option_feature_id')
        ->get()
        ->toArray();

        // dd($features);
        $features1 = [];
        foreach($features as $feature){
            $key = $feature['master_id'].'-'.$feature['training_option_feature_id'];
            $features1 = array_merge($features1, [$key=>true,]);
        }
        return json_encode($features1);
    }

    public function AddOrRemoveFeature($cart, $feature_id){

        $trainingOptionFeature = TrainingOptionFeature::find($feature_id);

        $CartFeature_count = CartFeature::where('master_id', $cart->id)
            ->where('training_option_feature_id', $feature_id)
            ->count();

        $feature_action='add';
        if($CartFeature_count!=0){
            $feature_action='remove';
        }

        $price = $trainingOptionFeature->price;
        if($cart->coin_id==335){
            $price = $trainingOptionFeature->price_usd;
        }

        if($feature_action=='add')
        {
            $vat_value = (VAT / 100) * $price;
            $total_after_vat = $price + $vat_value;
            CartFeature::create([
                'master_id'=>$cart->id,
                'training_option_feature_id'=>$feature_id,
                'price'=>$price,
                'vat'=>VAT,
                'vat_value'=>$vat_value,
                'total_after_vat'=>$total_after_vat,
            ]);
        }
        else
        {
            CartFeature::where('master_id', $cart->id)
            ->where('training_option_feature_id', $feature_id)
            ->forceDelete();
        }
    }

    public function updateOrCreate($array=null){

        $type = $array['type']??'training_option';
        $coin_id = GetCoinId();
        $coin_price = GetCoinPrice();

        $session = Session::with('trainingOption')->find($array['session_id']);
        $course = Course::find($session->trainingOption->course_id);

        $SessionHelper = new SessionHelper;

        $course = $SessionHelper->Single($course->slug, ($type == 'training_option') ? true : false)
            ->where('session_id', $array['session_id'])
            ->first();

        $SessionHelper->SetCourse($course);

        $price = $SessionHelper->Price();
        $exam_price = $SessionHelper->ExamPrice();
        $total = $SessionHelper->PriceWithExamPrice();
        $vat = $SessionHelper->VAT();
        $vat_value = $SessionHelper->VATFortPriceWithExamPrice();
        $total_after_vat = $SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT();

        $discount_id = $course->discount_id;
        $discount = $SessionHelper->Discount() ?? 0;
        $discount_value = $SessionHelper->DiscountValue();

        $promo_code = $array['promo_code']??null;
        if(isset($array['SmartPromocode'])){
            $discount_id = $array['SmartPromocode']->id;
            $discount = $array['SmartPromocode']->value;
            $discount_value = $price * $array['SmartPromocode']->value / 100;
        }

        $cart = Cart::updateOrCreate([
            'user_token' => $array['user_token'],
            'payment_status' => 63,
            'session_id' => $array['session_id'],
        ],[
            'master_id'          => $array['master_id'],
            'session_id'         => $array['session_id'],
            'status_id'          => 327, //51
            'user_id'            => auth()->id(),
            'training_option_id' => $session->training_option_id, //??
            'course_id'          => $session->trainingOption->course_id, //??
            'price'              => NumberFormat($price),
            'exam_price'         => NumberFormat($exam_price),
            'total'              => NumberFormat($total),
            'vat'                => NumberFormat($vat),
            'vat_value'          => NumberFormat($vat_value),
            'total_after_vat'    => NumberFormat($total_after_vat),
            'discount_id'        => $discount_id,
            'discount'           => $discount,
            'discount_value'     => $discount_value,
            'invoice_number'     => date("His") . rand(1234, 9632), //??
            'trying_count'       => 1,
            'coin_id'            => $coin_id,
            'coin_price'         => $coin_price,
            'registered_at'      => DateTimeNow(),
            'locale'             => app()->getLocale(),
            'payment_status'     => 63,
            // 'user_token'         => $user_token,
            'register_type'      => $type,
            'promo_code'          => $promo_code,
        ]);
        return $cart;
    }

    public function GetTotal($master_id){

        $cart_total = Cart::where('master_id', $master_id)
        ->select(DB::raw('sum(price) as price, sum(total) as total
        , sum(vat_value) as vat_value, sum(total_after_vat) as total_after_vat
        , sum(discount_value) as discount_value'))
        ->first();

        return $cart_total;
    }

    public function GetFeatureTotal($cart_id){

        $CartFeature = CartFeature::where('master_id', $cart_id)
        ->select(DB::raw('sum(price) as price, sum(vat_value) as vat_value, sum(total_after_vat) as total_after_vat'))
        // ->select(DB::raw('sum('.$price_currency.') as sum_prices'))
        ->first();

        return $CartFeature;
    }

    public function GetCart($cart_id){

        $cart = Cart::with(['trainingOptionOrSession', 'course.upload', 'trainingOption.trainingOptionFeatures.feature'
        , 'cartFeatures.trainingOptionFeature'])
        ->find($cart_id);

        return $cart;
    }
}
