<?php

namespace App\Http\Controllers\Front;

use App\Models\Training\Answer;
use App\Models\Training\Content;
use App\Models\Training\CourseRegistration;
use App\Models\Training\Exam;
use App\Models\Training\Question;
use App\Models\Training\UserAnswer;
use App\Models\Training\UserContent;
use App\Models\Training\UserExam;
use App\Models\Training\UserQuestion;
use App\User;
use App\Constant;
use App\Models\Admin\Role;
use App\Models\Admin\Upload;
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
        $user = User::with(['upload'])->findOrFail(auth()->user()->id);
        $genders = Constant::where('parent_id', 42)->get();
        $countries = Constant::where('post_type', 'countries')->get();
        return view('pages.info',compact('user', 'genders', 'countries'));
    } // end function





    /*
     * Update User Info
     */
    public function update($id,Request $request){

        $request->validate([
            'en_name' => 'required',
            'ar_name' => 'required',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'language' => 'required',
            'gender_id' => 'required|exists:constants,id',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name'      => json_encode(["en" => $request->en_name, "ar" => $request->ar_name]),
            'headline'  => $request->headline,
            'lang'      => $request->language,
            'bio'       => $request->bio,
            'mobile'    => $request->mobile,
            'gender_id' => $request->gender_id,
            'company' => $request->company,
            'job_title' => $request->job_title,
        ]);

        User::UploadFile($user, ['method'=>'update']);

        return redirect()->back();
    } // end function





    /*
     * Admin Change Role (To Preview another roles)
     */
    public function change_role($role_id){
        $user = \auth()->user();
        $user_role = $user->roles()->first();

        if ( !($user_role->id == 1 || $user->delegation_role_id == 1 ) ){
            abort(404);
        }


        if ($role_id == 1){
            // update user role
            DB::table('model_has_roles')->where('model_id',$user->id)->delete();
            $user->assignRole([$role_id]);

            // update  delegation_role_id to return status (Admin)
            User::whereId($user->id)->update([
                'delegation_role_id' => null,
            ]);
        }else{
            // update user role
            DB::table('model_has_roles')->where('model_id',$user->id)->delete();
            $user->assignRole([$role_id]);

            // update  delegation_role_id to return status (Admin)
            User::whereId($user->id)->update([
                'delegation_role_id' => 1,
            ]);
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
