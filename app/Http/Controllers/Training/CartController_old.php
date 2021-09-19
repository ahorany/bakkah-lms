<?php

namespace App\Http\Controllers\Training;

use App\User;
use App\Constant;
use App\Helpers\Active;
use Illuminate\Http\Request;
use App\Models\Training\CartMaster;
use App\Models\Training\Cart;
use App\Models\Training\Course;
use App\Models\Training\Session;
use App\Exports\StatisticsExport;
use Illuminate\Support\Facades\DB;
use App\Exports\RegistrationExport;
use App\Helpers\Models\Training\CourseHelper;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Training\TrainingOption;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Training\CartRequest;
use App\Helpers\Models\Training\SessionHelper;
use Illuminate\Database\Query\Builder as QueryBuilder;

class CartController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'carts';
    }

    private function GetAllCourses(){
        $all_courses = Course::has('trainingOptions.sessions')
            ->with('trainingOptions.sessions')
            ->orderBy('order')
            ->get();
        return $all_courses;
    }

    public function index(){

        $all_courses = Course::GetAll();

        $trash = GetTrash();
        // $carts = Cart::whereNotNull('id')
        //     ->with(['course:id,title,partner_id,certificate_type_id', 'payment', 'userId.countries', 'session'=>function($query){
        //         $query->withTrashed();
        //     }]);
        // $count = $carts->count();

        // $cartMasters = CartMaster::whereNotNull('user_id')
        //     ->with(['type', 'payment', 'userId.countries', 'carts'=>function($query){
        //         $query->withTrashed()
        //         ->with(['trainingOption', 'course:id,title,partner_id,certificate_type_id',
        //         'course'=>function($query){
        //             $query->withTrashed();
        //         }, 'session'=>function($query){
        //             $query->withTrashed();
        //         }, 'cartFeatures.feature'=>function($query){
        //             $query->withTrashed();
        //         }]);
        //     }])
        //     ->orderBy('id', 'desc');
        $cartMasters = CartMaster::whereNotNull('user_id')->orderBy('id', 'desc');
        $cartMasters = $cartMasters->with([
            'userId.gender:id,name',
            'userId.countries:id,name',
            'type:id,name',
            'rfpGroup.organization:id,title',
            'status:id,name',
            'payment:id,master_id',
            'paymentStatus:id,name',
            'carts.course:id,title,partner_id,certificate_type_id',
            'carts.trainingOption:id,PDUs,price',
        ]);
        $post_type='cart';
        $count = $cartMasters->count();
        $cartMasters = $cartMasters->page();

        $categories = Constant::where('post_type', 'course')->get();
        $status = $this->GetStatus();
        $countries = Constant::where('post_type', 'countries')->get();
        $delivery_methods = Constant::where('parent_id', 10)->get();
        $currencyConstants = Constant::whereIn('id', [334, 335])->get();
        $types = Constant::where('parent_id', 371)->orderBy('id', 'desc')->get();

        // perPage
        // dd($cartMasters);
        $session_array = $this->sessionsJson();
        // dd($session_array);

        return Active::Index(compact('cartMasters', 'post_type','count', 'trash', 'all_courses'
            , 'categories', 'status', 'countries', 'session_array', 'delivery_methods', 'currencyConstants', 'types'));
        // return Active::Index(compact('cartMasters', 'carts', 'post_type','count', 'trash', 'all_courses'
        //     , 'categories', 'status', 'countries', 'session_array', 'delivery_methods', 'currencyConstants', 'types'));
    }

    public function sessionsJson()
    {
        $sessions = Session::whereNotNull('id');
        if(request()->has('course_id') && request()->course_id!=-1 && request()->course_id!=-0){
            $sessions = $sessions->whereHas('trainingOption.course', function (Builder $query){
                $query->where('course_id', request()->course_id);
            });
        }
        if(request()->has('training_option_id') && request()->training_option_id!=-1 && request()->training_option_id!=-0){
            $sessions = $sessions->whereHas('trainingOption.course', function (Builder $query){
                $query->where('training_option_id', request()->training_option_id);
            });
        }
        $sessions = $sessions->with('trainingOption.course')->orderBy('date_from')->get();
        $session_array = Session::GetJson($sessions);

        // $delivery_methods = TrainingOption::where('course_id', \request()->course_id)
        //     ->with('constant')
        //     ->get();
        return json_encode($session_array);
        // return json_encode([$session_array,$delivery_methods]);
    }

    public function GetSessionInfoJson()
    {
        // return json_encode($session_array);
    }

    public function search(){

        $trash = GetTrash();

        $cartMasters = CartMaster::whereNotNull('id');

        if(!is_null(request()->invoice_number)) {
            $cartMasters = $cartMasters->where('invoice_number', 'like', '%'.request()->invoice_number.'%');
        }
        if(!is_null(request()->date_from)) {
            $cartMasters = $cartMasters->whereDate('registered_at', '>=', request()->date_from);
        }
        if(!is_null(request()->date_to)) {
            $cartMasters = $cartMasters->whereDate('registered_at', '<=', request()->date_to);
        }
        if(request()->has('coin_id') && request()->coin_id!=-1) {
            $cartMasters = $cartMasters->where('coin_id', request()->coin_id);
        }
        if(request()->has('type_id') && request()->type_id!=-1) {
            $cartMasters = $cartMasters->where('type_id', request()->type_id);
            request()->post_type = 'group_invoices';
        }
        // dd($cartMasters);

        $cartMasters = $cartMasters->whereHas('userId', function (Builder $query) {
                if(request()->has('user_search') && !is_null(request()->user_search)){
                    $query->where('name', 'like', '%'.request()->user_search.'%')
                        ->orWhere('email', 'like', '%'.request()->user_search.'%')
                        ->orWhere('mobile', 'like', '%'.request()->user_search.'%')
                    ;
                }
            });

        if(request()->has('country_id') && request()->country_id!=-1) {
            $cartMasters = $cartMasters->whereHas('userId.countries', function (Builder $query){
                $query->where('country_id', request()->country_id);
            });
        }

        $cartMasters = $cartMasters->whereHas('carts', function (Builder $query){
            if(request()->has('course_id') && request()->course_id!=-1) {
                $query->where('course_id', request()->course_id);
            }
            if(request()->has('session_id') && request()->session_id!=-1) {
                $query->where('session_id', request()->session_id);
            }
            if(request()->has('training_option_id') && request()->training_option_id!=-1) {
                $query->whereHas('trainingOption', function (Builder $query) {
                    $query->where('constant_id', request()->training_option_id);
                });
            }
            if(!is_null(request()->session_from)) {
                $query->whereHas('session', function (Builder $query) {
                    $query->whereDate('date_from', '>=', request()->session_from);
                });
            }
            if(!is_null(request()->session_to)) {
                $query->whereHas('session', function (Builder $query) {
                    $query->whereDate('date_from', '<=', request()->session_to);
                });
            }
            if(request()->promo_code) {
                $query->where('promo_code', '<>', '');
            }
            if(request()->has('category_id') && request()->category_id!=-1){
                $query->whereHas('course.postMorphs', function (Builder $query){
                    $query->where('constant_id', request()->category_id);
                });
            }
        });

        if(request()->has('payment_status') && request()->payment_status!=-1) {
            if(request()->payment_status==332){
                $cartMasters = $cartMasters->doesntHave('payment');
            }
            elseif(request()->payment_status==68){
                $cartMasters = $cartMasters->whereHas('payment', function (Builder $query) {
                    $query->whereIn('payment_status', [68, 376]);
                    // $query->where('payment_status', request()->payment_status);
                });
            }
            else{
                $cartMasters = $cartMasters->whereHas('payment', function (Builder $query) {
                    $query->where('payment_status', request()->payment_status);
                });
            }
        }

        $cartMasters = $cartMasters->with([
            'userId.gender',
            'userId.countries',
            'type',
            'rfpGroup.organization',
            'status',
            'payment',
            'paymentStatus',
            'carts.course',
            'carts.trainingOption',
        ]);
        $cartMasters = $cartMasters->orderBy('id', 'desc');
        $cartMasters = $cartMasters->page();

        $post_type='cart';
        $count = $cartMasters->count();

        // $cartMasters = CartMaster::whereNotNull('user_id')
        // ->with(['type', 'payment', 'userId.countries', 'carts'=>function($query){
        //     $query->withTrashed()
        //     ->with(['trainingOption', 'course:id,title,partner_id,certificate_type_id',
        //     'course'=>function($query){
        //         $query->withTrashed();
        //     }, 'session'=>function($query){
        //         $query->withTrashed();
        //     }, 'cartFeatures.feature'=>function($query){
        //         $query->withTrashed();
        //     }]);
        // }])
        // ->orderBy('id', 'desc');
        // $cartMasters = $cartMasters->page();

        return view(Active::$namespace.'.'.Active::$folder.'.table', compact('cartMasters', 'count'
            , 'trash'));
    }

    public function create(){

        $users = User::all();
        $sessions = Session::all();
        $status=Constant::where('parent_id',50)->get();
        return Active::Create(compact('users','sessions','status'));
    }

    public function store(CartRequest $request){

        $validated = $this->Validated($request->validated());
        $validated['created_by'] = auth()->user()->id;
        $cart = Cart::create($validated);

        return Active::Inserted($cart->userId->name);
    }

    public function edit(Cart $cart){

        $users = User::all();
        $sessions = Session::all();
        $status=Constant::where('parent_id',50)->get();
        return Active::Edit(['eloquent'=>$cart, 'post_type'=>$cart->post_type,
            'users'=>$users,'sessions'=>$sessions,'status'=>$status]);
    }

    public function update(CartRequest $request, Cart $cart){

        $validated = $this->validated($request->validated());
        $validated['updated_by'] = auth()->user()->id;

        Cart::find($cart->id)->update($validated);
//        $cart=  Cart::where('id', $cart->id)->update([
//            'attendance_count'=>5,
//        ]);
        return Active::Updated($cart->userId->trans_name);
    }

    public function destroy(Cart $cart, Request $request){
        Cart::where('id', $cart->id)->SoftTrash();
//        return Active::Deleted($cart->userId->trans_name);
    }

    public function restore($cart){
        Cart::where('id', $cart)->RestoreFromTrash();
//        $cart = Cart::where('id', $cart)->first();
//        return Active::Restored($cart->userId->trans_name);
    }

    private function Validated($validated){

        $validated['updated_by'] = auth()->user()->id;
        return $validated;
    }

    public function export()
    {
        return Excel::download(new RegistrationExport, 'registration.xlsx');
    }

    private function GetStatus(){
        $status = Constant::where('parent_id', 62)
        ->whereNotIn('id', [376, 377, 378, 379, 375])
        ->get();
        return $status;
    }

    public function insights(){

        $post_type = GetPostType('insight');
        Active::Link(GetPostType($post_type));

        $all_courses = Course::GetAll();
        $session_array = $this->sessionsJson();

        $categories = Constant::where('post_type', 'course')->get();
        $status = $this->GetStatus();

        $countries = Constant::where('post_type', 'countries')->get();
        $delivery_methods = Constant::where('parent_id', 10)->get();
        $vat_filters = Constant::where('parent_id', 345)->get();
        $product_filters = Constant::where('parent_id', 348)->get();
        $currencyConstants = Constant::whereIn('id', [334, 335])->get();

        $insightsBody = $this->insightsBody();
        $array = compact('all_courses', 'session_array', 'categories', 'status', 'countries'
        , 'delivery_methods', 'post_type', 'vat_filters', 'product_filters', 'currencyConstants');
        $insightsBody = array_merge($insightsBody, $array);

        Active::$folder = 'insights';
        return Active::Index($insightsBody);
    }

    public function insightsSearch(){

        $insightsBody = $this->insightsBody();
        $insightsBody = array_merge($insightsBody, [
            'post_type'=>request()->post_type??'insight',
        ]);
        return view('training.insights.table', $insightsBody);
    }

    private function insightsBody(){

        // =============== Insights per Product ==================================
        $totalByCourses = Cart::join('users', 'users.id', 'carts.user_id')
        ->JoinInsights()
        ->select(DB::Raw('courses.id, courses.short_title, sum(payments.paid_in-payments.paid_out) as total, count(carts.id) as _count'))
        ->groupBy('courses.id','courses.short_title')
        ->get();
        // =============== End of Insights per Product ===========================

        // =============== Insights per Country ==================================
        $totalByCountries = Cart::join('users', 'users.id', 'carts.user_id')
        ->JoinInsights()
        ->select(DB::Raw('countries.id, countries.name, sum(payments.paid_in-payments.paid_out) as total, count(carts.id) as _count'))
        ->groupBy('countries.id','countries.name')
        ->get();
        // =============== End of Insights per Country ================================

        // =============== Insights per Category ==================================
        $totalPerCategories = Cart::join('users', 'users.id', 'carts.user_id')
        ->JoinInsights()
        ->select(DB::Raw('constants.id, constants.name, sum(payments.paid_in-payments.paid_out) as total, count(carts.id) as _count'))
        ->groupBy('constants.id','constants.name')
        ->get();
        // =============== End of Insights per Category ==================================

        // =============== Total Delivery Methods ==================================
        $totalPerDeliveryMethods = Cart::join('users', 'users.id', 'carts.user_id')
        ->JoinInsights()
        ->select(DB::Raw('methods.id, methods.name, sum(payments.paid_in-payments.paid_out) as total, count(carts.id) as _count'))
        ->groupBy('methods.id','methods.name')
        ->get();
        // =============== End of Delivery Methods ===========================

        // =============== Total Delivery Sessions ==================================
        $totalPerSessions = Cart::join('users', 'users.id', 'carts.user_id')
        ->JoinInsights()
        ->select(DB::Raw('courses.order, sessions.id, sessions.date_from, sessions.date_to, courses.short_title, sum(payments.paid_in-payments.paid_out) as total, count(carts.id) as _count'))
        ->groupBy('courses.order', 'sessions.id', 'sessions.date_from', 'sessions.date_to', 'courses.short_title')
        ->orderBy('courses.order')
        ->orderBy('sessions.date_from')
        ->get();
        // =============== End of Delivery Sessions ===========================

        // =============== Total Insights ==================================
        $totalPerStatus_query = 'sum(payments.paid_in-payments.paid_out) as total, count(carts.id) as _count, sum(carts.price) as course_price, sum(carts.discount_value) as discount, sum(carts.exam_price) as exam_price, sum(carts.take2_price) as take2_price, sum(carts.vat_value) as vat_value, sum(carts.total_after_vat) as total_after_vat';
        // $field = '';
        // if(request()->has('exams')){// Without VAT
        //     $field .= 'carts.exam_price+';
        // }
        // if(request()->has('take2')){// Without VAT
        //     $field .= 'carts.take2_price+';
        // }
        // if(request()->has('courses')){// Without VAT
        //     $field .= 'carts.take2_price+';
        // }
        // $totalPerStatus_query = 'sum('.substr($field, 0, -1).') as total, count(carts.id) as _count';
        $totalPerStatus = Cart::join('users', 'users.id', 'carts.user_id')
        ->JoinInsights()
        ->join('constants as categories', function($join){
            $join->on('categories.id', '=', 'payments.payment_status');
            $join->where('categories.parent_id', 62)
            ->whereNotIn('categories.id', [376, 377, 378, 379, 375, 332]);
        })
        ->select(DB::Raw('categories.id, categories.name, '.$totalPerStatus_query))
        // ->select(DB::Raw('categories.id, categories.name, sum(payments.paid_in-payments.paid_out) as total, count(carts.id) as _count'))
        ->groupBy('categories.id', 'categories.name')
        ->get();
        // foreach($totalPerStatus as $totalPerStatu){
        //     dump($totalPerStatu->name);
        // }
        // dd($totalPerStatus);
        // =============== End of Total Insights ===========================
        if(!request()->has('payment_status') || request()->payment_status==332 || request()->payment_status==-1)
        {
            // =============== Total Insights ==================================
            $freeSeats = Cart::join('users', 'users.id', 'carts.user_id')
            // ->JoinInsights()
            ->where('carts.payment_status', 332)
            ->select(DB::Raw('1 as id, "Free Seat" as name, 0 as total, count(carts.id) as _count, 0 as course_price, 0 as discount, 0 as exam_price, 0 as take2_price, 0 as vat_value, 0 as total_after_vat'))

            // ->select(DB::Raw('1 as id, "Free Seat" as name, sum(carts.total_after_vat) as total, count(carts.id) as _count,sum(carts.price) as course_price, sum(carts.discount_value) as discount, sum(carts.exam_price) as exam_price, sum(carts.take2_price) as take2_price, sum(carts.vat_value) as vat_value, sum(carts.total_after_vat) as total_after_vat'))
            // ->groupBy('id', 'name')
            ->get();
            // dd($freeSeats);
            // =============== End of Total Insights ===========================
            $totalPerStatus = $totalPerStatus->merge($freeSeats);
        }

        return compact('totalByCourses', 'totalByCountries', 'totalPerCategories', 'totalPerDeliveryMethods'
        , 'totalPerSessions', 'totalPerStatus');
    }

    public function statistics(){

        Active::Link('statistics');
        $joinTable = '(
            select *
            from users
            where id in(
                select carts.user_id
                from carts
                inner join payments on carts.id = payments.master_id
                and (payments.payment_status = 68 or payments.payment_status = 376)
                and payments.deleted_at is null
                group by carts.user_id
                HAVING count(carts.id) > 1
            )
            and deleted_at is null
        ) as users';
        $statistics = Cart::join(DB::raw($joinTable), function($join){
            $join->on('users.id', 'carts.user_id');
        })
        ->join('courses', 'courses.id', 'carts.course_id')
        ->select('carts.id', 'users.email', 'users.mobile', 'users.name', 'courses.title', 'carts.registered_at');

        $statistics__all = $statistics->get();

        if(!is_null(request()->user_search)) {
            $statistics = $statistics->where('name', 'like', '%'.request()->user_search.'%')
                ->orWhere('email', 'like', '%'.request()->user_search.'%')
                ->orWhere('mobile', 'like', '%'.request()->user_search.'%')
                ->orWhere('title', 'like', '%'.request()->user_search.'%');
        }

        if(!is_null(request()->date_from)) {
            $statistics = $statistics->whereDate('registered_at', '>=', request()->date_from);
        }
        if(!is_null(request()->date_to)) {
            $statistics = $statistics->whereDate('registered_at', '<=', request()->date_to);
        }

        $statistics = $statistics->orderBy('carts.user_id');
        $statistics = $statistics->get();
        $count = $statistics->count();
        // $statistics = $statistics->paginate(PAGINATE);

        $folder = 'statistics';
        $trash = null;
        return view('training.statistics.index', compact('statistics', 'folder', 'trash', 'count', 'statistics__all'));
    }

    public function statisticsExport() {
        return Excel::download(new StatisticsExport, 'registration.xlsx');
    }

    public function trainingSchedule(){

        // if(request()->has('convertToUSD')){
        //     return redirect()->route('training.carts.training-schedule', ['coin_id'=> 335]);
        // }
        // else if(request()->has('convertToSAR')){
        //     return redirect()->route('training.carts.training-schedule', ['coin_id'=> 334]);
        // }
        Active::Link('training-schedule');
        $delivery_methods = Constant::where('parent_id', 10)->get();

        $CourseHelper = new CourseHelper();
        $all_courses = $CourseHelper->AllCourses();

        $courses = null;
        $selfCourse = null;
        $SessionHelper = new SessionHelper();
        $sessions = $SessionHelper->TrainingOption();

        $all_courses_ar = [];
        $all_sessions = [];
        foreach($sessions as $session){
            if(!in_array($session->id, $all_courses_ar))
            {
                if(!is_null($session->session_id))
                {
                    array_push($all_courses_ar, $session->id);
                    array_push($all_sessions, [
                        'id'=>$session->id,
                        'slug'=>$session->slug,
                        'title'=>$session->title,
                        'order'=>$session->order,
                    ]);
                }
            }
        }
        $all_sessions = collect($all_sessions)->sortBy('order');
        $selfCourse = null;
        $folder = 'training-schedule';
        // $post_type = 'training-schedule';
        // $trash = null;
        return view('training.training-schedule.index', compact('courses', 'sessions'
            , 'all_courses', 'selfCourse', 'SessionHelper', 'all_sessions', 'delivery_methods', 'folder'));
//         dd('New');
    }

    public function lms($cart_id=null){
        $cart = Cart::with(['session.trainingOption'])->findOrFail($cart_id);
        // dd($cart);

        // Need to have $master_id
        // app('App\Http\Controllers\Front\Education\LMSController')->run($cart);
        app('App\Http\Controllers\Front\Education\LMSController')->run($cart->master_id, $cart->id);

        Cart::where('id', $cart_id)->update(['lms_sent_at'=>now()]);

        Active::Flash("LMS sent Successfully.", __('flash.empty'), 'success');
        return back();
    }

    public function invoice($cart_master_id){

        // app\Mail\Invoice.php
        // dd($cart_master_id);
        $CartMast = CartMaster::where('id', $cart_master_id)->first();
        if(!is_null($CartMast) && $CartMast->type_id!=374){

            $cartMaster = CartMaster::with(['rfpGroup.organization', 'carts.userId', 'carts.trainingOption'])->findOrFail($cart_master_id);

        }else{

            $cartMaster = CartMaster::with(['carts.userId', 'carts.cartFeatures.feature'])->findOrFail($cart_master_id);

        }

            $count = $cartMaster->carts->count()??0;

            $totalPerStatus_query = 'sum(carts.total) as total, sum(carts.vat_value) as vat_value, sum(carts.total_after_vat) as total_after_vat';
            $cart = CartMaster::join('carts', 'cart_masters.id', 'carts.master_id')
            ->select(DB::Raw($totalPerStatus_query))
            ->whereNull('carts.deleted_at')
            ->find($cart_master_id);

            $coin_id = ($cartMaster->carts[0]->coin_id==334) ? 'SAR' : 'USD';

            // $coin_id = 'USD';
            // dd($cartMaster);
            // payment_receipt
            return view('emails.courses.invoice.'.$coin_id.'_master',['cartMaster'=>$cartMaster, 'count'=>$count, 'cart'=>$cart, 'user'=>$cartMaster->carts[0]->userId, 'type_id'=>$CartMast->type_id]);

    }

    // public function invoicePDF($cart_master_id) {

    //         // ============ Start of generate certification pdf function ==================
    //             // https://github.com/mpdf/mpdf
    //             // https://mpdf.github.io/css-stylesheets/supported-css.html

    //             // ============ Start of PDF sesstings ==================
    //                 $mpdf = new \Mpdf\Mpdf([
    //                     'margin_left' => 0,
    //                     'margin_right' => 0,
    //                     'margin_top' => 0,
    //                     'margin_bottom' => 0,
    //                     'margin_header' => 0,
    //                     'margin_footer' => 0,
    //                     'default-font' => 'Lato',
    //                     'orientation' => 'L',
    //                 ]);

    //                 $mpdf->SetProtection(array('print'));
    //                 $mpdf->SetTitle("Certificate");
    //                 $mpdf->SetAuthor(__('education.app_title'));
    //                 $mpdf->SetDisplayMode('fullpage');
    //                 $mpdf->SetFont('lato');
    //                 $mpdf->autoScriptToLang = true;
    //                 $mpdf->baseScript = 1;
    //                 $mpdf->autoVietnamese = true;
    //                 $mpdf->autoArabic = true;
    //                 $mpdf->autoLangToFont = true;
    //                 // $mpdf->SetDirectionality('rtl');

    //                 // $mpdf->SetWatermarkText("Paid");
    //                 // $mpdf->showWatermarkText = true;
    //                 // $mpdf->watermark_font = 'Lato';
    //                 // $mpdf->watermarkTextAlpha = 0.1;
    //                 // $mpdf->setAutoTopMargin = 'stretch';
    //             // ============ End of PDF sesstings ==================

    //             // ============ Start of generate the certificate and save it as a file ==================
    //                 ob_start();
    //                     // dump('aaaaa');
    //                     // $body = $this->invoice($id, 1);
    //                     // dd($body);
    //                     // $body = view('training.carts.', compact('id'))->render();
    //                     // $body = route('training.carts.invoice', $id)->render();

    //                     $cartMaster = CartMaster::with(['rfpGroup.organization', 'carts.userId', 'carts.trainingOption'])->find($cart_master_id);
    //                     $count = $cartMaster->carts->count();

    //                     $totalPerStatus_query = 'sum(carts.total) as total, sum(carts.vat_value) as vat_value, sum(carts.total_after_vat) as total_after_vat';
    //                     $cart = CartMaster::join('carts', 'cart_masters.id', 'carts.master_id')
    //                     ->select(DB::Raw($totalPerStatus_query))
    //                     ->whereNull('carts.deleted_at')
    //                     ->find($cart_master_id);

    //                     $coin_id = ($cartMaster->carts[0]->coin_id==334) ? 'SAR' : 'USD';

    //                     // $body = view('emails.courses.invoice.'.$coin_id, compact('cartMaster', 'count', 'cart'))->render();

    //                     $file_name_pdf = $cart_master_id;
    //                 $file_name_html = public_path() . '/invoices/'.$file_name_pdf.'.html';

    //                 $view = view('emails.courses.invoice.'.$coin_id, compact('cartMaster', 'count', 'cart'));

    //                 // $contents = (string) $view;
    //                 // or
    //                 $contents = $view->render();

    //                 $body = file_put_contents($file_name_html, $contents);

    //                     // file_put_contents($file_name_html, "lsdflaflöksd");


    //                     // $body = '<h1>'.$cart_master_id.'</h1>';

    //                     try{
    //                         $mpdf->WriteHTML($body);
    //                     }catch(\Mpdf\MpdfException $e){
    //                         die($e->getMessage());
    //                     }
    //                 ob_end_clean();

    //                 // $mpdf->Output();
    //                 // $file_name_pdf = $cert_no.'_'.$cart->userId->trans_name;
    //                 $file_name_pdf = $cart_master_id;
    //                 $file_name = public_path() . '/invoices/'.$file_name_pdf.'.pdf';
    //                 $mpdf->Output($file_name,'F');

    //                 return response()->download($file_name);
    //                 // $mpdf->WriteHTML(utf8_encode($html));
    //             // ============ End of generate the certificate and save it as a file ==================

    //     // ============ End of generate certification pdf function ==================
    //     // return view('training.certificates.certificate.index', compact('cart', 'data_for_qr','file_name_pdf'));

    // }
}
