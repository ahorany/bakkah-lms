<?php

namespace App\Http\Controllers\Training;

use App\Constant;
use App\Helpers\Active;
use App\Helpers\Models\Training\CartHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Training\GrossMarginRequest;
use App\Models\Training\Course;
// use App\Models\Training\GrossMargin;
use App\Models\Training\Session;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GrossMarginController extends Controller
{

    public function __construct()
    {
        Active::$namespace  = 'training';
        Active::$folder     = 'gross-margin';
    }


    public function index(){

        // $2y$10$1MkjDgsB8.blIOjtcoou.Od8uyOX.9mOMFytRfHth271v1h/IK8EO
        // dd(bcrypt('salrebdi@789'));
        // dd(bcrypt('basil@789'));
        // $2y$10$f/hqWHhKs5HDgjMxxVu.OuZNG9deoz5Gqj2/QM55iF5h3VzwO91iq
        // basil@eysar.com
        // dd(bcrypt('Abdulkader@789'));
        // $2y$10$0nq0KemAGdhbuqINQe.mCuEpkSk14467/llNwuVq6G8qNNgmDjTv.
        // 13336
        // dd(bcrypt('hdahoudi@456'));
        // $2y$10$2G42y2VfdF/Cuw31lV.6uu1ZV1SacOk1HuARMfc6qCDel86CeSxMa
        // 13507
        // dd(bcrypt('rabushawish@159'));
        //$2y$10$Qpkz8p8RngDND9Q8ENEgqOMbR2LKZaWUaUONxBnkSpHHpxJUGKalS

        $post_type='gross-margin';
        $trash = GetTrash();

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
        if(!is_null(request()->is_confirmed) && request()->is_confirmed != -1) {
            if(request()->is_confirmed == 474)
                $is_confirmed = 1;
            else if(request()->is_confirmed == 473)
                $is_confirmed = 0;
           $sessions =  $sessions->where('is_confirmed',  $is_confirmed);
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
        $sessions = $sessions->page();
        
        // $training_options = Constant::where('parent_id', 10)->get();
        $confirms = Constant::where('post_type', 'is_confirmed')->get();
        return Active::Index(compact('session_statuts_compo','all_courses', 'sessions', 'post_type','count', 'trash', 'confirms'));

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
    }

    public function getSession(Request $request){
       $session= Session::with(['trainer.profile','developer.profile','demand.profile','trainingOption.course'
                                    ,'carts'=>function($query){
                                        $query->whereIn('payment_status', [68,317,332,315])
                                                ->where('attendance_count', '>', 0);
                                                // ->where('new file', '>=', 70);
                                    }
                                ])
                        ->withCount(['carts'=>function($query){
                                                return  $query->whereIn('payment_status', [68,317,332,315]);
                                            }])
                        ->find($request->id);
        // dd($session);
        return $session;
    }

    public function store(GrossMarginRequest $request){
        // return $request;

        $GrossMargin = GrossMargin::updateOrCreate([
            'course_id' => $request->product_name,
            'session_id' => $request->Session,
        ],[
            'time' => $request->time,
            'total_hours' => NumberFormat($request->total_hours),
            'trainer_cost' => NumberFormat($request->trainer_cost),
            'developer_cost' => NumberFormat($request->developer_cost),
            'demand_team_cost' => NumberFormat($request->demand_team_cost),
            'zoom' => NumberFormat($request->zoom),
            'trainees_no' => $request->trainees_no,
            'attendants_no' => $request->attendants_no,
            'updated_by' => auth()->id(),
        ]);

        // return $request;
        Active::Flash(__('education.Updated'), __('education.Data updated successfully'),'success');
        return redirect()->route('training.gross-margin.edit', ['gross_margin'=>$GrossMargin->id]);
    }

    public function create(){

        $post_type='gross-margin';
        $folder = 'gross-margin';
        $all_courses = Course::GetAll();
        $session_array = $this->sessionsJson();
        $lang = json_encode(app()->getLocale());
        $trainers = User::where('user_type', 326)->get();
        $developers = User::where('user_type', 402)->get();
        $demand_teams = User::where('user_type', 403)->get();
        $coins = Constant::where('parent_id',333)->get();
        $times = Constant::where('post_type','time_field')->get();
        $eloquent = 0;

        return Active::Create(compact('eloquent','trainers','demand_teams','developers','post_type','coins','lang','all_courses','session_array', 'folder','times'));
    }

    public function edit(GrossMargin $grossMargin){

        $post_type='gross-margin';
        $folder = 'gross-margin';
        $all_courses = Course::GetAll();
        $session_array = $this->sessionsJson();
        $lang = json_encode(app()->getLocale());
        $trainers = User::where('user_type', 326)->get();
        $developers = User::where('user_type', 402)->get();
        $demand_teams = User::where('user_type', 403)->get();
        $coins = Constant::where('parent_id',333)->get();
        $times = Constant::where('post_type','time_field')->get();

        $GetCalculations = $grossMargin->GetCalculations($grossMargin);

        // dd($grossMargin);
        // dd($GetCalculations);

            $total_hours            = $GetCalculations['total_hours'];
            $on_demand_cost         = $GetCalculations['on_demand_cost'];
            $trainer_cost           = $GetCalculations['trainer_cost'];
            $trainees_no            = $GetCalculations['trainees_no'];
            $sid                    = $GetCalculations['sid'];
            $href_s = null;
            if($sid){
                $href_s = env('APP_URL');
                $href_s .= '/training/sessions/';
                $href_s .= $sid;
                $href_s .= '/edit?post_type=session';
                $href_s = '<a target="_blank" href="'.$href_s.'" class="btn btn-sm btn-primary">Edit</a>';
            }
            $href = $GetCalculations['href'];
            $attendants_link = null;
            if($href){
                $href_new = env('APP_URL');
                $href_new .= '/training/session/attendant?session=';
                $href_new .= $sid;
                $href_new .= '&post_type=session';
                $attendants_link = '<a target="_blank" href="'.$href_new.'" class="btn btn-sm btn-primary">Attendants ('.$trainees_no.')</a>';
            }

            $material_cost_course   = $GetCalculations['material_cost_course'];
            $material_cost          = $GetCalculations['material_cost'];
            $delivery_cost          = $GetCalculations['delivery_cost'];
            $sales_value            = $GetCalculations['sales_value'];
            $gross_profit           = $GetCalculations['gross_profit'];
            $gross_margin           = $GetCalculations['gross_margin'];

        return Active::Edit([
            'eloquent' => $grossMargin,
            'post_type' => $post_type,
            // 'post_type' => $grossMargin->post_type,
            'demand_teams' => $demand_teams,
            'developers' => $developers,
            'trainers' => $trainers,
            'coins' => $coins,
            'lang' => $lang,
            'all_courses' => $all_courses,
            'session_array' => $session_array,
            'folder' => $folder,
            'times' => $times,

            'on_demand_cost' => NumberFormatWithComma($on_demand_cost),
            'trainer_cost' => NumberFormatWithComma($trainer_cost),
            'href_s' => $href_s,
            'attendants_link' => $attendants_link,
            'material_cost' => NumberFormatWithComma($material_cost),
            'delivery_cost' => NumberFormatWithComma($delivery_cost),
            'sales_value' => NumberFormatWithComma($sales_value),
            'gross_profit' => NumberFormatWithComma($gross_profit),
            'gross_margin' => NumberFormatWithComma($gross_margin),
        ]);
    }


    public function update(GrossMarginRequest $request, GrossMargin $grossMargin){

        $validated = $this->Validated($request->validated());
        $grossMargin->update($validated);

        return Active::Updated($grossMargin->trans_title);

    }

    public function destroy(GrossMarginRequest $request, GrossMargin $grossMargin){
        GrossMargin::where('id', $grossMargin->id)->SoftTrash();
        return Active::Deleted($grossMargin->title);
    }

}
