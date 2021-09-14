<?php

namespace App\Http\Controllers\Training;

use App\Constant;
use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Http\Requests\Training\SessionRequest;
use App\Models\Training\Attendant;
use App\Models\Training\Cart;
use App\Models\Training\Course;
use App\Models\Training\Session;
use App\Models\Training\TrainingOption;
use App\Models\Training\UserSession;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;
use App\Profile;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ZoomImport;
use App\Exports\ZoomExport;

class SessionController extends Controller
{



    
 



    public function __construct()
    {

//         $curl = curl_init();
 
// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://export.az2.survey2connect.com/v1/api/csv?s=NjA1OWM4NTVlMTk3MjAxZWNhNGZjMDRk&v=NjA1YjAwZmVhNGNiZDUyOTFiOTAwNjMw&et=1&vt=Original',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'POST',
//   CURLOPT_POSTFIELDS => array('email' => 'aalhorany@bakkah.net.sa','password' => 'ITDep@123456'),
// ));
 
// $response = curl_exec($curl);
 
// curl_close($curl);
// echo $response;
        Active::$namespace = 'training';
        Active::$folder = 'sessions';
    }

    public function index(){

        $session_statuts_compo = Constant::where('post_type','session_status')->get();

        $all_courses = Course::has('trainingOptions.sessions')
            ->with('trainingOptions.sessions')
            ->orderBy('title->en')
            ->get();

        $trash = GetTrash();
        $sessions = Session::latest('date_from')->with(['attendants_status','attendantsStatusUpdated_by']);

        if(request()->has('course_id') && request()->course_id != -1){
            $sessions = $sessions->whereHas('trainingOption.course', function(Builder $query){
                $query->where('id', request()->course_id);
            });
        }
        if(!is_null(request()->session_from)) {
            $sessions = $sessions->where(function ($query) {
                $query->whereDate('date_from', '>=', request()->session_from);
            });
        }
        if(!is_null(request()->session_to)) {
            $sessions = $sessions->where(function ($query) {
            // $carts = $carts->whereHas('session', function (Builder $query) {
                $query->whereDate('date_from', '<=', request()->session_to);
            });
        }
        if(!is_null(request()->training_option_id) && request()->training_option_id != -1) {
            $sessions = $sessions->whereHas('trainingOption', function(Builder $query){
                $query->where('constant_id', request()->training_option_id);
            });
        }
        if(!is_null(request()->eval_api_search)) {
            $sessions = $sessions->where('evaluation_api_code', 'like', '%'.request()->eval_api_search.'%');
        }
        if(request()->has('session_status')){
            if(request()->session_status  == 425){
                $sessions = $sessions->doesntHave('cart');
            }else if(request()->session_status  == 426){
                $sessions = $sessions->whereHas('cart',function ($q){
                    $q->where('status_id', 51)->where('payment_status', '<>', 63);
                });
            }else if(request()->session_status == -1){
                $sessions = $sessions->with(['cart'=> function ($q){
                    $q->where('status_id', 51)->where('payment_status', '<>', 63);
                }]);
            }
        }else{
            $sessions =$sessions->with(['cart'=> function ($q){
                $q->where('status_id', 51)->where('payment_status', '<>', 63);
            }]);
        }
        if(auth()->id()==5074) {
            $sessions = $sessions->whereIn('attendants_status_id', [429,430]);
        }
       
        $count = $sessions->count();
        $post_type='session';
        $sessions = $sessions->page();
        $attend_count = Attendant::count();
        // $cart= Cart::where('id', 286)->update([
        //     'attendance_count'=>5,
        // ]);
        $training_options = Constant::where('parent_id', 10)->get();
        
        return Active::Index(compact('session_statuts_compo','all_courses', 'sessions', 'post_type','count','attend_count', 'trash', 'training_options'));
    }

    public function create(){

//        $training_option=TrainingOption::where('constant_id', '!=', 11)->get();
        $training_option=TrainingOption::where('price', '!=', 0)->get();
        $duration_type=Constant::where('parent_id',38)->get();
        $lang=Constant::where('parent_id',35)->get();
        $trainers = User::where('user_type', 326)->get();
        $developers = User::where('user_type', 402)->get();
        $demand_teams = User::where('user_type', 403)->get();
        return Active::Create(compact('training_option','duration_type','lang','trainers','developers','demand_teams'));
    }

    public function store(SessionRequest $request){
        $validated = $this->Validated($request->validated());
        $validated['created_by'] = auth()->user()->id;
        $session = Session::create($validated);
        // dump($session->id);
        // $this->insert_costs($session->id);
        return Active::Inserted($session->trans_name);
    }

