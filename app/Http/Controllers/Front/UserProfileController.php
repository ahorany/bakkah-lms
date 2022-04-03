<?php

namespace App\Http\Controllers\Front;

use App\Models\Training\Answer;
use App\Models\Training\Content;
use App\Models\Training\CourseRegistration;
use App\Models\Training\Exam;
use App\Models\Training\Question;
use App\Models\Training\Role;
use App\Models\Training\UserAnswer;
use App\Models\Training\UserContent;
use App\Models\Training\UserExam;
use App\Models\Training\UserQuestion;
use App\User;
use App\Constant;
use App\Models\Training\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Training\Course;
use App\Models\Training\Message;
use App\Models\Training\Reply;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    /************************************************************
     * UserProfileController: It is a class for User operations.
     *
     * ( Info page , Update Info , Logout ,
     *  Change Password Page,Update Changed Password)
     ***********************************************************/



    public function __construct()
    {
        // User Must Be Auth To Use Any Method In This Class
        $this->middleware('auth');

        // Permissions
//        $this->middleware('permission:user.change.role', ['only' => ['change_role']]);
    }



    /*
     *  Logout Auth User
     */
    public function logout(Request $request) {
        Auth::logout();
        return redirect(route('login'));
    } // end function





    /*
     * Change Password Page
     */
    public function change_password() {
        return view('pages.change_password');
    } // end function




    /*
     * Save Changed Password
     */
    public function save_password() {
        request()->validate([
            'old_password' => 'required|min:7|max:16',
            'new_password' => 'required|min:7|max:16',
            'password_confirmation' => 'required|min:7|max:16|same:new_password',
        ]);

        $user = User::find(auth()->user()->id);
        $password = $user->password;

        if(Hash::check(request()->old_password, $password) &&(request()->new_password == request()->password_confirmation)){
            $password = Hash::make(request()->new_password);
        }

        $user->update([
            'password' => $password,
        ]);

        return redirect(route('user.home'));
    } // end function




    /*
     * User Info Page
     */
    public function info() {
        $user = DB::select("SELECT users.id,user_branches.name as user_name, users.lang,users.email,users.mobile,
                                        users.gender_id,users.company,users.job_title,uploads.file
                                 FROM users
                                 INNER JOIN user_branches ON users.id = user_branches.user_id
                                            AND user_branches.deleted_at IS NULL
                                            AND user_branches.branch_id = ".getCurrentUserBranchData()->branch_id."
                                 LEFT JOIN uploads ON uploads.uploadable_id = users.id
                                    AND uploads.deleted_at IS NULL
                                    AND uploads.uploadable_type = 'App\\\User'
                                 WHERE users.deleted_at IS NULL AND users.id =".\auth()->user()->id);
        if(!$user){
            abort(404);
        }
        $user = $user[0];
        $genders = Constant::where('parent_id', 42)->get();
        return view('pages.info',compact('user', 'genders'));
    } // end function





    /*
     * Update User Info
     */
    public function update($id,Request $request){

        $request->validate([
            'name' => 'required',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'language' => 'required',
            'gender_id' => 'required|exists:constants,id',
        ]);

        $user = User::findOrFail(\auth()->id());

        $user->update([
            'lang'      => $request->language,
            'mobile'    => $request->mobile,
            'gender_id' => $request->gender_id,
            'company' => $request->company,
            'job_title' => $request->job_title,
        ]);

        DB::table('user_branches')
            ->where('branch_id',getCurrentUserBranchData()->branch_id)
            ->where('user_id',\auth()->id())
            ->update([
                'name'  =>  $request->name,
            ]);

        User::UploadFile($user, ['method'=>'update']);

        return redirect()->back();
    } // end function




    /*
     * Admin Change Role (To Preview another roles)
     */
    public function change_role($role_id){
        if (is_super_admin()){
            abort(404);
        }

        $user = \auth()->user();
        $user_role = $user->roles()->first();

        $user_branch = getCurrentUserBranchData();
        if (!$user_branch){
            abort(404);
        }

        $branch_role_admin  = Role::where('branch_id',$user_branch->branch_id)->where('role_type_id',510)->first();
        if (!$branch_role_admin){
            abort(404);
        }

        if ( !($user_role->id == $branch_role_admin->id || $user_branch->delegation_role_id == $branch_role_admin->id ) ){
            abort(404);
        }

        if ($role_id == $branch_role_admin->id){
            // update user role
            DB::table('model_has_roles')
                ->join('roles','roles.id','=','model_has_roles.role_id')
                ->where('model_has_roles.model_id',$user->id)
                ->where('roles.branch_id',$user_branch->branch_id)
                ->delete();
            $user->assignRole([$role_id]);

            // update  delegation_role_id to return status (Admin)
            DB::table('user_branches')->where('id',$user_branch->id)->update([
                'delegation_role_id' => null,
            ]);

            $user_branch->delegation_role_id = null;
            session()->put("user_branch", $user_branch);
        }else{
            // update user role
            DB::table('model_has_roles')
                ->join('roles','roles.id','=','model_has_roles.role_id')
                ->where('model_has_roles.model_id',$user->id)
                ->where('roles.branch_id',$user_branch->branch_id)
                ->delete();
            $user->assignRole([$role_id]);

            // update  delegation_role_id to return status (Admin)
            DB::table('user_branches')->where('id',$user_branch->id)->update([
                'delegation_role_id' => $branch_role_admin->id,
            ]);


            $user_branch->delegation_role_id = $branch_role_admin->id;
            session()->put("user_branch", $user_branch);
        }

         return redirect()->route('user.home');
    }// end function



    /*
     * Admin Change Branch (To Preview another Branch)
     */
    public function change_branch($branch_id){
        if (is_super_admin()){
            DB::table('user_branches')->where('user_id',\auth()->id())->update(['branch_id' => $branch_id]);
        }

       $user_branch = DB::select("SELECT branches.title ,  user_branches.id , user_branches.branch_id , user_branches.user_id ,user_branches.name , user_branches.bio , user_branches.expire_date , user_branches.delegation_role_id  FROM `user_branches`
                                INNER JOIN branches ON branches.id = user_branches.branch_id AND branches.deleted_at IS NULL
                                WHERE user_branches.user_id = ".\auth()->id()." AND user_branches.branch_id = ? AND user_branches.deleted_at IS NULL",[$branch_id]);

        if (isset($user_branch[0])){
            session()->put('user_branch',$user_branch[0]);
        }

        return redirect()->route('user.home');
    }// end function



    ////////////////  Zoom Test  /////////////////////

//    public function join_zoom(){
//        return view('pages.zoom.join');
//    }
//
//    public function meeting(){
//        $nickname = session()->get('nickname');
//        $meetingId = session()->get('meetingId');
//        return view('pages.zoom.meeting',compact('nickname','meetingId'));
//    }
//
//    public function add_join_zoom(Request $request){
//        $nickname = $request->nickname;
//        $meetingId = $request->meetingId;
//        return redirect(route("user.meeting"))->with(['nickname' => $nickname , 'meetingId' => $meetingId]);
//    }



} // End Class
