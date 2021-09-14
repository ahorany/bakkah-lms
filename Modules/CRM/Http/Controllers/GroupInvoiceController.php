<?php

namespace Modules\CRM\Http\Controllers;

use App\User;
use App\Constant;
use App\Helpers\Active;
use App\Helpers\Models\Training\SessionHelper;
use App\Helpers\NumToWords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CRM\Entities\GroupInvoiceMaster;
use Modules\CRM\Entities\Organization;
use Modules\CRM\Http\Requests\GroupInvoiceAdminRequest;
use Modules\CRM\Http\Requests\GroupInvoiceRequest;
use Illuminate\Contracts\Support\Renderable;
use App\Models\Training\Course;
use App\Models\Training\Session;
use App\Models\Training\CartMaster;
use App\Models\Training\Cart;
use App\Models\Training\Payment;
use Modules\CRM\Http\Requests\RFPRequest;
use App\Helpers\Recaptcha;
use App\Http\Controllers\Front\Education\EpayController;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExcelImport;
use Illuminate\Support\Facades\DB;
use App\Imports\UsersImport;
use App\Mail\InvoiceMaster;
use Illuminate\Support\Facades\Mail;

// docx
// composer require phpoffice/phpword
// https://stackoverflow.com/questions/49254352/pass-dynamic-values-when-export-to-docx-using-phpword-in-laravel
class GroupInvoiceController extends Controller
{
    // public $master_id_field = 'master_id';
    public function __construct()
    {
        // $this->middleware('auth')->except(['', '']);
        Active::$namespace = null;
        Active::$folder = 'crm::group_invoices';
    }

    public function index()
    {
        $trash = GetTrash();
        $post_type = GetPostType();

        $data = $this->GetInvoice();
        $all_courses = $this->GetAllCourses();
        $session_array = null;

        $cartMasters = CartMaster::with(['rfpGroup', 'rfpGroup.organization', 'carts.userId', 'carts.course', 'rfpGroup.session.trainingOption.course', 'rfpGroup.userId']);

        if(request()->has('type_id') && request()->type_id != -1){
            $cartMasters = $cartMasters->where('type_id', request()->type_id);
        }

        if(request()->has('organization_id') && request()->organization_id != -1){
            $cartMasters = $cartMasters->whereHas('rfpGroup.organization', function(Builder $query){
                $query->where('id', request()->organization_id);
            });
        }

        if(!is_null(request()->invoice_number)) {
            $cartMasters = $cartMasters->where('invoice_number', 'like', '%'.request()->invoice_number.'%');
        }

        if(request()->has('owner_user_id') && request()->owner_user_id != -1){
            $cartMasters = $cartMasters->whereHas('rfpGroup', function (Builder $query) {
                $query->where('owner_user_id', request()->owner_user_id);
            });
        }

        if(request()->has('status_id') && request()->status_id != -1){
            $cartMasters = $cartMasters->where('status_id', request()->status_id);
        }

        if(request()->has('payment_status') && request()->payment_status != -1){
            $cartMasters = $cartMasters->where('payment_status', request()->payment_status);
        }

        if(!is_null(request()->follow_up_date_from)) {
            $cartMasters = $cartMasters->whereHas('rfpGroup', function (Builder $query) {
                $query->whereDate('follow_up_date', '>=', request()->follow_up_date_from);
            });
        }
        if(!is_null(request()->follow_up_date_to)) {
            $cartMasters = $cartMasters->whereHas('rfpGroup', function (Builder $query) {
                $query->whereDate('follow_up_date', '<=', request()->follow_up_date_to);
            });
        }
        $cartMasters = $cartMasters->orderByDesc('id');

        //  dump(request()->type_id);
        // dd($cartMasters->toSql());

        // $cartMasters = $cartMasters->get();
        // // dd($cartMasters->toSql());
        // foreach($cartMasters as $cartMaster){
        //     dump($cartMaster);
        // }
        // dd('sxxxxx');

        $count = $cartMasters->count();

        // $cartMasters = $cartMasters->page();
        $cartMasters = $cartMasters->paginate(PAGINATE);

        // $cartMasters = $cartMasters->get();

        // dd($cartMasters);
        $args = array_merge($data, [
            'post_type'=>$post_type,
            'count'=>$count,
            'trash'=>$trash,
            'cartMasters'=>$cartMasters,
            'all_courses'=>$all_courses,
            'session_array'=>$session_array,
        ]);
        return Active::Index($args);
    }

    public function create()
    {
        $post_type = GetPostType();
        $all_courses = $this->GetAllCourses();
        $session_array = null;
        $data = $this->GetInvoice();

        $args = array_merge($data, [
            'post_type'=>$post_type,
            'all_courses'=>$all_courses,
            'session_array'=>$session_array,
        ]);

        return Active::Create($args);
    }

    public function store(GroupInvoiceAdminRequest $request)
    {
        // dd($request);
        $validated = $request->validated();
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;
        // $validated['owner_user_id'] = auth()->user()->id;
        $invoice_number = 'G-'.date("His").rand(1234, 9632); //??uniqid()

        $organization = Organization::find(request()->organization_id??null);
        $user = User::where('email', $organization->email??null)->first();

        $cart_master = CartMaster::create([
            'type_id' => request()->type_id,
            'invoice_amount' => request()->invoice_amount,
            'user_id' => $user->id??null,
            'status_id' => request()->status_id,
            'payment_status' => request()->payment_status,
            'invoice_number' => $invoice_number, //??uniqid(),
            'accounting_sys_invoice' => request()->accounting_sys_invoice,
            'reference' => request()->reference,
            'tax_number' => request()->tax_number,
            'due_date' => request()->due_date,
            // 'invoice_sent_date' => $validated['invoice_sent_date'],
            // 'payment_date' => $validated['payment_date'],
            // 'payment_sent' => $validated['payment_sent'],
            'payment_sentmail' => request()->payment_sentmail,
        ]);

        $validated['master_id'] = $cart_master->id;
        GroupInvoiceMaster::create($validated);

        return Active::Inserted($invoice_number, ['post_type'=>request()->post_type,'type_id'=>request()->type_id]);
    }

    public function edit($cart_master_id)
    {
        $data = $this->GetInvoice();
        $all_courses = $this->GetAllCourses();

        $CartMast = CartMaster::findOrFail($cart_master_id);
        if(!is_null($CartMast) && $CartMast->type_id==372){  //  // Group
            request()->post_type = 'group_invoices';
        }

        $GetTotals = $this->GetTotals($cart_master_id);
        $cartMaster = $GetTotals['cartMaster'];
        // dd($cartMaster);

        $session_array = null;

       $args = array_merge($data, [
        'eloquent'=>$cartMaster,
        'cartMaster'=>$cartMaster,
        'all_courses'=>$all_courses,
        'session_array'=>$session_array,
        'count'=>$GetTotals['count'],
        'total'=>$GetTotals['masterTotals']['total'],
        'vat_value'=>$GetTotals['masterTotals']['vat_value'],
        'total_after_vat'=>$GetTotals['masterTotals']['total_after_vat'],
        'coin_id'=>$GetTotals['coin_id'],
        'vat'=>$GetTotals['vat'],
       ]);

       return Active::Edit($args);
    }

