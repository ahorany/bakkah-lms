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
use App\Helpers\Models\Training\CartHelper;
use App\Helpers\Models\Training\CourseHelper;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Training\TrainingOption;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Training\CartRequest;
use App\Helpers\Models\Training\SessionHelper;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;



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

    private function GetPaginator($items)
    {

        $total = count($items); // total count of the set, this is necessary so the paginator will know the total pages to display
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = PAGINATE; // how many items you want to display per page?
        $offset = ($currentPage - 1) * $perPage; // get the offset, how many items need to be "skipped" on this page
        // dump($currentPage);dump('start : '.$offset);dump('length : '.$perPage);dump($currentPage);
        $items = array_slice($items, $offset, $perPage); // the array that we actually pass to the paginator is sliced
        // dd($items);
        return new LengthAwarePaginator($items, $total, $perPage, $currentPage, [
            'path' => request()->url(),
            'query' => request()->query()
        ]);
    }

    public function totalCount(){

        $totalCount  = DB::select(DB::raw("SELECT count(cart_masters.id) as totalCount FROM cart_masters Where cart_masters.deleted_at is null and cart_masters.user_id is not null and cart_masters.id in( SELECT carts.master_id FROM carts Where carts.deleted_at is null and carts.trashed_status is null )"));

        return $totalCount[0]->totalCount??0;

    }

    public function index(Request $request){

        $totalCount = $this->totalCount();

        $CartHelper = new CartHelper();
        $cartMasters = $CartHelper->Query(true);

        $paginator = $this->GetPaginator($cartMasters);
        $cartMasters = $paginator->items();
        $count = $paginator->total();
        // $count = count($cartMasters);

        // dd($cartMasters);
        // echo $count.'<br>';
        // dd($paginator);


        $post_type='cart';
        $trash = GetTrash();
        $all_courses = Course::GetAll();
        $categories = Constant::where('post_type', 'course')->get();
        $status = $this->GetStatus();
        $countries = Constant::where('post_type', 'countries')->get();
        $delivery_methods = Constant::where('parent_id', 10)->get();
        $currencyConstants = Constant::whereIn('id', [334, 335])->get();
        $types = Constant::where('parent_id', 371)->orderBy('id', 'desc')->get();
        $attend_types = Constant::where('post_type', 'attend_type')->orWhere('id', 316)->orderBy('id', 'DESC')->get();
        // $session_array = $this->sessionsJson();

        return Active::Index(compact('cartMasters', 'post_type', 'totalCount', 'count', 'trash', 'all_courses'
            , 'categories', 'status', 'countries', 'delivery_methods', 'currencyConstants', 'types','paginator','attend_types'));

        // dd('New');
        // ===============================

        // $all_courses = Course::GetAll();

        // $trash = GetTrash();

        // $cartMasters = CartMaster::whereNotNull('user_id')->orderBy('id', 'desc');
        // $cartMasters = $cartMasters->with([
        //     'userId.gender:id,name',
        //     'userId.countries:id,name',
        //     'type:id,name',
        //     'rfpGroup.organization:id,title',
        //     'status:id,name',
        //     'payment:id,master_id,paid_in',
        //     'paymentStatus:id,name',
        //     'carts',
        //     'carts.course:id,title,partner_id,certificate_type_id',
        //     'carts.trainingOption:id,PDUs,price',
        //     'carts.cartFeatures',
        // ]);
        // $post_type='cart';
        // $count = $cartMasters->count();
        // $cartMasters = $cartMasters->page();

        // $categories = Constant::where('post_type', 'course')->get();
        // $status = $this->GetStatus();
        // $countries = Constant::where('post_type', 'countries')->get();
        // $delivery_methods = Constant::where('parent_id', 10)->get();
        // $currencyConstants = Constant::whereIn('id', [334, 335])->get();
        // $types = Constant::where('parent_id', 371)->orderBy('id', 'desc')->get();

        // // perPage
        // // dd($cartMasters);
        // $session_array = $this->sessionsJson();
        // // dd($session_array);
        // return Active::Index(compact('cartMasters', 'post_type','count', 'trash', 'all_courses'
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

        $totalCount = $this->totalCount();

        $CartHelper = new CartHelper();
        $cartMasters = $CartHelper->Query(true);

        $paginator = $this->GetPaginator($cartMasters);
        $cartMasters = $paginator->items();
        $count = $paginator->total();
        // $count = count($cartMasters);

        // dd($cartMasters);
        // echo $count.'<br>';
        // dd($paginator);ss


        $trash = GetTrash();
        $post_type='cart';

        return view(Active::$namespace.'.'.Active::$folder.'.table', compact('cartMasters', 'totalCount', 'count', 'trash', 'paginator'));

        // $trash = GetTrash();

        // $cartMasters = CartMaster::with([
        //     'userId.gender:id,name',
        //     'userId.countries:id,name',
        //     'type:id,name',
        //     'rfpGroup.organization:id,title',
        //     'status:id,name',
        //     'payment:id,master_id,paid_in',
        //     'paymentStatus:id,name',
        //     'carts',
        //     'carts.course:id,title,partner_id,certificate_type_id',
        //     'carts.trainingOption:id,PDUs,price',
        //     'carts.cartFeatures',
        // ]);

        // if(!is_null(request()->invoice_number)) {
        //     $cartMasters = $cartMasters->where('invoice_number', 'like', '%'.request()->invoice_number.'%');
        // }
        // if(!is_null(request()->date_from)) {
        //     $cartMasters = $cartMasters->whereDate('registered_at', '>=', request()->date_from);
        // }
        // if(!is_null(request()->date_to)) {
        //     $cartMasters = $cartMasters->whereDate('registered_at', '<=', request()->date_to);
        // }
        // if(request()->has('coin_id') && request()->coin_id!=-1) {
        //     $cartMasters = $cartMasters->where('coin_id', request()->coin_id);
        // }
        // if(request()->has('type_id') && request()->type_id!=-1) {
        //     $cartMasters = $cartMasters->where('type_id', request()->type_id);
        //     request()->post_type = 'group_invoices';
        // }

        // $cartMasters = $cartMasters->whereHas('userId', function (Builder $query) {
        //     if(request()->has('user_search') && !is_null(request()->user_search)){
        //         $query->where('name', 'like', '%'.request()->user_search.'%')
        //             ->orWhere('email', 'like', '%'.request()->user_search.'%')
        //             ->orWhere('mobile', 'like', '%'.request()->user_search.'%')
        //         ;
        //     }
        // });

        // if(request()->has('country_id') && request()->country_id!=-1)
        // {
        //     $cartMasters = $cartMasters->whereHas('userId.countries', function (Builder $query){
        //         $query->where('country_id', request()->country_id);
        //     });
        // }

        // // $cartMasters = $cartMasters->whereHas('carts', function (Builder $query){
        // //     // if(request()->has('course_id') && request()->course_id!=-1) {
        // //     //     $query->where('course_id', request()->course_id);
        // //     // }
        // //     // if(request()->has('session_id') && request()->session_id!=-1) {
        // //     //     $query->where('session_id', request()->session_id);
        // //     // }
        // //     // if(request()->has('training_option_id') && request()->training_option_id!=-1) {
        // //     //     $query->whereHas('trainingOption', function (Builder $query) {
        // //     //         $query->where('constant_id', request()->training_option_id);
        // //     //     });
        // //     // }
        // //     // if(!is_null(request()->session_from)) {
        // //     //     $query->whereHas('session', function (Builder $query) {
        // //     //         $query->whereDate('date_from', '>=', request()->session_from);
        // //     //     });
        // //     // }
        // //     // if(!is_null(request()->session_to)) {
        // //     //     $query->whereHas('session', function (Builder $query) {
        // //     //         $query->whereDate('date_from', '<=', request()->session_to);
        // //     //     });
        // //     // }
        // //     // if(request()->promo_code) {
        // //     //     $query->where('promo_code', '<>', '');
        // //     // }
        // //     // if(request()->has('category_id') && request()->category_id!=-1){
        // //     //     $query->whereHas('course.postMorphs', function (Builder $query){
        // //     //         $query->where('constant_id', request()->category_id);
        // //     //     });
        // //     // }
        // // });

        // if(request()->has('payment_status') && request()->payment_status!=-1) {
        //     if(request()->payment_status==332){
        //         $cartMasters = $cartMasters->doesntHave('payment');
        //     }
        //     elseif(request()->payment_status==68){
        //         $cartMasters = $cartMasters->whereHas('payment', function (Builder $query) {
        //             $query->whereIn('payment_status', [68, 376]);
        //             // $query->where('payment_status', request()->payment_status);
        //         });
        //     }
        //     else{
        //         $cartMasters = $cartMasters->whereHas('payment', function (Builder $query) {
        //             $query->where('payment_status', request()->payment_status);
        //         });
        //     }
        // }

        // $cartMasters = $cartMasters->orderBy('id', 'desc');
        // $cartMasters = $cartMasters->take(5)->page();

        // $post_type='cart';
        // $count = $cartMasters->count();

        // return view(Active::$namespace.'.'.Active::$folder.'.table', compact('cartMasters', 'count'
        //     , 'trash'));
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

    public function destroy($id){

        // Cart::where('id', $cart->id)->SoftTrash();
        // return Active::Deleted($cart->userId->trans_name);

        CartMaster::where('id', $id)->where('payment_status', 63)->SoftTrash();
        Cart::where('master_id', $id)->where('payment_status', 63)->SoftTrash();
        return 'deleted';
    }

    public function restore($id){
        // CartMaster::where('id', $id)->RestoreFromTrash();
        // return 'restored';
        // Cart::where('id', $cart)->RestoreFromTrash();
        // $cart = Cart::where('id', $cart)->first();
        // return Active::Restored($cart->userId->trans_name);
    }

    private function Validated($validated){

        $validated['updated_by'] = auth()->user()->id;
        return $validated;
    }

    public function exportYes()
    {
        return Excel::download(new RegistrationExport('Yes'), 'registration.xlsx');
    }

    public function exportNo()
    {
        return Excel::download(new RegistrationExport(null), 'registration.xlsx');
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

    private function insightsBody__main(){

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

        // =============== Insights per Category ======================================
        $totalPerCategories = Cart::join('users', 'users.id', 'carts.user_id')
        ->JoinInsights()
        ->select(DB::Raw('constants.id, constants.name, sum(payments.paid_in-payments.paid_out) as total, count(carts.id) as _count'))
        ->groupBy('constants.id','constants.name')
        ->get();
        // =============== End of Insights per Category ==================================

        // =============== Total Delivery Methods ========================================
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


    private function insightsBody__last(){

        $post_type = request()->post_type??'insight';

        $totalPerStatus_query = '
            payments.payment_status as payment_status
            , round(sum(carts.price), 2) as course_price
            , round(sum(carts.discount_value), 2) as discount
            , round(sum(carts.exam_price), 2) as exam_price
            , round(sum(carts.take2_price), 2) as take2_price
            , round(sum(carts.exam_simulation_price), 2) as exam_simulation_price
            , round(sum(carts.total), 2) as cart_total
            , round(sum(carts.vat_value), 2) as vat_value
            , round(sum(carts.total_after_vat), 2) as total_after_vat
            , round(sum(payments.paid_in-payments.paid_out), 2) as total
            , round(sum(cart_masters.total_after_vat), 2) as total_after_vat_cart_masters
            , round(sum(cart_masters.total_after_vat) - sum(payments.paid_in)) as diff
            , count(carts.id) as _count
        ';

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
                ->whereNotIn('categories.id', [378, 379, 375, 332]);
            })
            ->select(DB::Raw('categories.id, categories.name, '.$totalPerStatus_query))
            ->groupBy('categories.id', 'categories.name')
            ->get();
            // ->toSql();
            // foreach($totalPerStatus as $totalPerStatu){
            //     dump($totalPerStatu->name);
            // }
            // dd($totalPerStatus);
            // =============== End of Total Insights ===========================
            if(!request()->has('payment_status') || request()->payment_status==332 || request()->payment_status==-1)
            {
                // =============== Total Insights ==================================
                $freeSeats = Cart::join('users', 'users.id', 'carts.user_id')
                ->JoinInsights()
                ->where('carts.payment_status', 332)
                ->select(DB::Raw('332 as payment_status, "Free Seat" as name, 0 as total, count(carts.id) as _count, 0 as course_price, 0 as discount, 0 as exam_price, 0 as take2_price, 0 as vat_value, 0 as total_after_vat'))
                ->get();
                // dd($freeSeats);
                // =============== End of Total Insights ===========================
                $totalPerStatus = $totalPerStatus->merge($freeSeats);
            }

        if($post_type=='summary'){

            return compact('totalPerStatus');

            // // =============== Total Insights ==================================

                // $where = "and true";
                // $where .= " and carts.payment_status != 383";
                // $where .= " and carts.deleted_at is null";
                // $where .= " and carts.trashed_status is null";

                // $args = [];

                // $coin_id = 334;
                // if(request()->has('coin_id_insights') && request()->coin_id_insights!=-1){
                //     $coin_id = request()->coin_id_insights;
                // }
                // $where .= " and carts.coin_id = :coin_id";
                // $args = array_merge($args, ['coin_id' => $coin_id,]);

                // if(request()->has('course_id') && request()->course_id!=-1){
                //     $course_id = request()->course_id;
                //     $where .= " and carts.course_id = :course_id";
                //     $args = array_merge($args, ['course_id' => $course_id,]);
                // }
                // if(request()->has('session_id') && request()->session_id!=-1){
                //     $session_id = request()->session_id;
                //     $where .= " and carts.session_id = :session_id";
                //     $args = array_merge($args, ['session_id' => $session_id,]);
                // }

                // // if(request()->has('payment_status') && request()->payment_status!=-1){
                // //     $payment_status = request()->payment_status;
                // //     $where .= " and carts.payment_status = :payment_status";
                // //     $args = array_merge($args, ['payment_status' => $payment_status,]);
                // // }

                // if(request()->has('register_from') && !is_null(request()->register_from)){
                //     $register_from = request()->register_from;
                //     $where .= " and carts.registered_at >= :register_from";
                //     $args = array_merge($args, ['register_from' => $register_from,]);

                //     if(request()->has('register_to') && !is_null(request()->register_to)){
                //         $register_to = request()->register_to;
                //         $where .= " and carts.registered_at <= :register_to";
                //         $args = array_merge($args, ['register_to' => $register_to,]);
                //     }
                // }

                // $training_option_join = "";
                // if(request()->has('training_option_id') && request()->training_option_id!=-1){
                //     $training_option_join = " inner join training_options on training_options.id = carts.training_option_id";
                //     $training_option_id = request()->training_option_id;
                //     $where .= " and training_options.constant_id = :training_option_id";
                //     $args = array_merge($args, ['training_option_id' => $training_option_id,]);
                // }

                // $countries_join = "";
                // if(request()->has('country_id') && request()->country_id!=-1){
                //     $countries_join = " inner join users on users.id = carts.user_id left join constants as countries on countries.id = users.country_id";
                //     $country_id = request()->country_id;
                //     $where .= " and countries.id = :country_id";
                //     $args = array_merge($args, ['country_id' => $country_id,]);
                // }

                // $category_join = "";
                // // $postable_type = "'App\\Models\\Training\\Course'";
                // if(request()->has('category_id') && request()->category_id!=-1){
                //     $category_join = " inner join post_morphs on post_morphs.postable_id = carts.course_id";
                //     $category_id = request()->category_id;
                //     $where .= " and table_id IS NULL and postable_type like '%Course%' and post_morphs.constant_id = :category_id";
                //     $args = array_merge($args, ['category_id' => $category_id,]);
                // }

                // $sessions_join = "";
                // if(request()->has('session_from') && !is_null(request()->session_from)){
                //     $sessions_join = " inner join sessions on sessions.id = carts.session_id";
                //     $session_from = request()->session_from;
                //     $where .= " and sessions.date_from >= :session_from";
                //     $args = array_merge($args, ['session_from' => $session_from,]);

                //     if(request()->has('session_to') && !is_null(request()->session_to)){
                //         $session_to = request()->session_to;
                //         $where .= " and sessions.date_from <= :session_to";
                //         $args = array_merge($args, ['session_to' => $session_to,]);
                //     }
                // }

            //     // $totalPerStatus = DB::select( DB::raw("
            //     // SELECT ct.payment_status, constants.name as name
            //     // , round(sum(ct.price), 2) as course_price
            //     // , round(sum(ct.discount_value), 2) as discount
            //     // , round(sum(ct.exam_price), 2) as exam_price
            //     // , round(sum(ct.take2_price), 2) as take2_price
            //     // , round(sum(ct.exam_simulation_price), 2) as exam_simulation_price
            //     // , round(sum(ct.total), 2) as total
            //     // , round(sum(ct.vat_value), 2) as vat_value
            //     // , round(sum(ct.total_after_vat), 2) as total_after_vat
            //     // , round(sum(ps.paid_in), 2) as paid_in
            //     // , round(sum(cart_masters.total_after_vat), 2) as total_after_vat_cart_masters
            //     // , round(sum(cart_masters.total_after_vat) - sum(ps.paid_in)) as diff
            //     // , count(ct.master_id) as _count
            //     // FROM cart_masters
            //     // inner join (
            //     //     select carts.master_id,
            //     //     CASE WHEN carts.payment_status = 383 THEN 68
            //     //         WHEN carts.payment_status = 376 THEN 68
            //     //         WHEN carts.payment_status = 377 THEN 63
            //     //         ELSE carts.payment_status END AS payment_status
            //     //     , sum(carts.price) as price
            //     //     , sum(carts.discount_value) as discount_value
            //     //     , sum(carts.exam_price) as exam_price
            //     //     , sum(carts.take2_price) as take2_price
            //     //     , sum(carts.exam_simulation_price) as exam_simulation_price
            //     //     , sum(carts.total) as total
            //     //     , sum(carts.vat_value) as vat_value
            //     //     , sum(carts.total_after_vat) as total_after_vat
            //     //     from carts
            //     //     $training_option_join
            //     //     $countries_join
            //     //     $category_join
            //     //     $sessions_join
            //     //     $where
            //     //     group by master_id, payment_status
            //     // ) as ct on ct.master_id = cart_masters.id and cart_masters.deleted_at is null and cart_masters.user_id is not null
            //     // left join (
            //     //     select master_id,
            //     //     CASE WHEN payment_status = 377 THEN 68
            //     //         WHEN payment_status = 376 THEN 63
            //     //         ELSE payment_status END AS payment_status,
            //     //     sum(paid_in) as paid_in
            //     //     from payments
            //     //     group by master_id, payment_status
            //     // ) as ps on ps.master_id = cart_masters.id
            //     // inner join constants on ct.payment_status = constants.id
            //     // group by ct.payment_status
            //     // "), $args);

                // echo "
                // SELECT
                // CASE WHEN carts.payment_status = 383 THEN 68
                //         WHEN carts.payment_status = 376 THEN 68
                //         WHEN carts.payment_status = 377 THEN 63
                //         ELSE carts.payment_status END AS payment_status
                // , constants.name as name
                // , round(sum(carts.price), 2) as course_price
                // , round(sum(carts.discount_value), 2) as discount
                // , round(sum(carts.exam_price), 2) as exam_price
                // , round(sum(carts.take2_price), 2) as take2_price
                // , round(sum(carts.exam_simulation_price), 2) as exam_simulation_price
                // , round(sum(carts.total), 2) as total
                // , round(sum(carts.vat_value), 2) as vat_value
                // , round(sum(carts.total_after_vat), 2) as total_after_vat
                // , round(sum(payments.paid_in), 2) as paid_in
                // , round(sum(cart_masters.total_after_vat), 2) as total_after_vat_cart_masters
                // , round(sum(cart_masters.total_after_vat) - sum(payments.paid_in)) as diff
                // , count(carts.master_id) as _count
                // FROM carts as carts
                // inner join
                //     cart_masters as cart_masters on carts.master_id = cart_masters.id and cart_masters.deleted_at is null and cart_masters.user_id is not null and cart_masters.type_id=374

                //     $training_option_join
                //     $countries_join
                //     $category_join
                //     $sessions_join
                //     $where

                // left join (
                //     select payments.master_id,
                //     CASE WHEN payments.payment_status = 383 THEN 68
                //         WHEN payments.payment_status = 376 THEN 68
                //         WHEN payments.payment_status = 377 THEN 63
                //         ELSE payments.payment_status END AS payment_status,
                //     sum(payments.paid_in) as paid_in
                //     from payments
                //     group by payments.master_id, payments.payment_status
                // ) as payments on payments.master_id = cart_masters.id
                // inner join constants on carts.payment_status = constants.id
                // group by carts.payment_status
                // ";
            //     // dd('sssss');


            //     $totalPerStatus = DB::select( DB::raw("
            //     SELECT
            //     payments.payment_status
            //     , constants.name as name
            //     , round(sum(carts.price), 2) as course_price
            //     , round(sum(carts.discount_value), 2) as discount
            //     , round(sum(carts.exam_price), 2) as exam_price
            //     , round(sum(carts.take2_price), 2) as take2_price
            //     , round(sum(carts.exam_simulation_price), 2) as exam_simulation_price
            //     , round(sum(carts.total), 2) as total
            //     , round(sum(carts.vat_value), 2) as vat_value
            //     , round(sum(carts.total_after_vat), 2) as total_after_vat
            //     , round(sum(payments.paid_in), 2) as paid_in
            //     , round(sum(cart_masters.total_after_vat), 2) as total_after_vat_cart_masters
            //     , round(sum(cart_masters.total_after_vat) - sum(payments.paid_in)) as diff
            //     , count(carts.master_id) as _count
            //     FROM carts as carts
            //     inner join
            //         cart_masters as cart_masters on carts.master_id = cart_masters.id and cart_masters.deleted_at is null and cart_masters.user_id is not null

            //         $training_option_join
            //         $countries_join
            //         $category_join
            //         $sessions_join
            //         $where

            //     left join (
            //         select payments.master_id,
            //         CASE WHEN payments.payment_status = 383 THEN 68
            //              WHEN payments.payment_status = 376 THEN 68
            //              WHEN payments.payment_status = 377 THEN 63
            //              ELSE payments.payment_status
            //         END AS payment_status
            //         , sum(payments.paid_in) as paid_in
            //         from payments
            //         group by payments.master_id, payments.payment_status
            //     ) as payments on payments.master_id = cart_masters.id
            //     inner join constants on payments.payment_status = constants.id
            //     group by payments.payment_status
            //     "), $args);

            //     // where cart_masters.type_id=374
            //     // dd($training_option_join);
            //     // $someVariable = 1;
            //     // $results = DB::select( DB::raw("SELECT * FROM some_table WHERE some_col = :somevariable"), array(
            //     // 'somevariable' => $someVariable,
            //     // ));

            //         // dd($totalPerStatus);
            //         // =============== End of Total Insights ===========================
            //         // $totalPerStatus = $totalPerStatus->merge($totalPerStatus);


            //     return compact('totalPerStatus');

        }else{

            // post_type=='insight'

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

            // =============== Insights per Category ======================================
                $totalPerCategories = Cart::join('users', 'users.id', 'carts.user_id')
                ->JoinInsights()
                ->select(DB::Raw('constants.id, constants.name, sum(payments.paid_in-payments.paid_out) as total, count(carts.id) as _count'))
                ->groupBy('constants.id','constants.name')
                ->get();
            // =============== End of Insights per Category ==================================

            // =============== Total Delivery Methods ========================================
                $totalPerDeliveryMethods = Cart::join('users', 'users.id', 'carts.user_id')
                ->JoinInsights()
                ->select(DB::Raw('methods.id, methods.name, sum(payments.paid_in-payments.paid_out) as total, count(carts.id) as _count'))
                ->groupBy('methods.id','methods.name')
                ->get();
            // =============== End of Delivery Methods ===========================

            // =============== Total Delivery Sessions ==================================
                $totalPerSessions = Cart::join('users', 'users.id', 'carts.user_id')
                ->JoinInsights()
                ->select(DB::Raw('courses.id, sessions.id, sessions.date_from, sessions.date_to, courses.short_title, sum(payments.paid_in-payments.paid_out) as total, count(carts.id) as _count'))
                ->groupBy('courses.id', 'sessions.id', 'sessions.date_from', 'sessions.date_to', 'courses.short_title')
                ->orderBy('courses.id')
                ->orderBy('sessions.date_from')
                ->get();
            // =============== End of Delivery Sessions ===========================

            return compact('totalByCourses', 'totalByCountries', 'totalPerCategories', 'totalPerDeliveryMethods'
            , 'totalPerSessions'
            , 'totalPerStatus');

        }

    }


    private function insightsBody(){
        // =============== Total Insights ==================================

        $where = "Where true";
        $where .= " and payment_status != 383";
        $where .= " and carts.deleted_at is null";
        $where .= " and carts.trashed_status is null";

        $args = [];

        $coin_id = 334;
        if(request()->has('coin_id_insights') && request()->coin_id_insights!=-1){
            $coin_id = request()->coin_id_insights;
        }
        $where .= " and carts.coin_id = :coin_id";
        $args = array_merge($args, ['coin_id' => $coin_id,]);

        $courses_join = " inner join courses on courses.id = carts.course_id";
        if(request()->has('course_id') && request()->course_id!=-1){
            $course_id = request()->course_id;
            $where .= " and carts.course_id = :course_id";
            $args = array_merge($args, ['course_id' => $course_id,]);
        }
        if(request()->has('session_id') && request()->session_id!=-1){
            $session_id = request()->session_id;
            $where .= " and carts.session_id = :session_id";
            $args = array_merge($args, ['session_id' => $session_id,]);
        }

        if(request()->has('payment_status') && request()->payment_status!=-1){
            $payment_status = request()->payment_status;
            $where .= " and carts.payment_status = :payment_status";
            $args = array_merge($args, ['payment_status' => $payment_status,]);
        }

        if(request()->has('register_from') && !is_null(request()->register_from)){
            $register_from = request()->register_from;
            $where .= " and carts.registered_at >= :register_from";
            $args = array_merge($args, ['register_from' => $register_from,]);

            if(request()->has('register_to') && !is_null(request()->register_to)){
                $register_to = request()->register_to;
                $where .= " and carts.registered_at <= :register_to";
                $args = array_merge($args, ['register_to' => $register_to,]);
            }
        }

        // $training_option_join = "";
        $training_option_join = " inner join training_options on training_options.id = carts.training_option_id";
        $training_option_join .= " inner join constants as methods on methods.id = training_options.constant_id";
        if(request()->has('training_option_id') && request()->training_option_id!=-1){
            // $training_option_join = " inner join training_options on training_options.id = carts.training_option_id";
            $training_option_id = request()->training_option_id;
            $where .= " and training_options.constant_id = :training_option_id";
            $args = array_merge($args, ['training_option_id' => $training_option_id,]);
        }

        // $countries_join = "";
        $countries_join = " inner join users on users.id = carts.user_id";
        $countries_join .= "  inner join constants as countries on countries.id = users.country_id";
        if(request()->has('country_id') && request()->country_id!=-1){
            // $countries_join = " inner join users on users.id = carts.user_id left join constants as countries on countries.id = users.country_id";
            $country_id = request()->country_id;
            $where .= " and countries.id = :country_id";
            $args = array_merge($args, ['country_id' => $country_id,]);
        }

        // $category_join = "";
        $category_join = " inner join post_morphs on post_morphs.postable_id = carts.course_id and table_id IS NULL and postable_type like '%Course%'";
        $category_join .= " inner join constants as categories on categories.id = post_morphs.constant_id and categories.post_type = 'course'";
        if(request()->has('category_id') && request()->category_id!=-1){
            // $category_join = " inner join post_morphs on post_morphs.postable_id = carts.course_id";
            $category_id = request()->category_id;
            $where .= " and post_morphs.constant_id = :category_id";
            $args = array_merge($args, ['category_id' => $category_id,]);
        }

        $sessions_join = "";
        if(request()->has('session_from') && !is_null(request()->session_from)){
            $sessions_join = " inner join sessions on sessions.id = carts.session_id";
            $session_from = request()->session_from;
            $where .= " and sessions.date_from >= :session_from";
            $args = array_merge($args, ['session_from' => $session_from,]);

            if(request()->has('session_to') && !is_null(request()->session_to)){
                $session_to = request()->session_to;
                $where .= " and sessions.date_from <= :session_to";
                $args = array_merge($args, ['session_to' => $session_to,]);
            }
        }

        $m_select = '';
        $m_select_carts = '';
        $m_group = '';
        $order_by = '';

        if(request()->with_months == 'yes'){
            $m_select = "carts.reg_date,";
            $m_select_carts = "ANY_VALUE(DATE_FORMAT(carts.registered_at, '%Y-%m')) as reg_date,";
            $m_group = "carts.reg_date, ";
            $order_by = "Order By carts.reg_date DESC";
        }

        $Sql = "
        SELECT $m_select categories.id, categories.name
            , round(sum(carts.price), 2) as course_price
            , round(sum(carts.discount_value), 2) as discount
            , round(sum(carts.exam_price), 2) as exam_price
            , round(sum(carts.take2_price), 2) as take2_price
            , round(sum(carts.exam_simulation_price), 2) as exam_simulation_price
            , round(sum(carts.pract_exam_price), 2) as pract_exam_price
            , round(sum(carts.book_price), 2) as book_price
            , round(sum(carts.total), 2) as total
            , round(sum(carts.vat_value), 2) as vat_value
            , round(sum(carts.total_after_vat_cart), 2) as total_after_vat
            , round(sum(payments.paid_in), 2) as paid_in
            , round(sum(payments.paid_out), 2) as paid_out
            , round(sum(cart_masters.total_after_vat), 2) as total_after_vat_cart_masters
            , round(sum(cart_masters.total_after_vat) - sum(payments.paid_in)) as diff
            , count(carts.master_id) as _count
        FROM cart_masters
        INNER JOIN
        (
            SELECT $m_select_carts carts.master_id,
            CASE WHEN carts.payment_status = 383 THEN 68
                 WHEN carts.payment_status = 376 THEN 68
                 WHEN carts.payment_status = 377 THEN 63
                 ELSE carts.payment_status
            END AS payment_status
            , sum(carts.price) as price
            , sum(carts.discount_value) as discount_value
            , sum(carts.exam_price) as exam_price
            , sum(carts.take2_price) as take2_price
            , sum(carts.exam_simulation_price) as exam_simulation_price
            , sum(carts.pract_exam_price) as pract_exam_price
            , sum(carts.book_price) as book_price
            , sum(carts.total) as total
            , sum(carts.vat_value) as vat_value
            , (sum(carts.total_after_vat)-sum(carts.refund_value_after_vat)) as total_after_vat_cart
            FROM carts
                $training_option_join
                $countries_join
                $category_join
                $courses_join
                $sessions_join
                $where
            GROUP BY master_id, payment_status
        ) as carts on carts.master_id = cart_masters.id and cart_masters.deleted_at is null and cart_masters.user_id is not null
        LEFT JOIN
        (
            SELECT master_id,
                CASE WHEN payment_status = 383 THEN 68
                     WHEN payment_status = 376 THEN 68
                     WHEN payment_status = 377 THEN 63
                     ELSE payment_status
                END AS payment_status,
                sum(paid_in) as paid_in,
                sum(paid_out) as paid_out
            FROM payments
            GROUP BY master_id, payment_status
        ) as payments on payments.master_id = cart_masters.id
        INNER JOIN constants as categories on carts.payment_status = categories.id
        GROUP BY $m_group carts.payment_status $order_by
        ";

        $totalPerStatus = DB::select( DB::raw($Sql), $args);

        // echo request()->with_months.'<br><br><br><br>';
        // echo $Sql;
        // dd($totalPerStatus);
        $post_type = request()->post_type??'insight';

        // ========================================= Summary =========================================
        if($post_type=='summary' || $post_type=='summary_months'){

            return compact('totalPerStatus');

        }else{
            // ========================================= Insights =========================================
            // $post_type=='insight';

            // =============== Insights per Product ==================================
                $select_master_cart = 'carts.id, carts.name';
                $select_cart = ', courses.id, courses.short_title as name';
                $group_by = 'courses.id, courses.short_title';

                $totalByCourses = $this->totalByParts($select_master_cart, $select_cart, $group_by, $training_option_join, $countries_join, $category_join, $courses_join, $sessions_join, $where, $args);
            // =============== End of Insights per Product ===========================

            // =============== Total Delivery Methods = Training Options ==================================
                $select_master_cart = 'carts.id, carts.name';
                $select_cart = ', methods.id, methods.name as name';
                $group_by = 'methods.id, methods.name';

                $totalPerDeliveryMethods = $this->totalByParts($select_master_cart, $select_cart, $group_by, $training_option_join, $countries_join, $category_join, $courses_join, $sessions_join, $where, $args);
            // =============== End of Delivery Methods = Training Options ===========================

            // =============== Insights per Category ======================================
                $select_master_cart = 'carts.id, carts.name';
                $select_cart = ', categories.id, categories.name as name';
                $group_by = 'categories.id, categories.name';

                $totalPerCategories = $this->totalByParts($select_master_cart, $select_cart, $group_by, $training_option_join, $countries_join, $category_join, $courses_join, $sessions_join, $where, $args);
            // =============== End of Insights per Category ==================================

            // =============== Insights per Country ==================================
                $select_master_cart = 'carts.id, carts.name';
                $select_cart = ', countries.id, countries.name as name';
                $group_by = 'countries.id, countries.name';

                $totalByCountries = $this->totalByParts($select_master_cart, $select_cart, $group_by, $training_option_join, $countries_join, $category_join, $courses_join, $sessions_join, $where, $args);
            // =============== End of Insights per Country ================================

            return compact(
                'totalByCourses'
                , 'totalByCountries'
                , 'totalPerCategories'
                , 'totalPerDeliveryMethods'
                // , 'totalPerSessions'
                , 'totalPerStatus'
            );

        }
    }

    public function totalByParts($select_master_cart = '', $select_cart = '', $group_by = '', $training_option_join, $countries_join, $category_join, $courses_join, $sessions_join, $where, $args){

        $totalByParts = DB::select( DB::raw("
            SELECT $select_master_cart
            , round(sum(carts.price), 2) as course_price
            , round(sum(carts.discount_value), 2) as discount
            , round(sum(carts.exam_price), 2) as exam_price
            , round(sum(carts.take2_price), 2) as take2_price
            , round(sum(carts.exam_simulation_price), 2) as exam_simulation_price
            , round(sum(carts.pract_exam_price), 2) as pract_exam_price
            , round(sum(carts.book_price), 2) as book_price
            , round(sum(carts.total), 2) as total
            , round(sum(carts.vat_value), 2) as vat_value
            , round(sum(carts.total_after_vat_cart), 2) as total_after_vat
            , round(sum(payments.paid_in), 2) as paid_in
            , round(sum(payments.paid_out), 2) as paid_out
            , round(sum(cart_masters.total_after_vat), 2) as total_after_vat_cart_masters
            , round(sum(cart_masters.total_after_vat) - sum(payments.paid_in)) as diff
            , count(carts.master_id) as _count
            FROM cart_masters
            INNER JOIN
            (
                SELECT carts.master_id
                , sum(carts.price) as price
                , sum(carts.discount_value) as discount_value
                , sum(carts.exam_price) as exam_price
                , sum(carts.take2_price) as take2_price
                , sum(carts.exam_simulation_price) as exam_simulation_price
                , sum(carts.pract_exam_price) as pract_exam_price
                , sum(carts.book_price) as book_price
                , sum(carts.total) as total
                , sum(carts.vat_value) as vat_value
                , (sum(carts.total_after_vat)-sum(carts.refund_value_after_vat)) as total_after_vat_cart
                $select_cart
                FROM carts
                    $training_option_join
                    $countries_join
                    $category_join
                    $courses_join
                    $sessions_join
                    $where
                GROUP BY master_id, $group_by
            ) as carts on carts.master_id = cart_masters.id and cart_masters.deleted_at is null and cart_masters.user_id is not null

            LEFT JOIN
            (
                SELECT master_id,
                    sum(paid_in) as paid_in,
                    sum(paid_out) as paid_out
                FROM payments
                GROUP BY master_id
            ) as payments on payments.master_id = cart_masters.id
            GROUP BY $select_master_cart
            "), $args);

            return $totalByParts;
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

        // dd(request()->course_id);

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
        $send_email = null;
        if(isset($_GET['send_email'])){
            $send_email = $_GET['send_email'];
        }
        // dd($send_email);

        $cart = Cart::with(['session.trainingOption'])->findOrFail($cart_id);
        // dd($cart);

        // Need to have $master_id
        // app('App\Http\Controllers\Front\Education\LMSController')->run($cart);
        app('App\Http\Controllers\Front\Education\LMSController')->run($cart->master_id, $cart->id, $send_email);

        // Cart::where('id', $cart_id)->update(['lms_sent_at'=>now()]);

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

    public function monthly_registeration(Request $request)
    {
        $folder = 'registeration';
        $post_type = GetPostType('registeration');

        $all_courses = Course::GetAll();
        $delivery_methods = Constant::where('parent_id', 10)->get();
        $vats = Constant::where('post_type', 'vat')->orderBy('id', 'desc')->get();

        $with_vat = $request->with_vat;
        $s_from = $request->session_from;
        $s_to   = $request->session_to;

        $sql = "select  s.id,s.date_from,s.date_to,c.tr_option,c.course_title,c.tr_option,c.course_id,c.type_id,c.type_name
        from sessions s
        join (  ";
        if($with_vat == 468 || $with_vat == -1)
            $sql .= " select con.name as tr_option,cr.course_id,cr.session_id,co.title as course_title,cm.type_id,cons2.name as type_name";
        else
            $sql .= " select con.name as tr_option,cr.course_id,cr.session_id,co.title as course_title,cm.type_id,cons2.name as type_name";

        $sql .= " from carts cr join courses co on cr.course_id = co.id ";
                    if( $request->course_id >0)
                        $sql .= " and co.id = '".$request->course_id."' ";
        $sql .= " join training_options tro  on cr.training_option_id = tro.id
                    join constants con on con.id = tro.constant_id
                    join cart_masters cm on cm.id = cr.master_id
                    join constants cons2 on cons2.id = cm.type_id ";
                        if( $request->type_id >0)
                                $sql .= " and cm.type_id = '".$request->type_id."' ";
                        if($request->training_option_id > 0)
                            $sql .= " and tro.constant_id = '".$request->training_option_id."' ";
        $sql .= " where cr.trashed_status is null and cr.deleted_at is null";


        $sql .= " group by session_id,course_title,tr_option,course_id,con.name,cm.type_id,cons2.name
                ) c on c.session_id = s.id
                where s.trashed_status is null ";
        if($request->session_id != -1)
        $sql .= " and s.id = '".$request->session_id."' ";
        if($s_from != '')
        {
            $arr = explode('-',$s_from);
            $sql .= " and Month (date_from) >= ".$arr[0]." and year (date_from) >= ".$arr[1]." ";
        }
        if($s_to != '')
        {
            $arr = explode('-',$s_to);
            $sql .= " and Month (date_from) <= ".$arr[0]." and year (date_from) <= ".$arr[1]." ";
        }

        $sql  .= " ORDER BY s.id DESC ";

        $results = DB::select($sql);

        $session_array = null;
        Active::Link($post_type);

        $paginator = $this->GetPaginator($results);
        $results = $paginator->items();
        $types = Constant::where('parent_id', 371)->orderBy('id', 'desc')->get();

        return view('training.registeration.index', compact('types','post_type', 'results', 'folder','all_courses', 'session_array', 'delivery_methods','vats'
        ,'with_vat', 'paginator'));
    }
}

