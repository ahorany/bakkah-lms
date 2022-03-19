<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\CategoryRequest;
use App\Http\Requests\Training\SessionRequest;
use App\Models\Admin\Partner;
use App\Models\Training\Course;
use App\Models\Training\Category;
use App\Constant;
use App\Models\Training\Certificate;
use App\Models\Training\Group;
use App\Models\Training\Session;
use App\Models\Training\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use DB;

// use Illuminate\Support\Str;

class SessionController extends Controller
{
    public function __construct()
    {
         $this->middleware('permission:training.sessions.index');
         Active::$namespace = 'training';
         Active::$folder = 'sessions';
    }

    public function index(){
        $post_type = 'sessions';
        $trash = GetTrash();
        $sessions = Session::with(['course']);
        $count = $sessions->count();
        $sessions = $sessions->page();
        return Active::Index(compact('sessions', 'count', 'post_type', 'trash'));
    }


    public function create(){
        $courses = Course::select('id','title')->where('training_option_id','!=', 11)->get();
        return Active::Create(['courses' => $courses]);
    }

    public function store(SessionRequest $request){
        $validated = $request->validated();
        $validated['branche_id'] = 1;

        $course = Course::select('id','title')
            ->where('id',$validated['course_id'])
            ->where('training_option_id','!=', 11)->first();
        if (!$course){
            abort(404);
        }

        $session = Session::create($validated);
        return Active::Inserted('Session');
    }

    public function edit(Session $session){
        $courses = Course::select('id','title')->where('training_option_id','!=', 11)->get();
        return Active::Edit(['eloquent'=>$session,'courses'=> $courses]);
    }

    public function update(SessionRequest $request,Session $session){
        $validated = $request->validated();
        $validated['branche_id'] = 1;


        $course = Course::select('id','title')
            ->where('id',$validated['course_id'])
            ->where('training_option_id','!=', 11)->first();
        if (!$course){
            abort(404);
        }


        Session::find($session->id)->update($validated);
        return Active::Updated('Session');
    }


    public function destroy(Session $session){
        Session::where('id', $session->id)->SoftTrash();
        return Active::Deleted('Session');
    }

    public function restore($session){
        Session::where('id', $session)->RestoreFromTrash();
        $session = Session::where('id', $session)->first();
        return Active::Restored('The Session');
    }


}