    private function GetInvoice(){
        $users = User::where('user_type', 315)->orderby('name')->get(); //employee
        $types = Constant::where('parent_id', 371)->where('id', '<>', 374)->get();
        $status = Constant::where('parent_id', 354)->get();
        $payment_status = Constant::where('parent_id', 62)->get();
        $organizations = Organization::select('title','id')->get();
        return compact('users', 'types', 'status', 'organizations', 'payment_status');
    }

    private function GetTotals($master_id){

        // , 'notes.user'
        $cartMaster = CartMaster::with(['rfpGroup', 'rfpGroup.organization', 'userId', 'carts.userId', 'carts.cartFeatures.trainingOptionFeature.feature', 'carts.course', 'carts.session', 'rfpGroup.session.trainingOption.course', 'rfpGroup.userId'])->findOrFail($master_id);

        if(!is_null($cartMaster)){

            // $master_id__field = 'carts.master_id';
            // if($cartMaster->type_id==372){  // Group
            //     request()->post_type = 'group_invoices';
            //     $master_id__field = 'carts.group_invoice_id';
            //     $cartMaster = CartMaster::with(['rfpGroup', 'rfpGroup.organization', 'userId', 'carts.userId', 'carts.cartFeatures.feature', 'carts.course', 'carts.session', 'rfpGroup.session.trainingOption.course', 'rfpGroup.userId'])->findOrFail($master_id);
            // }

            $totalPerStatus_query = "sum(carts.total) as total, sum(carts.vat_value) as vat_value, sum(carts.total_after_vat) as total_after_vat";

            $masterTotals = CartMaster::join('carts', 'cart_masters.id', 'carts.master_id')
                                ->select(DB::Raw($totalPerStatus_query))
                                ->whereNull('carts.deleted_at')
                                ->find($master_id);

            $count = $cartMaster->carts->count()??0;
            $vat = $cartMaster->vat??0;
            $coin_id = $cartMaster->coin_id??334;
            $currency = ($cartMaster->coin_id==334) ? 'SAR' : 'USD';

            // dd(request()->post_type);
            // dd($cartMaster);

            return compact('cartMaster', 'count', 'masterTotals', 'coin_id', 'vat', 'currency');

        }else{
            return null;
        }
    }

    public function update(GroupInvoiceAdminRequest $request, $id)
    {
        $validated = $request->validated();
        $validated['updated_by'] = auth()->user()->id;

        // GroupInvoiceMaster::where('master_id', $id)->update($validated);
        GroupInvoiceMaster::updateOrCreate(['master_id' => $id], $validated);

        CartMaster::where('id', request()->master_id)
            ->update([
                'type_id' => request()->type_id,
                'invoice_amount' => request()->invoice_amount,
                'status_id' => request()->status_id,
                'payment_status' => request()->payment_status,
                'invoice_number' => request()->invoice_number,
                'accounting_sys_invoice' => request()->accounting_sys_invoice,
                'reference' => request()->reference,
                'tax_number' => request()->tax_number,
                'due_date' => request()->due_date,
                'payment_sentmail' => request()->payment_sentmail,
            ]);

            $excel_file = $request->excel_file;
            if($excel_file)
            {
                Excel::import(new ExcelImport(request()->organization_id, request()->rfp_group_id, request()->master_id, request()->course_id, request()->session_id), $excel_file);
            }

            $this->updatePrices_edit();

            if(request()->type_id == 370){ // B2B only
                $invoice_amount = request()->invoice_amount??0;
                if($invoice_amount > 0){
                    $count = Cart::where('master_id', $id)->count();
                    if($count > 0)
                    {
                        $vat = (GetCoinId()==334) ? 15 : 0;
                        $total_price_before_vat = ($invoice_amount * 100) / (100 + $vat);
                        $price = $total_price_before_vat / $count;
                        $total = $price;
                        $vat_value = ($price * $vat) / 100;
                        $total_after_vat = $total + $vat_value;

                        Cart::where('master_id', request()->master_id)->update([
                            'price' => NumberFormat($price),
                            'discount_id' => null,
                            'discount' => 0,
                            'discount_value' => 0,
                            'exam_price' => 0,
                            'take2_price' => 0,
                            'exam_simulation_price' => 0,
                            'total' => NumberFormat($total),
                            'vat' => NumberFormat($vat),
                            'vat_value' => NumberFormat($vat_value),
                            'total_after_vat' => NumberFormat($total_after_vat),
                        ]);

                        GroupInvoiceMaster::where('master_id', request()->master_id)->update([
                            'price' => NumberFormat($price),
                            'discount_id' => null,
                            'discount' => 0,
                            'discount_value' => 0,
                            'exam_price' => 0,
                            'take2_price' => 0,
                            'exam_simulation_price' => 0,
                            'total' => NumberFormat($total),
                            'vat' => NumberFormat($vat),
                            'vat_value' => NumberFormat($vat_value),
                            'total_after_vat' => NumberFormat($total_after_vat),
                        ]);

                        $GetTotals = $this->GetTotals(request()->master_id);
                        $masterTotals = $GetTotals['masterTotals'];
                        $vat = $GetTotals['vat'];
                        // $cartMaster = $GetTotals['cartMaster'];
                        // $count = $GetTotals['count'];
                        // $coin_id = $GetTotals['coin_id'];
                        // $currency = $GetTotals['currency'];

                        CartMaster::updateOrCreate([
                            'id'=>request()->master_id,
                        ],[
                            'total' => NumberFormat($masterTotals->total),
                            'vat' => NumberFormat($vat),
                            'vat_value' => NumberFormat($masterTotals->vat_value),
                            'total_after_vat' => NumberFormat($masterTotals->total_after_vat),
                        ]);

                        if(request()->payment_status == 332){ // Free
                            $payment = Payment::where('master_id', request()->master_id)->first();
                            if(!is_null($payment->id??null)){
                                Payment::where('id', $payment->id)->forceDelete();
                                // Payment::findOrFail($payment->id)->delete();
                            }

                            CartMaster::updateOrCreate([
                                'id'=>request()->master_id,
                                // 'type_id'=>request()->type_id,
                            ],[
                                'total' => 0,
                                'vat' => 0,
                                'vat_value' => 0,
                                'total_after_vat' => 0,
                                'payment_status' => 332,
                                // 'coin_id'=>$coin_id,
                                // 'coin_price'=>$coin_price,
                            ]);

                        }else{

                            Payment::updateOrCreate([
                                'master_id'=>request()->master_id,
                                // 'type_id'=>request()->type_id,
                            ],[
                                'paid_in'=>NumberFormat($masterTotals->total_after_vat),  // $invoice_amount
                                'payment_status'=>request()->payment_status??0,
                                'user_id'=>request()->user_id??null,
                                // 'type_id'=>request()->type_id,
                                // 'description'=>'',
                                // 'paid_at'=>DateTimeNow(),
                            ]);

                            if(request()->payment_status == 68) {
                                Cart::updateOrCreate([
                                    'master_id'=>request()->master_id,
                                ],[
                                    'payment_status'=>68,
                                    'registered_at'=>DateTimeNow(),
                                ]);
                            }
                        }

                    }
                }
            }

        Active::Flash(__('education.Updated'), __('education.Data updated successfully'),'success');
        return back();
    }