    public function edit(Session $session){

        // dd($session->id);
        $training_option=TrainingOption::where('price', '!=', 0)->get();
        //        $training_option=TrainingOption::where('constant_id', '!=', 11)->get();
        $duration_type  = Constant::where('parent_id',38)->get();
        $lang           = Constant::where('parent_id',35)->get();
        $trainers       = User::where('user_type', 326)->get();
        $developers     = User::where('user_type', 402)->get();
        $demand_teams   = User::where('user_type', 403)->get();

        return Active::Edit([
            'eloquent' => $session,
            'post_type' => $session->post_type,
            'training_option' => $training_option,
            'duration_type' => $duration_type,
            'lang' => $lang,
            'trainers' => $trainers,
            'developers' => $developers,
            'demand_teams' => $demand_teams,
            
        ]);
    }

    public function update(SessionRequest $request, Session $session){
        // dd($request->all());
        $validated = $this->validated($request->validated());
        $validated['updated_by'] = auth()->user()->id;
        $attend_count = Attendant::count();
        $colle = $attend_count - $session->duration;
        $validated['attendance_count'] = $colle;
        //  $session->attendants->count()
        // dd($validated);
        Session::find($session->id)->update($validated);
        // Session::where('id', $session->id)->update([
        //     'attendance_count'=>$colle,
        // ]);
        
        Session::UploadFile($session, ['method'=>'update']);
        
        $this->insert_costs($session->id);
        
        return Active::Updated($session->trans_name);
    }

    public function destroy(Session $session, Request $request){
        Session::where('id', $session->id)->SoftTrash();
        return Active::Deleted($session->title);
    }

    public function restore($session){
        Session::where('id', $session)->RestoreFromTrash();

        $session = Session::where('id', $session)->first();
        return Active::Restored($session->title);
    }

    public function getSessionByCourse(){

        if(request()->has('training_option_id')){
            $DateTimeNow = DateTimeNow();
            // $trainingOptions = TrainingOption::where('training_option_id', request()->training_option_id)->pluck('id');
            // $sessions = Session::whereIn('training_option_id', $trainingOptions)->get()->toArray();
            $sessions = Session::where('training_option_id', request()->training_option_id)
            ->whereDate('date_to', '>', $DateTimeNow)
            ->get()
            ->map(function($session){
                return [
                    'id'=>$session->id,
                    'published_session'=>$session->published_session,
                ];
            });
            return $sessions;
        }
        return null;
    }

    private function Validated($validated){

        if(isset($validated['money_back_guarantee'])){
            $validated['money_back_guarantee'] = 1;
        }
        else{
            $validated['money_back_guarantee'] = 0;
        }

        if(isset($validated['retarget_discount'])){
            $validated['retarget_discount'] = 1;
        }
        else{
            $validated['retarget_discount'] = 0;
        }

        if(isset($validated['send_reminder_before_start'])){
            $validated['send_reminder_before_start'] = 1;
        }
        else{
            $validated['send_reminder_before_start'] = 0;
        }

        if(isset($validated['except_fri_sat'])){
            $validated['except_fri_sat'] = 1;
        }
        else{
            $validated['except_fri_sat'] = 0;
        }

        $validated['show_in_website'] = request()->has('show_in_website')?1:0;

        $validated['updated_by'] = auth()->user()->id;
        return $validated;
    }

    public function sessionAttendant()
    {
        if(request()->has('session'))
        {
            $session = Session::with(['trainingOption.course', 'durationType'])->find(request()->session);

            $carts_collection = Cart::where('session_id', request()->session)
            // ->where('status_id', 51)
            // ->where('payment_status', '<>', 63)
            ->whereIn('payment_status', [68,315,317,332])
            ->with(['userId', 'attendants'])
            ->get();

            $carts = $carts_collection->sortBy('userId.name');
            $carts->values()->all();

            $attend_types = Constant::where('post_type', 'attend_type')->orWhere('id', 316)->orderBy('id', 'DESC')->get();
            // dd($attend_types);
            // dd(Active::$namespace.'.'.Active::$folder.'.attendant');
            // dd($attend_types);
            return view(Active::$namespace.'.'.Active::$folder.'.attendant', compact('carts', 'session', 'attend_types'));
        }
    }

