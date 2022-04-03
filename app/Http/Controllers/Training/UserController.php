<?php

namespace App\Http\Controllers\Training;

use DB;
use App\User;
use App\Constant;
use App\Helpers\Active;
use Illuminate\Http\Request;
use App\Http\Requests\Training\UserRequest;
use App\Http\Controllers\Controller;
use App\Mail\UserMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Training\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training.users.index', ['only' => ['index']]);
        $this->middleware('permission:users.create', ['only' => ['create','store']]);
        $this->middleware('permission:users.edit', ['only' => ['edit','update']]);
        $this->middleware('permission:users.delete', ['only' => ['destroy']]);


        Active::$namespace = 'training';
        Active::$folder = 'users';
    }



    /////////////////////////////////  List Users With Search and statistics   ///////////////////////////////////////////////

    public function index()
    {
        $post_type = GetPostType('users');
        $trash = GetTrash();

        $users = User::with(['roles' => function($q){
            $q->select('id','name')->where('branch_id',getCurrentUserBranchData()->branch_id??1);
        }])->whereHas('roles',function ($q){
            $q->where('branch_id',getCurrentUserBranchData()->branch_id??1)->where('roles.id','<>',4);
        })->whereHas('branches',function ($q){
            $q->where('branch_id',getCurrentUserBranchData()->branch_id??1);
        });



        if (!is_null(request()->user_search)) {
            $users = $this->SearchUser($users);
        }

        $count = $users->count();
        $users = $users->page();


        $learners_no = $this->getLearnersNo();
        $complete_courses_no = $this->getCompleteCoursesNo();
        $courses_in_progress = $this->getCoursesInProgress();
        $courses_not_started = $this->getCoursesNotStarted();

        return Active::Index(compact('users', 'count', 'post_type', 'trash','learners_no','complete_courses_no','courses_in_progress','courses_not_started'));
    }


    private function SearchUser($eloquent){

        $eloquent1 = $eloquent->where(function ($query) {
            $query->where('users.name', 'like', '%' . request()->user_search . '%')
                ->orWhere('users.email', 'like', '%' . request()->user_search . '%')
                ->orWhere('users.mobile', 'like', '%' . request()->user_search . '%')
                ->orWhere('users.job_title', 'like', '%' . request()->user_search . '%')
                ->orWhere('users.company', 'like', '%' . request()->user_search . '%');
        });
        return $eloquent1;
    }

    private function getLearnersNo(){
        $learners_no  = DB::table('model_has_roles')->where('model_has_roles.role_id',3);
        if (!is_null(request()->user_search)) {
            $learners_no = $learners_no->join('users','users.id','model_has_roles.model_id');
            $learners_no = $this->SearchUser($learners_no);
        }
        return $learners_no->count();
    }

    private function getCompleteCoursesNo(){
        $complete_courses_no = DB::table('courses_registration')->where('progress',100);
        if (!is_null(request()->user_search)) {
            $complete_courses_no = $complete_courses_no->join('users','users.id','courses_registration.user_id');
            $complete_courses_no = $this->SearchUser($complete_courses_no);
        }
        return $complete_courses_no->count();
    }

    private function getCoursesInProgress(){
        $courses_in_progress = DB::table('courses_registration')->where('progress','<',100)->where('progress','>',0);
        if (!is_null(request()->user_search)) {
            $courses_in_progress = $courses_in_progress->join('users','users.id','courses_registration.user_id');
            $courses_in_progress = $this->SearchUser($courses_in_progress);
        }
        return $courses_in_progress->count();
    }

    private function getCoursesNotStarted(){
        $courses_not_started = DB::table('courses_registration')->where('progress',0);
        if (!is_null(request()->user_search)) {
            $courses_not_started = $courses_not_started->join('users','users.id','courses_registration.user_id');
            $courses_not_started = $this->SearchUser($courses_not_started);
        }
        return $courses_not_started->count();
    }

/////////////////////////////////  Create User   ///////////////////////////////////////////////

    public function create()
    {
        $post_type = GetPostType('users');
        $genders = Constant::where('parent_id', 42)->get();
        $current_user_branch =  getCurrentUserBranchData();
        $roles = Role::select('id','name')->where(function ($q) use($current_user_branch){
                                                  $q->where('branch_id',$current_user_branch->branch_id);
                                              })->where('id','<>',4)->get();


        return Active::Create([
            'eloquent' => new User(),
            'genders' => $genders,
            'post_type' => $post_type,
            'roles' => $roles,
        ]);
    }

    public function store(UserRequest $request)
    {
        $post_type = GetPostType('users');
        $validated = $request->validated();
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;


        $user = User::where('email',$request->email)->first();
        if (!$user){
            $user = User::create([
                'email'      => $request->email,
                'gender_id'  => $request->gender_id,
                'password'   => $validated['password'],
                'created_by' => $validated['created_by'],
                'updated_by' => $validated['updated_by'],
            ]);
        }

        $user->assignRole([request()->role]);


        \DB::table('user_branches')->insert([
            'branch_id' => getCurrentUserBranchData()->branch_id,
            'user_id'   => $user->id,
            'name'      => $request->name,
        ]);

        Mail::to($user->email)->send(new UserMail($user->id ,  $request->password));

        return Active::Inserted($user->trans_name,[
            'post_type' => $post_type,
        ]);
    }



/////////////////////////////////  Update User   ///////////////////////////////////////////////

    public function edit(User $user)
    {
        $post_type = GetPostType('users');
        $genders = Constant::where('parent_id', 42)->get();

        $current_user_branch =  getCurrentUserBranchData();
        $roles = Role::select('id','name')->where(function ($q) use($current_user_branch){
            $q->where('branch_id',$current_user_branch->branch_id);
        })->where('id','<>',4)->get();

        $role_id = $user->roles()->where('branch_id',$current_user_branch->branch_id)->select('roles.id')->first()->id??-1;


        return Active::Edit([
            'eloquent' => $user,
            'post_type' => $post_type,
            'genders' => $genders,
            'roles' => $roles,
            'role_id' => $role_id,
            'edit' => 'edit',
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $post_type = GetPostType('users');
        $validated = $request->validated();
        $validated['name'] = null;
        $validated['updated_by'] = auth()->user()->id;
        unset($validated['password']);

        $user->update($validated);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        DB::table('model_has_roles')->where('model_id',$user->id)->delete();
        $user->assignRole([request()->role]);

        User::UploadFile($user, ['method' => 'update']);


        return Active::Updated($user->trans_name, [
            'post_type' => $post_type,
        ]);
    }



/////////////////////////////////  Destroy User   ///////////////////////////////////////////////

    public function destroy(User $user, Request $request)
    {
        User::where('id', $user->id)->SoftTrash();
        return Active::Deleted($user->trans_name);
    }



/////////////////////////////////  Restore User   ///////////////////////////////////////////////

    public function restore($user)
    {
        User::where('id', $user)->RestoreFromTrash();
        $user = User::where('id', $user)->first();
        return Active::Restored($user->trans_name);
    }



    public function getUserData()
    {
       $user_branch =  \DB::select("SELECT users.email , users.gender_id ,users.mobile, user_branches.*  FROM user_branches
                                    RIGHT JOIN users ON users.id = user_branches.user_id
                                    WHERE  users.email = '".\request()->email."'
                                    LIMIT 1");

        if (isset($user_branch[0])){
            return response()->json(['status' => true,'data' => $user_branch[0] ]);
        }
        return response()->json(['status' => false]);
    }

} // end class