    public function destroy($id)
    {
        $group = CartMaster::findOrFail($id);
        CartMaster::where('id', $group->id)->SoftTrash();
        return Active::Deleted($group->invoice_number);
    }

    public function restore($CartMaster){
        CartMaster::where('id', $CartMaster)->RestoreFromTrash();

        $CartMaster = CartMaster::where('id', $CartMaster)->first();
        return Active::Restored($CartMaster->invoice_number);
    }

    // =================== Start Frontend Functions =======================
    private function GetAllCourses(){
        $all_courses = Course::has('trainingOptions.sessions')
            // ->where('active',1)
            ->with('trainingOptions.sessions')
            ->orderBy('order')
            ->get();
        return $all_courses;
    }

    public function sessionsJson(){

        $sessions = Session::whereNotNull('id');
        if(request()->has('course_id') && request()->course_id!=-1 && request()->course_id!=-0){
            $sessions = $sessions->whereHas('trainingOption.course', function (Builder $query){
                $query->where('course_id', request()->course_id);
                    // ->whereDate('date_from', '>=', now())
                    // ->where('session_start_time', '>=', DateTimeNowAddHours());
            });
        }
        $sessions = $sessions->with('trainingOption.course')->orderByDesc('date_from')->get();

        $session_array = [];
        foreach($sessions as $session){
            // $trining_short_name = $session->course->trining_short_name??null;
            $trining_option_name = $session->trainingOption->constant->trans_name??null;
            array_push($session_array, [
                'id'=>$session->id,
                'json_title'=>$session->published_from.'-'.$session->published_to.' | '.$trining_option_name,
            ]);
        }
        return json_encode($session_array);

        /*
        $sessions = Session::whereNotNull('id');
        if(request()->has('course_id') && request()->course_id!=-1 && request()->course_id!=-0){
            $sessions = $sessions->whereHas('trainingOption.course', function (Builder $query){
                $query->where('course_id', request()->course_id)
                ->where(function($query){
                    $query->whereDate('sessions.date_from', '>=', now())
                    ->where('sessions.session_start_time', '>=', DateTimeNowAddHours());
                });

                if(request()->has('val_session_id')){
                    if(!is_null(request()->val_session_id) && !empty(request()->val_session_id)){
                        $query->orWhere('sessions.id', request()->val_session_id);
                    }
                }
                // ->whereDate('date_from', '>=', now())
                // ->where('session_start_time', '>=', DateTimeNowAddHours());
            });
        }
        $sessions = $sessions->with(['trainingOption.course'=>function($query){
            $query->where('course_id', request()->course_id);
        }])->orderBy('date_from')->get();
        // $sessions = $sessions->with('trainingOption.course')->orderBy('date_from')->get();2
        */
        // $sessions = Session::whereNotNull('id');
        // // request()->course_id = 1;
        // // request()->val_session_id = 473;
        // $sessions = $sessions->whereHas('trainingOption.course', function (Builder $query){
        //     $query->where('course_id', request()->course_id);
        // });
        // $sessions = $sessions->where(function($query){
        //     $query->whereDate('date_from', '>=', now())
        //     ->where('session_start_time', '>=', DateTimeNowAddHours());
        // });

        // if(request()->has('val_session_id'))
        // {
        //     if(!is_null(request()->val_session_id) && !empty(request()->val_session_id)){
        //         $sessions = $sessions->orWhere('id', request()->val_session_id);
        //     }
        // }
        // $sessions = $sessions->with('trainingOption.course')
        // ->orderBy('date_from')
        // ->get();
        // // $sessions = $sessions->with('trainingOption.course')->orderBy('date_from')->get();

        // $session_array = [];
        // foreach($sessions as $session){
        //     // $trining_short_name = $session->course->trining_short_name??null;
        //     $trining_option_name = $session->trainingOption->constant->trans_name??null;
        //     array_push($session_array, [
        //         'id'=>$session->id,
        //         'json_title'=>$session->published_from.'-'.$session->published_to.' | '.$trining_option_name,
        //     ]);
        // }
        // return json_encode($session_array);
    }

    public function SessionsDetailsJson(){

        if(request()->has('session_id') && request()->session_id != -1){

            request()->training_option_id = -1;
            $SessionHelper = new SessionHelper(334);
            $session = $SessionHelper->TrainingOption([0, 1])->where('session_id', request()->session_id)->first();
            // $courses = $SessionHelper->Query('leftJoin', true);

            // $courses = $courses->where('courses.id', 150);
            // $courses = $courses->orderBy('sessions.date_from', 'asc')
            // ->get();
            // ->toArray();
            // dd($courses);

            if(!is_null($session)){
                request()->training_option_id = $session->training_option_id;
                $SessionHelper->SetCourse($session);

                $vat = $SessionHelper->VAT();
                $session_exam_price = $SessionHelper->ExamPrice();
                $discount_value = $SessionHelper->DiscountValue();
                $vat_value = $SessionHelper->VATFortPriceWithExamPrice();
                $sub_total = $SessionHelper->PriceWithExamPrice();
                $total_after_vat = $SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT();

                return json_encode([
                    'training_option_id' => $session->training_option_id,
                    'price' => NumberFormat($session->session_price),
                    'discount_id' => $session->discount_id,
                    'discount' => NumberFormat($session->discount_value),
                    'discount_value' => NumberFormat($discount_value),
                    // 'promo_code' => $session->code,
                    'exam_price' => NumberFormat($session_exam_price),
                    'take2_price' => NumberFormat($session->take2_price),
                    'exam_simulation_price' => NumberFormat($session->exam_simulation_price),
                    'total' => NumberFormat($sub_total),
                    'vat' => NumberFormat($vat),
                    'vat_value' => NumberFormat($vat_value),
                    'total_after_vat' => NumberFormat($total_after_vat),
                ]);
            }
        }
        return null;
        // return json_encode($session);
    }


    public function register(){

        $all_courses = $this->GetAllCourses();
        $session_array = null;
        $route = route('crm::rfq.register.submit');

        return view('crm::front.register.index', compact('all_courses', 'session_array', 'route'));
    }

    public function autofill(){
        if(request()->has('email')){
            $organization = Organization::where('email', request()->email)
                ->select('title', 'job_title', 'mobile', 'name', 'job_title', 'address')
                ->first();
            if($organization) {
                return [
                    'title' => $organization->trans_title??'',
                    'job_title' => $organization->job_title??'',
                    'mobile' => $organization->mobile??'',
                    'name' => $organization->trans_name??'',
                    'job_title' => $organization->job_title??'',
                    'address' => $organization->address??'',
                ];
            }
            return [
                'title' => '',
                'job_title' => '',
                'mobile' => '',
                'name' => '',
                'job_title' => '',
                'address' => '',
            ];
        }
    }