    public function sessionAttendantStore(){

        if(request()->has('session_id')) {

            // update status

            $this->Attendance('arrayInserted');
            $this->Attendance('arrayRemoved');

                $this->ArrayNotes();
                ////////
                $carts = Cart::where('session_id', request()->session_id)->get();
                foreach($carts as $cart){
                    $attendant_count = Attendant::where('cart_id', $cart->id)->count();
                    $cart->update([
                        'attendance_count'=>$attendant_count,
                    ]);
                }
                $attendance_count = Cart::where('session_id', request()->session_id)->sum('attendance_count');

                if(request()->has('action') && \request()->action == "approve" ){
                    $session = Session::find(request()->session_id);
                        $session->update([
                            'attendance_count'=>$attendance_count,
                            'attendants_status_updated_by' => auth()->id() ,
                            'attendants_status_updated_at' => Carbon::now() ,
                            'attendants_status_id' => 429 ,
                        ]);
                }else if(request()->has('action') && \request()->action == "save" ){

                    $session = Session::find(request()->session_id);
                    if ($session->attendants_status_id == 429){
                        $session->update([
                            'attendance_count'=>$attendance_count,
                        ]);
                    }else{
                        $session->update([
                            'attendance_count'=>$attendance_count,
                            'attendants_status_updated_by' => auth()->id() ,
                            'attendants_status_updated_at' => Carbon::now() ,
                            'attendants_status_id' => 428 ,
                        ]);
                    }
                }else if(request()->has('action') && \request()->action == "done" ) {
                    $session = Session::find(request()->session_id);
                    $session->update([
                        'attendance_count'=>$attendance_count,
                        'attendants_status_updated_by' => auth()->id() ,
                        'attendants_status_updated_at' => Carbon::now() ,
                        'attendants_status_id' => 430 ,
                    ]);
                }else if(request()->has('action') && \request()->action == "xero_invoices_run" ) {
                    return response()->json(['msg'=>'xero_invoices_run']);
                }
        }
    }

    private function ArrayNotes(){
        if (request()->has('arrayNotes') && !is_null(request()->get('arrayNotes'))) {
            foreach (request()->get('arrayNotes') as $value) {
                $cart = Cart::find($value['cart_id']);
                if(!empty($value['attend_type_id'])){
                    $cart->update([
                        'attend_type_id' => $value['attend_type_id'],
                    ]);
                }
                if(!empty($value['exam_voucher_code'])){
                    $cart->update([
                        'exam_voucher_code' => $value['exam_voucher_code'],
                        'attend_type_id' => $value['attend_type_id'],
                    ]);
                }
                if(!empty($value['notes'])){
                    $cart->notes()->updateOrCreate([], [
                        'comment'=>$value['notes'],
                        'user_id'=>auth()->user()->id??null,
                    ]);
                }
            }
        }
    }

    private function Attendance($name){
        if (request()->has($name) && !is_null(request()->get($name))) {
            foreach (request()->get($name) as $value) {
                $this->$name($value);
            }
        }
    }

    private function arrayInserted($value){
        Attendant::create([
            'cart_id' => $value['cart_id'],
            'attend_day' =>$value['index']
        ]);
        return response()->json(['msg'=>'success']);

    }

    private function arrayRemoved($value){
        Attendant::where('cart_id', $value['cart_id'])
            ->where('attend_day', $value['index'])
            ->delete();
        return response()->json(['msg'=>'error']);
    }

    public function importzoom()
    {

       if(request()->file('file') != '')
       {
            Active::Flash('Imported', __('flash.imported'), 'success');

            Excel::import(new ZoomImport,request()->file('file'));
            return Excel::download(new ZoomExport, 'zoom_participants.xlsx');
       }
       else
       {
            Active::Flash('Not Imported', __('flash.not_imported'), 'danger');
            return back();
       }
    }

    public function calculate_cost($array=null)
    {

        if(isset(request()->session_id))
            $session_id = request()->session_id;
        else
            $session_id =  $array['session_id'];
             
        $session = Session::with(['userSession.user.profile'])->find($session_id);
        // dump($session);
        // var amOrPm = (time_s.getHours() < 12) ? "AM" : "PM";
        // dd( $session->session_start_time);
        $date = strtotime($session->session_start_time);
        if(date('H', $date) < 12 )
            $evOrmo = 'morning_rate';
        else
            $evOrmo = 'evening_rate'; 
       
        $morning_rate_type = $evOrmo.$this->GetTypeCode($session->trainingOption->constant_id??13);
        // dump($morning_rate_type); 
        if(isset(request()->user_id))
            $morning_rate = Profile::where('user_id',request()->user_id)->pluck($morning_rate_type)->first();
        else
        {
            // $morning_rate = $session->userSession()->where('post_type', 'trainer')->where('session_id',$session_id)->first()->user->profile->$morning_rate_type;
            $user_id = $session->userSession()->where('post_type', 'trainer')->where('session_id',$session_id)->first();
            $morning_rate = $user_id->user->profile->$morning_rate_type??0;
        }
       
        
        $total_hours = ($session->duration??1) * ($session->hours_per_day??1);
        
        $trainer_cost = $morning_rate * $total_hours;     

        // on_demand_cost
        $daily_rate_type = 'daily_rate'.$this->GetTypeCode($session->trainingOption->constant_id??13);
        if(isset(request()->user_id))
            $daily_rate = Profile::where('user_id',request()->user_id)->pluck($daily_rate_type)->first();
        else
        {
            $user_id = $session->userSession()->where('post_type', 'demand')->where('session_id',$session_id)->first();
            $daily_rate = $user_id->user->profile->$morning_rate_type??0;
        }
            
        
        $on_demand_cost = $daily_rate * $total_hours;
        // return $on_demand_cost;
        
        $arr = [];
        $arr['trainer_cost']   = $trainer_cost;
        $arr['on_demand_cost'] = $on_demand_cost;
        return $arr;
    }

