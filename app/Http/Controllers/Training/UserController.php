<?php

namespace App\Http\Controllers\Training;

use App\Models\Training\UserBranch;
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
use App\Models\Training\CourseRegistration;

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

        $users = UserBranch::where('branch_id',getCurrentUserBranchData()->branch_id??1)
            ->with(['user'=> function($q){
                $q->with(['roles' => function($q){
                    $q->where('branch_id',getCurrentUserBranchData()->branch_id??1)
                        ->where('roles.id','<>',4);
                }]);
            }])->whereHas('user.roles',function ($q){
                $q->where('branch_id',getCurrentUserBranchData()->branch_id??1)->where('roles.id','!=',4);
            });


        $branch_id = getCurrentUserBranchData()->branch_id;

        if (!is_null(request()->user_search)) {
            $users = $this->SearchUser($users,true);
        }

        $count = $users->count();
        $users = $users->page();
        $learners_no  = User::getLearnersNo();
        if(!is_null(request()->user_search)) {
            $learners_no = $this->SearchUser($learners_no);
        }
        $learners_no =  $learners_no->count();
        //
        $users_no = $count;
        $complete_courses_no_sql = CourseRegistration::getCoursesNo(null,512);
        // dd($complete_courses_no_sql->count());
        $complete_courses_no_sql =  $complete_courses_no_sql->whereRaw('courses_registration.progress >= courses.complete_progress')
                                                            ->where('courses_registration.progress','!=',0);;
        if (!is_null(request()->user_search)) {
            $complete_courses_no_sql = $this->SearchUser($complete_courses_no_sql);
        }
        $complete_courses_no =  $complete_courses_no_sql->count();
        // dd($complete_courses_no);
        $courses_in_progress_sql = CourseRegistration::getCoursesNo(null,512);
        $courses_in_progress_sql =  $courses_in_progress_sql->whereRaw('courses_registration.progress < courses.complete_progress')
                                            ->where('courses_registration.progress','>',0);
        if (!is_null(request()->user_search)) {
            $courses_in_progress_sql = $this->SearchUser($courses_in_progress_sql);
        }
        $courses_in_progress =  $courses_in_progress_sql->count();

        $courses_not_started_sql = CourseRegistration::getCoursesNo(null,512);
        $courses_not_started_sql =  $courses_not_started_sql->where('progress',0);
        if (!is_null(request()->user_search)) {
            $courses_not_started_sql = $this->SearchUser($courses_not_started_sql);
        }
        $courses_not_started =  $courses_not_started_sql->count();
        // dd($complete_courses_no->count(),    );
        return Active::Index(compact('users', 'count', 'post_type', 'trash','users_no','complete_courses_no','courses_in_progress','courses_not_started'));
    }


    private function SearchUser($eloquent,$is_eloquent = false){

        if ($is_eloquent){
            $eloquent1 = $eloquent->where(function ($query) {
                $query->where('user_branches.name', 'like', '%' . request()->user_search . '%');
            })->orWhereHas('user',function ($q){
                $q->where('email', 'like', '%' . request()->user_search . '%');
            });
        }else{
            $eloquent1 = $eloquent->where(function ($query) {
                $query->where('user_branches.name', 'like', '%' . request()->user_search . '%');
                $query->orWhere('users.email', 'like', '%' . request()->user_search . '%');
            });
        }
        return $eloquent1;
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
                'mobile'     => $request->mobile,
                'password'   => bcrypt($validated['password']),
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

        Mail::to($user->email)->send(new UserMail($user ,$request->name,  $request->password));

        return Active::Inserted($user->email,[
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


        $user_branch = DB::table('user_branches')
                      ->where('user_id',$user->id)
                      ->where('branch_id',$current_user_branch->branch_id)->first();


        return Active::Edit([
            'eloquent' => $user,
            'user_branch' => $user_branch,
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


        $user->update([
            'gender_id'  => $request->gender_id,
            'mobile'     => $request->mobile,
            'updated_by' => $validated['updated_by'],
            ]);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }


        \DB::table('user_branches')
            ->where('user_id',$user->id)
            ->where('branch_id',getCurrentUserBranchData()->branch_id)
            ->update([
              'name' => $request->name,
          ]);


        DB::table('model_has_roles')->where('model_id',$user->id)
            ->join('roles','roles.id','model_has_roles.role_id')
            ->where('roles.branch_id',getCurrentUserBranchData()->branch_id)
            ->delete();
        $user->assignRole([request()->role]);


        User::UploadFile($user, ['method' => 'update']);

        if ($request->password){
            Mail::to($user->email)->send(new UserMail($user->id ,$request->name,  $request->password));
        }

//        User::where('id',$user->id)->update(['is_logout' => 1]);

        return Active::Updated($user->email, [
            'post_type' => $post_type,
        ]);
    }



/////////////////////////////////  Destroy User   ///////////////////////////////////////////////

    public function destroy(User $user, Request $request)
    {
        UserBranch::where('user_id', $user->id)
            ->where('branch_id',getCurrentUserBranchData()->branch_id)->SoftTrash();

        User::where('id',$user->id)->update(['is_logout' => 1]);

        return Active::Deleted($user->email);
    }



/////////////////////////////////  Restore User   ///////////////////////////////////////////////

    public function restore($user)
    {
        UserBranch::where('user_id', $user)->where('branch_id',getCurrentUserBranchData()->branch_id)->RestoreFromTrash();
        User::where('id',$user)->update(['is_logout' => 1]);
        $user = User::where('id', $user)->first();
        return Active::Restored($user->email);
    }



    public function getUserData()
    {
       $user_branch =  \DB::select("SELECT users.email , users.gender_id ,users.mobile, user_branches.*
                                    FROM user_branches
                                    RIGHT JOIN users ON users.id = user_branches.user_id
                                    INNER JOIN model_has_roles ON users.id = model_has_roles.model_id AND model_has_roles.role_id != 4
                                    WHERE  users.email = '".\request()->email."'
                                    LIMIT 1");


        if (isset($user_branch[0])){
            return response()->json(['status' => true,'data' => $user_branch[0] ]);
        }
        return response()->json(['status' => false]);
    }

} // end class