    public function registerStore(RFPRequest $request){

        if(!Recaptcha::run()) { return back(); }

        $validated = $request->validated();

        $type_id = 373; // Request For Proposal

        $data_org = json_encode([
            'en'=>$validated['en_title'],
            'ar'=>$validated['en_title'],
        ], JSON_UNESCAPED_UNICODE);

        $data_manager = json_encode([
            'en'=>$validated['en_name'],
            'ar'=>$validated['en_name']
        ], JSON_UNESCAPED_UNICODE);

        $args = [
            'title'=>$data_org, //Organization Names
            'mobile'=>$validated['mobile'],
            'address'=>$validated['address'],
            'job_title'=>$validated['job_title'],
            'name'=>$data_manager, // Manager Name
        ];

        $organization = Organization::updateOrCreate([
            'email'=>$validated['email'],
        ], $args);

        if(request()->has('email') && !is_null(request()->email)){
            $cart_master = CartMaster::create([
                'type_id'=>$type_id,
                'status_id' => 355, // Pending
                'payment_status' => 63, // Not Completed
                'invoice_number' => 'G-'.date("His").rand(1234, 9632),
            ]);
            $cart_master_id = $cart_master->id??0;

            if ($cart_master->exists) { // if added

                $rfp_group = $cart_master->rfpGroup()->create([
                    'master_id'=>$cart_master_id,
                    'organization_id'=>$organization->id,
                    'course_id' => $validated['course_id'],
                    'session_id' => $validated['session_id'],
                ]);

                if ($rfp_group->exists) { // if added

                    $args = [
                        'name'=>$data_manager,
                        'job_title'=>$validated['job_title']??null,
                        'company'=>$validated['en_title']??null, //$data_org,
                        'mobile'=>$validated['mobile']??null,
                        'user_type'=>41,
                        'locale'=>app()->getLocale(),
                    ];
                    $user = User::updateOrCreate([
                        'email'=>$validated['email'],
                    ], array_merge($args));
                    $user_id = $user->id??0;

                    CartMaster::updateOrCreate([
                        'id'=>$cart_master_id,
                    ],[
                        'user_id'=>$user_id,
                    ]);

                    $excel_file = $request->excel_file;
                    if($excel_file)
                    {
                        Excel::import(new ExcelImport($organization->id, $rfp_group->id, $cart_master_id, request()->course_id, request()->session_id), $excel_file);
                    }

                    $this->updatePrices_front($validated['course_id'], $validated['session_id'], $cart_master_id, $type_id);

                    Active::Flash(__('education.Success'), __('education.Your submit was submitted successfully'),'success');
                    return redirect()->route('crm::rfq.register.edit', ['id'=>$cart_master_id]);
                }
                return back();
            }
        }

        return back();
    }

    public function deleteCandidates() {
        // return request()->cart_id;
        Cart::where('id', request()->cart_id)->whereNotIn('payment_status', [68])->SoftTrash();
        // echo 'yess';
        return null;
    }

    public function storeCandidate() {
        // 0824494127
        //
        if(request()->has('master_id')){
            // dd(request()->crm_invoice_number);
            $cartMaster = CartMaster::with(['rfpGroup.organization', 'carts.userId'])->findOrFail(request()->master_id);

            // dd($cartMaster);

            // $master_id__field = ($cartMaster->type_id==372)?'group_invoice_id':'master_id';
            // request()->cartMaster__type_id = 372;

            // $cartMasterCandidate = CartMaster::where('invoice_number', request()->crm_invoice_number)->first();
            // dump($cartMasterCandidate->id);
            // $cartUpdate = Cart::where('invoice_number', request()->crm_invoice_number)->update([
            //     $master_id__field => request()->master_id,
            //     'payment_status'=>request()->payment_status??0,
            // ]);

            // dd()

            // dump($master_id__field);
            // dd($cartMaster->id);

            $cartCandidate = Cart::where('invoice_number', request()->crm_invoice_number)->first();
            // dd($cartCandidate);

            if(!is_null($cartCandidate)){

                $group = GroupInvoiceMaster::where('master_id',request()->master_id)->first();
                // dd($group);
                request()->session_id = $group->session_id;
                $data = $this->SessionsDetailsJson();

                // 'training_option_id' => $session->training_option_id,
                //     'price' => NumberFormat($session->session_price),
                //     'discount_id' => $session->discount_id,
                //     'discount' => NumberFormat($session->discount_value),
                //     'discount_value' => NumberFormat($discount_value),
                //     // 'promo_code' => $session->code,
                //     'exam_price' => NumberFormat($session_exam_price),
                //     'take2_price' => 0,
                //     'exam_simulation_price' => NumberFormat($session->exam_simulation_price),
                //     'total' => NumberFormat($sub_total),
                //     'vat' => NumberFormat($vat),
                //     'vat_value' => NumberFormat($vat_value),
                //     'total_after_vat' => NumberFormat($total_after_vat),

                // dd($data());


                $cartUpdate = Cart::where('invoice_number', request()->crm_invoice_number)->update([
                    'master_id' => request()->master_id,
                    // 'payment_status'=>request()->payment_status??0,
                ]);

                // $coin_id = $cartCandidate->coin_id??334;
                // $coin_price = $cartCandidate->coin_price??1;

                $GetTotals = $this->GetTotals(request()->master_id);
                $masterTotals = $GetTotals['masterTotals'];
                $vat = $GetTotals['vat'];
                // $cartMaster = $GetTotals['cartMaster'];
                // $count = $GetTotals['count'];
                // $coin_id = $GetTotals['coin_id'];
                // $currency = $GetTotals['currency'];

                CartMaster::updateOrCreate([
                    'id'=>request()->master_id,
                    // 'type_id'=>request()->type_id,
                ],[

                    'total' => NumberFormat($masterTotals->total),
                    'vat' => NumberFormat($vat),
                    'vat_value' => NumberFormat($masterTotals->vat_value),
                    'total_after_vat' => NumberFormat($masterTotals->total_after_vat),
                    // 'coin_id'=>$coin_id,
                    // 'coin_price'=>$coin_price,
                ]);



                // ---------
                if($cartMaster->payment_status == 332){ // Free
                    $payment = Payment::where('master_id', request()->master_id)->first();
                    if(!is_null($payment->id??null)){
                        Payment::where('id', $payment->id)->forceDelete();
                        // Payment::findOrFail($payment->id)->delete();
                    }

                    CartMaster::updateOrCreate([
                        'id'=>request()->master_id,
                        // 'type_id'=>request()->type_id,
                    ],[
                        'total' => 0,
                        'vat' => 0,
                        'vat_value' => 0,
                        'total_after_vat' => 0,
                        'payment_status' => 332,
                        // 'coin_id'=>$coin_id,
                        // 'coin_price'=>$coin_price,
                    ]);

                }else{

                    $CartMaster_user_id = CartMaster::where('id', request()->master_id)->select('user_id,payment_status')->first();

                    Payment::updateOrCreate([
                        'master_id'=>request()->master_id,
                    ],[
                        'paid_in'=>NumberFormat($masterTotals->total_after_vat),
                        'payment_status'=>$CartMaster_user_id->payment_status??0,
                        // 'payment_status'=>request()->payment_status??0,
                        'user_id'=>$CartMaster_user_id->user_id??null,
                        // 'type_id'=>request()->type_id,
                        // 'description'=>'',
                        // 'paid_at'=>DateTimeNow(),
                    ]);

                    if($cartMaster->payment_status == 68) {
                        Cart::updateOrCreate([
                            'master_id'=>request()->master_id,
                        ],[
                            'payment_status'=>68,
                            'registered_at'=>DateTimeNow(),
                        ]);
                    }
                }
                // ---------

                return view('crm::group_invoices.candidates', [
                    'eloquent'=>$cartMaster,
                    'cartMaster'=>$cartMaster,
                ])->render();
            }else{
                return 'fail';
            }
        }else{
            return 'fail';
        }

    }


