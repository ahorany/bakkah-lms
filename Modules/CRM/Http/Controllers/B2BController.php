<?php

namespace Modules\CRM\Http\Controllers;

use App\Constant;
use App\Http\Controllers\Training\CartController;
use App\Models\Training\Course;
use App\Models\Training\Session;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Helpers\Active;
use Modules\CRM\Entities\B2bMaster;
use Modules\CRM\Entities\Organization;
use Illuminate\Contracts\Support\Renderable;
use Modules\CRM\Http\Requests\B2BRequest;
use Modules\CRM\Http\Requests\OrganizationRequest;
use Predis\Response\Status;

class B2BController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Active::$folder = 'crm::b2bs';
        Active::$namespace = null;
    }

    public function index()
    {
        $trash = GetTrash();
        $b2bs = B2bMaster::whereNotNull('id')->get();
        $organizations = Organization::select('title','id')->get();
        $status = Constant::where('parent_id', 354)->get();
        $all_courses = Course::orderBy('order')->get();
        $sessions = Session::where('course_id', \request()->course_id)->get();
        $CartController = new CartController();
        $session_array = $CartController->sessionsJson();

        $count = $b2bs->count();
        $post_type='b2bs';

        if (!is_null(\request()->organization_id)){

            if(request()->has('organization_id') && request()->organization_id != -1){
                $b2bs = B2bMaster::latest('created_at');
                $b2bs = $b2bs->whereHas('organization', function(Builder $query){
                    $query->where('id', request()->organization_id);
                })->get();
            }
        }

        if (!is_null(\request()->status_id)){

            if(request()->has('status_id') && request()->status_id != -1){
                $b2bs = B2bMaster::latest('created_at');
                $b2bs = $b2bs->whereHas('status', function(Builder $query){
                    $query->where('id', request()->status_id);
                })->get();
            }
        }

        if (!is_null(\request()->course_id)){

            if(request()->has('course_id') && request()->course_id != -1){
                $b2bs = B2bMaster::latest('created_at');
                $b2bs = $b2bs->whereHas('course', function(Builder $query){
                    $query->where('id', request()->course_id);
                })->get();
            }
        }

        if (!is_null(\request()->session_id)){

            if(request()->has('session_id') && request()->session_id != -1){
                $b2bs = B2bMaster::latest('created_at');
                $b2bs = $b2bs->whereHas('session', function(Builder $query){
                    $query->where('id', request()->session_id);
                })->get();
            }
        }

        Active::$folder = 'crm::b2bs';
        Active::$namespace = null;

        return Active::Index(compact('session_array','sessions','status','all_courses','organizations', 'b2bs', 'post_type', 'count', 'trash'));
    }



    public function create()
    {

        $b2bs = B2bMaster::with(['course', 'user', 'session']);
        if(request()->has('course_id') && request()->course_id != -1){

            $b2bs = $b2bs->where('course_id', request()->course_id);
        }
//        dd($b2bs);
        $data = $this->getInvoice('users','status',
                     'organizations','all_courses',
                       'training_id','sessions',
                      'session_array');

        $CartController = new CartController();
        $session_array = $CartController->sessionsJson();

        Active::$folder = 'crm::b2bs';
        Active::$namespace = null;

        return Active::Create([
            'organizations'=>$data['organizations'],
            'users'=>$data['users'],
            'status'=>$data['status'],
            'post_type'=>null,

            'b2bs'=>$b2bs,
            'all_courses'=>$data['all_courses'],
            'sessions'=>$data['sessions'],
            'session_array'=>$data['session_array'],
            'training_id'=>$data['training_id']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(B2BRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;

        $b2b = B2bMaster::create($validated);


        return Active::Inserted($b2b, ['post_type' => null]);
    }

    public function show($id)
    {
        return view('crm::show');
    }

    public function edit(B2bMaster $b2b)
    {

        $b2b = B2bMaster::findOrFail($b2b->id);


        $data = $this->getInvoice('users','status',
                          'organizations','all_courses',
                            'training_id','sessions','session_array');

//        $training = Session::where('training_option_id',)
        Active::$folder = 'crm::b2bs';
        Active::$namespace = null;

        return Active::Edit([
            'organizations'=>$data['organizations'],
            'users'=>$data['users'],
            'status'=>$data['status'],
            'post_type'=>null,
            'eloquent'=>$b2b,
            'all_courses'=>$data['all_courses'],
            'sessions'=>$data['sessions'],
            'session_array'=>$data['session_array'],
            'training_id'=>$data['training_id']
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(B2BRequest $request, B2bMaster $b2b)
    {
        $validated = $request->validated();

        $b2b->update($validated);

        return Active::Updated($b2b->trans_name);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(B2bMaster $b2b)
    {
        $b2b->SoftTrash($b2b);
        return Active::Deleted($b2b->trans_name);
    }

    public function restore($organization)
    {
        dd('done');
        Organization::where('id', $organization)->RestoreFromTrash();
        $organization = Organization::where('id', $organization)->first();
        return Active::Restored($organization->trans_name);
    }

    private function getInvoice($users, $status, $organizations,$all_courses,
                                $training_id,$sessions,$session_array)
    {
        $all_courses = Course::orderBy('order')->get();
        $sessions = Session::where('course_id', \request()->course_id)->get();
        $CartController = new CartController();
        $session_array = $CartController->sessionsJson();
        $training_id = $b2b->session->trainingOption->id ?? null;

        $users = Constant::where('parent_id', 42)->orWhere('post_type', 'employee')->get();
        $status = Constant::where('parent_id', 354)->get();
        $organizations = Organization::select('title', 'id')->get();
        return ['users' => $users, 'status' => $status, 'organizations' => $organizations,'all_courses'=>$all_courses
        ,'sessions'=>$sessions,'session_array'=>$session_array,'training_id'=>$training_id
        ];

    }



}