    public function calculate_gross_margin($array=null)
    {
        if(isset(request()->session_id))
            $session_id = request()->session_id;
        else
            $session_id = $array['session_id'];  
             
        
        $session = Session::where('id',$session_id)->first();

        $total_hours = ($session->duration??1) * ($session->hours_per_day??1);
        // trainees_no
        $trainees_no = 0;
        $carts = $session->carts->whereIn('payment_status', [68,317]);
        if(isset($carts))
            $trainees_no = $carts->count();

        $sid = $grossMargin->session->id??0;
        $href = false;
        if($session_id > 0 && $trainees_no > 0)
            $href = true;

        $material_cost_course = $session->trainingOption->course->material_cost??0;
        $material_cost = 0;
        if($trainees_no!=0)
        {
            $material_cost = $material_cost_course * $trainees_no;
        }
        
        // sales_value
        $sales_value=0;
        foreach($carts as $cart)
        {
            $price = $cart->price??0;
            $discount_value = $cart->discount_value??0;

            // Free or Bakkah Employee
            if($cart->payment_status == 332 || $cart->payment_status == 315){
                $price = 0;
                $discount_value = 0;
            }

            if($cart->coin_id == 335){
                $price = ($price) * ($cart->coin_price??0);
                $discount_value = ($discount_value) * ($cart->coin_price??0);
            }

            $sales_value += $price - $discount_value;
        }
        
        // delivery_cost
        if(isset(request()->on_demand_cost))
        {
            $on_demand_cost = request()->on_demand_cost;
            $trainer_cost   = request()->trainer_cost;
        }
        else
        {
            $costs = $this->calculate_cost(array('session_id' => $session_id));
            $on_demand_cost = $costs['on_demand_cost'];
            $trainer_cost   = $costs['trainer_cost'];
            
        }

        $delivery_cost = (request()->zoom_cost??0) + ($on_demand_cost??0) + ($trainer_cost??0) + ($material_cost??0);
        // gross_profit
        $gross_profit=0;
        $gross_profit = ($sales_value??0) - ($delivery_cost??0);

        // gross_margin
        $sales_value_new = $sales_value;
        if($sales_value == 0){
            $sales_value_new = 1;
        }
        $gross_margin=0;
        $gross_margin = (($gross_profit??0) / $sales_value_new) * 100;

        return compact('material_cost','sales_value','gross_profit','gross_margin','total_hours','on_demand_cost','trainer_cost','trainees_no','session_id','href','material_cost_course','delivery_cost');

    }
 
    
    
    private function insert_costs($session_id)
    {
        
        if(request()->trainer_cost > 0 &&  request()->trainer_id > 0)
        {
            $user_trainer = UserSession::updateOrCreate(
                ['session_id' => $session_id,'post_type'=>'trainer'],
                ['cost' => request()->trainer_cost,'user_id' => request()->trainer_id]
            );
        }

        if(request()->developer_cost > 0 &&  request()->developer_id > 0)
        {
            $user_developer = UserSession::updateOrCreate(
                ['session_id' => $session_id,'post_type'=>'developer'],
                ['cost' => request()->developer_cost,'user_id' => request()->developer_id]
            );
        }

        if(request()->demand_cost > 0 && request()->demand_team_id > 0)
        {
            $user_demand = UserSession::updateOrCreate(
                ['session_id' => $session_id,'post_type'=>'demand'],
                ['cost' => request()->demand_cost,'user_id' =>request()->demand_team_id]
            );
        }
    }

    public function confirm_session()
    {

        Session::where('id', request()->session_id)->update(['is_confirmed' => request()->confirm]);    

    }

    private function GetTypeCode($trainingOption=13){
            $array = [
                11=>'',
                13=>'_online',
                353=>'',
                383=>'_classroom',
            ];
            return $array[$trainingOption];
        }
    
}