    public function registerEdit($id){
        $cartMaster = CartMaster::with(['rfpGroup.organization', 'carts.userId'])->findOrFail($id);
        $all_courses = $this->GetAllCourses();
        $route = route('crm::rfq.register.update', ['id'=>$cartMaster->id]);
        return view('crm::front.register.index', compact('cartMaster', 'all_courses', 'route'));
    }

    public function registerUpdate(RFPRequest $request, $id){

        if(!Recaptcha::run()) { return back(); }

        if(isset($id) && !is_null($id)){

            $validated = $request->validated();

            $type_id = 373; // Request For Proposal

            $data_org = json_encode([
                'en'=>$validated['en_title'],
                'ar'=>$validated['en_title']
            ], JSON_UNESCAPED_UNICODE);

            $data_manager = json_encode([
                'en'=>$validated['en_name'],
                'ar'=>$validated['en_name']
            ], JSON_UNESCAPED_UNICODE);

            $args = [
                'title'=>$data_org,
                'mobile'=>$validated['mobile'],
                'address'=>$validated['address'],
                'job_title'=>$validated['job_title'],
                'name'=>$data_manager,
            ];

            $organization = Organization::where('email', $validated['email'])->first();
            if(!is_null($organization)){
                Organization::where('id', $organization->id)->update($args);
            }

                $cart_master = CartMaster::find($id);
                $cart_master_id = $cart_master->id??0;

                if(!is_null($cart_master) && request()->has('email') && !is_null(request()->email)) { // if exist

                    $user = User::where('email', $validated['email'])->first();
                    $args = [
                        'name'=>$data_manager,
                        'job_title'=>$validated['job_title']??null,
                        'company'=>$validated['en_title']??null, //$data_org,
                        'mobile'=>$validated['mobile'],
                        'user_type'=>41,
                        'locale'=>app()->getLocale(),
                    ];
                    if(is_null($user)){
                        $user = User::create(array_merge($args, [
                            'email'=>$validated['email'],
                        ]));
                    }

                    //$user_id = $user->id??0;

                    $this->updatePrices_front($validated['course_id'], $validated['session_id'], $cart_master_id, $type_id);

                    // ==== updated ====
                    Active::Flash(__('education.Success'), __('education.Your submit was submitted successfully'),'success');
                    return redirect()->route('crm::rfq.register.edit', ['id'=>$cart_master_id]);
                }

        }

        return back();
    }

    public function updatePrices_front($course_id, $session_id, $cart_master_id, $type_id){

        $GetTotals = $this->GetTotals($cart_master_id);
        $masterTotals = $GetTotals['masterTotals'];
        $vat = $GetTotals['vat'];
        // $cartMaster = $GetTotals['cartMaster'];
        // $count = $GetTotals['count'];
        $coin_id = $GetTotals['coin_id'];
        // $currency = $GetTotals['currency'];

        $coin_price = ($coin_id==335) ? 3.8 : 1;

        // $coin_id = GetCoinId();
        // $coin_price = GetCoinPrice();

        $SessionHelper = new SessionHelper();
        $session = $SessionHelper->TrainingOption()->where('session_id', $session_id)->first();
        $SessionHelper->SetCourse($session);

        // $Discount = $SessionHelper->Discount();
        $vat = $SessionHelper->VAT();
        $session_exam_price = $SessionHelper->ExamPrice();
        $discount_value = $SessionHelper->DiscountValue();
        $vat_value = $SessionHelper->VATFortPriceWithExamPrice();
        $sub_total = $SessionHelper->PriceWithExamPrice();
        $total_after_vat = $SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT();

        GroupInvoiceMaster::where('master_id', $cart_master_id)->update([
            // 'organization_id' => request()->organization_id,
            'course_id' => $course_id,
            'session_id' => $session_id,
            'price' => NumberFormat($session->session_price),
            'discount_id' => $session->discount_id,
            'discount' => NumberFormat($session->discount_value),
            'discount_value' => NumberFormat($discount_value),
            // 'promo_code' => $session->code,
            'exam_price' => NumberFormat($session_exam_price),
            'take2_price' => NumberFormat($session->take2_price),
            'exam_simulation_price' => NumberFormat($session->exam_simulation_price),
            'total' => NumberFormat($sub_total),
            'vat' => NumberFormat($vat),
            'vat_value' => NumberFormat($vat_value),
            'total_after_vat' => NumberFormat($total_after_vat),
            'coin_id'=>$coin_id,
            'coin_price'=>$coin_price,
        ]);

        // $master_id__field = ($type_id==372)?'group_invoice_id':'master_id';

        Cart::where('master_id', $cart_master_id)->whereNull('deleted_at')->update([
            'session_id' => $session_id,
            'training_option_id' => $session->training_option_id,
            'course_id' => $course_id,
            'price' => NumberFormat($session->session_price),
            'discount_id' => $session->discount_id,
            'discount' => NumberFormat($session->discount_value),
            'discount_value' => NumberFormat($discount_value),
            // 'promo_code' => $session->code,
            'exam_price' => NumberFormat($session_exam_price),
            'take2_price' => NumberFormat($session->take2_price),
            'exam_simulation_price' => NumberFormat($session->exam_simulation_price),
            'total' => NumberFormat($sub_total),
            'vat' => NumberFormat($vat),
            'vat_value' => NumberFormat($vat_value),
            'total_after_vat' => NumberFormat($total_after_vat),
            'coin_id'=>$coin_id,
            'coin_price'=>$coin_price,
            'registered_at'=>DateTimeNow(),
            'locale'=>app()->getLocale(),
        ]);


        CartMaster::updateOrCreate([
            'id'=>$cart_master_id,
        ],[
            'total' => NumberFormat($masterTotals->total),
            'vat' => NumberFormat($vat),
            'vat_value' => NumberFormat($masterTotals->vat_value),
            'total_after_vat' => NumberFormat($masterTotals->total_after_vat),
            'coin_id'=>$coin_id,
            'coin_price'=>$coin_price,
        ]);

        $CartMaster_user_id = CartMaster::where('id', $cart_master_id)->select('user_id')->first();

        Payment::updateOrCreate([
            'master_id'=>$cart_master_id,
        ],[
            'paid_in'=>NumberFormat($masterTotals->total_after_vat),
            'user_id'=>$CartMaster_user_id->user_id??null,
            'description'=>$type_id,
            // 'payment_status'=>request()->payment_status??0,
            // // 'transaction_id'=>$id,
            // 'paid_at'=>DateTimeNow(),
        ]);

        return null;

    }


    public function updatePrices_edit(){

        if(request()->has('session_id')){

            $GetTotals = $this->GetTotals(request()->master_id);
            $masterTotals = $GetTotals['masterTotals'];
            $vat = $GetTotals['vat'];
            // $cartMaster = $GetTotals['cartMaster'];
            // $count = $GetTotals['count'];
            $coin_id = $GetTotals['coin_id'];
            // $currency = $GetTotals['currency'];

            $coin_price = ($coin_id==335) ? 3.8 : 1;
            // $coin_id = GetCoinId();
            // $coin_price = GetCoinPrice();

            GroupInvoiceMaster::where('master_id', request()->master_id)->update([
                'course_id' => request()->course_id,
                'organization_id' => request()->organization_id,
                'session_id' => request()->session_id,
                'price' => NumberFormat(request()->price),
                'discount_id' => request()->discount_id,
                'discount' => NumberFormat(request()->discount),
                'discount_value' => NumberFormat(request()->discount_value),
                // 'promo_code' => $session->code,
                'exam_price' => NumberFormat(request()->exam_price),
                'take2_price' => NumberFormat(request()->take2_price),
                'exam_simulation_price' => NumberFormat(request()->exam_simulation_price),
                'total' => NumberFormat(request()->total),
                'vat' => NumberFormat(request()->vat),
                'vat_value' => NumberFormat(request()->vat_value),
                'total_after_vat' => NumberFormat(request()->total_after_vat),
                'coin_id'=>$coin_id,
                'coin_price'=>$coin_price,
            ]);

            // $master_id__field = (request()->type_id==372)?'group_invoice_id':'master_id';

            Cart::where('master_id', request()->master_id)->whereNull('deleted_at')->update([
                'training_option_id' => request()->training_option_id,
                'course_id' => request()->course_id,
                'session_id' => request()->session_id,
                'payment_status' => request()->payment_status,
                'price' => NumberFormat(request()->price),
                'discount_id' => request()->discount_id,
                'discount' => NumberFormat(request()->discount),
                'discount_value' => NumberFormat(request()->discount_value),
                // 'promo_code' => $session->code,
                'exam_price' => NumberFormat(request()->exam_price),
                'take2_price' => NumberFormat(request()->take2_price),
                'exam_simulation_price' => NumberFormat(request()->exam_simulation_price),
                'total' => NumberFormat(request()->total),
                'vat' => NumberFormat(request()->vat),
                'vat_value' => NumberFormat(request()->vat_value),
                'total_after_vat' => NumberFormat(request()->total_after_vat),
                'coin_id'=>$coin_id,
                'coin_price'=>$coin_price,
                'registered_at'=>DateTimeNow(),
                'locale'=>app()->getLocale(),
            ]);


            CartMaster::updateOrCreate([
                'id'=>request()->master_id,
            ],[
                'total' => NumberFormat($masterTotals->total),
                'vat' => NumberFormat(request()->vat),
                'vat_value' => NumberFormat($masterTotals->vat_value),
                'total_after_vat' => NumberFormat($masterTotals->total_after_vat),
                'coin_id'=>$coin_id,
                'coin_price'=>$coin_price,
            ]);

            if(request()->payment_status == 332){ // Free
                $payment = Payment::where('master_id', request()->master_id)->first();
                if(!is_null($payment->id??null)){
                    Payment::where('id', $payment->id)->forceDelete();
                    // Payment::findOrFail($payment->id)->delete();
                }

                CartMaster::updateOrCreate([
                    'id'=>request()->master_id,
                    // 'type_id'=>request()->type_id,
                ],[
                    'total' => 0,
                    'vat' => 0,
                    'vat_value' => 0,
                    'total_after_vat' => 0,
                    'payment_status' => 332,
                    // 'coin_id'=>$coin_id,
                    // 'coin_price'=>$coin_price,
                ]);

            }else{

                $CartMaster_user_id = CartMaster::where('id', request()->master_id)->select('user_id')->first();

                Payment::updateOrCreate([
                    'master_id'=>request()->master_id,
                ],[
                    'paid_in'=>NumberFormat($masterTotals->total_after_vat),
                    'payment_status'=>request()->payment_status??0,
                    'user_id'=>$CartMaster_user_id->user_id??null,
                    'description'=>request()->type_id,
                    // 'payment_status'=>request()->payment_status??0,
                    // // 'transaction_id'=>$id,
                    // 'paid_at'=>DateTimeNow(),
                ]);

                if(request()->payment_status == 68) {
                    Cart::updateOrCreate([
                        'master_id'=>request()->master_id,
                    ],[
                        'payment_status'=>68,
                        'registered_at'=>DateTimeNow(),
                    ]);
                }
            }

        }

        return null;

    }

    // =================== End Frontend =======================

    public function escapeValue($text){
        $text = str_replace("<br>", "<w:br/>", $text);
        $text = str_replace("<br />", "<w:br/>", $text);
        $text = str_replace("<br/>", "<w:br/>", $text);
        $text = str_replace("<p>", "<w:br/>", $text);
        $text = str_replace("</p>", "<w:br/>", $text);
        $text = preg_replace('~[\r\n\t]+~', '', $text);
        $text = str_replace('&', '', $text);
        return $text;
    }

    public function exportQuotationToDoc($cart_master_id)
    {
        // $CartMast = CartMaster::findOrFail($cart_master_id);
        // if(!is_null($CartMast) && $CartMast->type_id==372){  //  // Group
        //     request()->post_type = 'group_invoices';
        // }

        $GetTotals = $this->GetTotals($cart_master_id);
        $cartMaster = $GetTotals['cartMaster'];
        $masterTotals = $GetTotals['masterTotals'];
        $count = $GetTotals['count'];
        $coin_id = $GetTotals['coin_id'];
        $currency = $GetTotals['currency'];
        // $coin_price = ($coin_id==335) ? 3.8 : 1;


        if((int)$count <= 0){
            Active::Flash(__('education.Warning'), __('education.There is no candidates'),'warning');
            return back();
        }

        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(false);
        \PhpOffice\PhpWord\Settings::setCompatibility(false);
        $template_file = public_path() . '/quotations/quotation_template_b2c_en.docx';
        if (file_exists($template_file)) {
            $templateProcessor  = new \PhpOffice\PhpWord\TemplateProcessor($template_file);

            $organization_title = $cartMaster->rfpGroup->organization->trans_title??'';
            $course_title = $cartMaster->rfpGroup->session->trainingOption->course->trans_title??'';
            $candidates_count = $count??'';
            $total_after_vat = NumberFormatWithComma($masterTotals->total_after_vat??'');
            $date_from = date('d-m-Y', strtotime($cartMaster->rfpGroup->session->date_from??''));
            $date_to = date('d-m-Y', strtotime($cartMaster->rfpGroup->session->date_to??''));
            $duration = $cartMaster->rfpGroup->session->duration??'';
            $course_city = $cartMaster->rfpGroup->session->trainingOption->type->trans_name??'';
            $total_after_vat_per_one = NumberFormatWithComma($cartMaster->rfpGroup->total_after_vat??'');

            $templateProcessor ->setValue('organization_title', $this->escapeValue($organization_title));
            $templateProcessor ->setValue('course_title', $this->escapeValue($course_title));
            $templateProcessor ->setValue('candidates_count', $this->escapeValue($candidates_count));
            $templateProcessor ->setValue('total_after_vat', $this->escapeValue($total_after_vat));
            $templateProcessor ->setValue('date_from', $this->escapeValue($date_from));
            $templateProcessor ->setValue('date_to', $this->escapeValue($date_to));
            $templateProcessor ->setValue('duration', $this->escapeValue($duration));
            $templateProcessor ->setValue('course_city', $this->escapeValue($course_city));
            $templateProcessor ->setValue('total_after_vat_per_one', $this->escapeValue($total_after_vat_per_one));
            $templateProcessor ->setValue('coin_id', $this->escapeValue($currency));

            $file_name = public_path() . '/quotations/export/'.$organization_title.'_'.date('d-m-Y').'.docx';
            $templateProcessor ->saveAs($file_name);

            // To send invoice & payment receipt by email
            // $EpayController = new EpayController();
            // $EpayController->SendEmailMaster($cartMaster->userId, $cart_master_id);

            return response()->download($file_name);
            // return response()->download($file_name)->deleteFileAfterSend(true);
        }
    }

    public function exportInvoiceToDoc($cart_master_id)
    {
        // $CartMast = CartMaster::findOrFail($cart_master_id);
        // if(!is_null($CartMast) && $CartMast->type_id==372){  //  // Group
        //     request()->post_type = 'group_invoices';
        // }

        $GetTotals = $this->GetTotals($cart_master_id);
        $cartMaster = $GetTotals['cartMaster'];
        $masterTotals = $GetTotals['masterTotals'];
        $count = $GetTotals['count'];
        $coin_id = $GetTotals['coin_id'];
        $currency = $GetTotals['currency'];

        // dd($GetTotals);
// aaaaaaaaaaaaaaaaaaaaa
        if((int)$count <= 0){
            Active::Flash(__('education.Warning'), __('education.There is no candidates'),'warning');
            return back();
        }
        $organization_title = $cartMaster->rfpGroup->organization->en_title??'';
        $organization_email = $cartMaster->rfpGroup->organization->ar_title??'';
        // $organization_email = $cartMaster->rfpGroup->organization->email??'';
        $organization_address = $cartMaster->rfpGroup->organization->address??'';
        $tax_number = $cartMaster->tax_number??'';
        $invoice_number = $cartMaster->invoice_number??'';
        $reference = $cartMaster->reference??'';
        $due_date = date('d-m-Y', strtotime($cartMaster->due_date??''));

        $date_from = date('d-m-Y', strtotime($cartMaster->rfpGroup->session->date_from??''));
        $date_to = date('d-m-Y', strtotime($cartMaster->rfpGroup->session->date_to??''));
        $course_city = $cartMaster->rfpGroup->session->trainingOption->type->trans_name??'';
        // $coin_id = ($cartMaster->rfpGroup->coin_id==334) ? 'SAR' : 'USD';

        $total = NumberFormatWithComma($masterTotals->total??'');
        $vat = $cartMaster->rfpGroup->vat;
        $vat_value = NumberFormatWithComma($masterTotals->vat_value??'');
        $total_after_vat_without_format = $masterTotals->total_after_vat??0;
        $total_after_vat = NumberFormatWithComma($masterTotals->total_after_vat??'');

        $NumToWords = new NumToWords();

        $num_str = number_format($total_after_vat_without_format,2);
        $decimals = intval($NumToWords->AfterNum('.', $num_str));
        $integers = intval($NumToWords->BeforeNum('.', $total_after_vat_without_format));

        $currency_title_en = ($coin_id==335)?'U.S. Dollar':'Saudi Riyal';
        $currency_title = ($coin_id==335)?'دولار أمريكي':'ريال سعودي';
        if($decimals > 0){
            $Total_in_words = $NumToWords->NumberToWord( $integers ).' and '.$decimals.'/100 '.$currency_title_en;
            $Total_in_words_ar = $NumToWords->ConvertNumber($integers, "male").' و  100/'.$decimals.' '.$currency_title;
        }else{
            $Total_in_words = $NumToWords->NumberToWord( $total_after_vat_without_format ).' '.$currency_title_en;
            $Total_in_words_ar = $NumToWords->ConvertNumber($total_after_vat_without_format, "male").' '.$currency_title;
        }
        $Total_in_words_ar = "<w:rPr><w:rtl/></w:rPr>".$Total_in_words_ar;

        $cart_table = array();
        $j=0;
        foreach($cartMaster->carts as $cart){
            $cart_table[$j]['id'] = $j;

            $item_details = '';
            if($count>1){
                $item_details .= 'Candidate Name: '.$cart->userId->en_name??null;
                $item_details .= '<br>';
            }
            $item_details .= $cart->course->en_title??null;
            $item_details .= '<br><w:rPr><w:rtl/></w:rPr>'.$cart->course->ar_title??null;
            $item_details .= '<br>Delivery Method: '.$cart->trainingOption->constant->en_name??null;

            $training_option_id = $cart->trainingOption->constant_id??$cartMaster->rfpGroup->session->trainingOption->constant_id;

            if($training_option_id == 13){
                $item_details .= '<br>Date and Time: '.$date_from.' - '.$date_to.$cart->session->session_time;
                $item_details = substr($item_details, 0, -4); //<w:br/>
            }
            $item_details = $this->escapeValue($item_details);

            $cart_table[$j]['title']  = $item_details;
            $cart_table[$j]['price']  = NumberFormatWithComma($cart->price);
            $cart_table[$j]['qty']    = 1;
            $cart_table[$j]['amount'] = NumberFormatWithComma($cart->price);
            $j++;

            if($cart->discount_value>0){
                $cart_table[$j]['id'] = $j;

                $item_details = 'Discount ('.$cart->discount.'%) خصم';
                $item_details = $this->escapeValue($item_details);

                $cart_table[$j]['title']  = $item_details;
                $cart_table[$j]['price']  = '-'.NumberFormatWithComma($cart->discount_value);
                $cart_table[$j]['qty']    = 1;
                $cart_table[$j]['amount'] = '-'.NumberFormatWithComma($cart->discount_value);
                $j++;
            }

            if($cart->exam_price>0){
                $cart_table[$j]['id'] = $j;

                $item_details = 'Voucher Exam for '.$cart->course->en_title??null;
                $item_details .= '<br>';
                $item_details .= '<w:rPr><w:rtl/></w:rPr>قسيمة اختبار '.$cart->course->ar_title??null;
                $item_details = $this->escapeValue($item_details);

                $cart_table[$j]['title']  = $item_details;
                $cart_table[$j]['price']  = NumberFormatWithComma($cart->exam_price);
                $cart_table[$j]['qty']    = 1;
                $cart_table[$j]['amount'] = NumberFormatWithComma($cart->exam_price);
                $j++;
            }

            if($cart->take2_price>0){
                $cart_table[$j]['id'] = $j;

                $item_details = 'Exam Take2';
                $item_details = $this->escapeValue($item_details);

                $cart_table[$j]['title']  = $item_details;
                $cart_table[$j]['price']  = NumberFormatWithComma($cart->take2_price);
                $cart_table[$j]['qty']    = 1;
                $cart_table[$j]['amount'] = NumberFormatWithComma($cart->take2_price);
                $j++;
            }

            if($cart->exam_simulation_price>0){
                $cart_table[$j]['id'] = $j;

                $item_details = 'Exam Simulator';
                $item_details = $this->escapeValue($item_details);

                $cart_table[$j]['title']  = $item_details;
                $cart_table[$j]['price']  = NumberFormatWithComma($cart->exam_simulation_price);
                $cart_table[$j]['qty']    = 1;
                $cart_table[$j]['amount'] = NumberFormatWithComma($cart->exam_simulation_price);
                $j++;
            }

            if(!is_null($cart->cartFeatures)){
                foreach($cart->cartFeatures as $feature){
                    $cart_table[$j]['id'] = $j;

                    $item_details = $feature->feature->title;
                    $item_details = $this->escapeValue($item_details);

                    $cart_table[$j]['title']  = $item_details;
                    $cart_table[$j]['price']  = NumberFormatWithComma($feature->price);
                    $cart_table[$j]['qty']    = 1;
                    $cart_table[$j]['amount'] = NumberFormatWithComma($feature->price);
                    $j++;
                }
            }
        }

        $subject_en = 'Invoice';
        $subject_ar = 'فاتورة';
        $word_temp_file = 'group_inv_tmp';

        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(false);
        \PhpOffice\PhpWord\Settings::setCompatibility(false);

        $template_file = public_path() . '/invoices/'.$word_temp_file.'.docx';
        if (file_exists($template_file)) {
            $templateProcessor  = new \PhpOffice\PhpWord\TemplateProcessor($template_file);

            $templateProcessor->setValue('subject_en', $this->escapeValue($subject_en));
            $templateProcessor->setValue('subject_ar', "<w:rPr><w:rtl/></w:rPr>".$this->escapeValue($subject_ar));
            $templateProcessor ->setValue('organization_title', $this->escapeValue($organization_title));
            $templateProcessor ->setValue('organization_email', $this->escapeValue($organization_email));
            $templateProcessor ->setValue('organization_address', $this->escapeValue($organization_address));
            $templateProcessor ->setValue('tax_number', $this->escapeValue($tax_number));
            $templateProcessor ->setValue('invoice_number', $this->escapeValue($invoice_number));
            $templateProcessor ->setValue('invoice_date', $this->escapeValue(date('d-m-Y')));
            $templateProcessor ->setValue('reference', $this->escapeValue($reference));
            $templateProcessor ->setValue('due_date', $this->escapeValue($due_date));

            $d = 1;
            $templateProcessor->cloneRow('d', $j);
            for ( $k=0; $k < $j ; $k++) {
                $templateProcessor->setValue('d' . '#' . $d, $d);
                $templateProcessor->setValue('title' . '#' . $d, $cart_table[$k]['title']);
                $templateProcessor->setValue('qty' . '#' . $d, $cart_table[$k]['qty']);
                $templateProcessor->setValue('price' . '#' . $d, $cart_table[$k]['price']);
                $templateProcessor->setValue('amount' . '#' . $d, $cart_table[$k]['amount']);
                $d++;
            }

            $templateProcessor ->setValue('total', $this->escapeValue($total));
            $templateProcessor ->setValue('vat', $this->escapeValue($vat));
            $templateProcessor ->setValue('vat_value', $this->escapeValue($vat_value));
            $templateProcessor ->setValue('total_after_vat', $this->escapeValue($total_after_vat));
            $templateProcessor->setValue('Total_in_words', $Total_in_words);
            $templateProcessor->setValue('Total_in_words_ar', $Total_in_words_ar);
            $templateProcessor->setValue('currency_title_en', $currency);
            $templateProcessor->setValue('currency_title', $currency_title);

            // $templateProcessor->deleteBlock('tbl_proforma');
            $templateProcessor->setValue('del', '');
            $templateProcessor->setValue('del2', '');
            $templateProcessor->setValue('del3', '');

            $file_name = public_path() . '/invoices/export/'.$organization_title.'-INV-'.$invoice_number.'_'.date('d-m-Y').'.docx';
            $templateProcessor ->saveAs($file_name);

            return response()->download($file_name);
            // return response()->download($file_name)->deleteFileAfterSend(true);
        }
    }


    public function search(){

        $carts = null;
        $carts = Cart::with(['course:id,title', 'payment', 'userId'])->take(3)->get();
        // if(request()->has('searchTxt')){
        //     $carts = Cart::with(['course:id,title', 'payment', 'userId'])
        //     ->whereHas('userId', function (Builder $query) {
        //         $query->where('name', 'like', '%'.trim(request()->searchTxt).'%')
        //             ->orWhere('email', 'like', '%'.trim(request()->searchTxt).'%');
        //     })
        //     ->take(15)
        //     ->get();
        // }
        // dd($carts);
        return $carts;
        // return view('crm::group_invoices.search', compact('carts'));
        // return response()->json($carts);
        // return ['carts'=>$carts];
    }

    public function SearchCandidates(){

        if(request()->has('id'))
        {
            if(!empty(request()->id))
            {
                $id = request()->id;
                // echo $id;
                // $users = Constant::where('parent_id', 42)->orWhere('post_type', 'employee')->get();
                if(request()->has('cand_s_data') && !is_null(request()->cand_s_data)) {
                        $carts = Cart::whereNotNull('id')
                        ->whereHas('userId', function (Builder $query) {
                                $query->where('name', 'like', '%'.trim(request()->cand_s_data).'%')
                                    ->orWhere('email', 'like', '%'.trim(request()->cand_s_data).'%');
                            });

                        $carts = $carts->orWhere('invoice_number', 'like', '%'.trim(request()->cand_s_data).'%');

                        $carts = $carts->with(['course:id,title', 'payment', 'userId']);
                        // print_r($carts);
                        $count = $carts->count();
                        if($count){
                            $ret_data = '<ul id="search_can_ul">';
                            foreach($carts as $cart){
                                // $ret_data .= $cart;
                                $ret_data .= '<li><span>' . $cart->userId->trans_name??null . ' - ' .  $cart->userId->email??null . ' - ' . $cart->invoice_number??null . ' - <span style="color:#fb4400"><b>' . $cart->trainingOption->training_name??null . ' | ' . $cart->session->published_from??null . ' - ' . $cart->session->published_to??null . '</b></span></span><span class="btn" data-id="' . $cart->id . '"><i class="fa fa-plus"></i></span></li>';
                            }
                            $ret_data .= '</ul>';
                            return response()->json(array('msg'=> $ret_data), 200);
                        }
                }

            }
        }
    }
}
