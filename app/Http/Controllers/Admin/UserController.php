<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\User;
use App\Constant;
use App\Helpers\Active;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        Active::$folder = 'users';
    }

    public function index()
    {

        $post_type = GetPostType('users');
        $trash = GetTrash();

        $users = User::with('upload');

        if (!is_null(request()->user_search)) {
            $users = $users->where(function ($query) {
                $query->where('name', 'like', '%' . request()->user_search . '%')
                    ->orWhere('email', 'like', '%' . request()->user_search . '%')
                    ->orWhere('mobile', 'like', '%' . request()->user_search . '%')
                    ->orWhere('job_title', 'like', '%' . request()->user_search . '%')
                    ->orWhere('company', 'like', '%' . request()->user_search . '%');
            });
        }

        // if(!is_null(request()->role)) {
        //     $users = $users->where(function($query){
        //         $query->where('user_type', request()->role);
        //     });
        // }
        $user_type = '';
        if (!is_null(request()->post_type) && request()->post_type == 'users') {
            //$post_type = GetPostType('trainers');
            $users = $users->where('user_type', 41)->orWhereNull('user_type');
            $user_type = 41;
        }

        if (!is_null(request()->post_type) && request()->post_type == 'trainers') {
            //$post_type = GetPostType('trainers');
            $users = $users->where('user_type', 326);
            $user_type = 326;
        }

        if (!is_null(request()->post_type) && request()->post_type == 'employees') {
            //$post_type = GetPostType('employees');
            $users = $users->orWhere('user_type', 315);
            $user_type = 315;
        }

        if (!is_null(request()->post_type) && request()->post_type == 'developers') {
            //$post_type = GetPostType('employees');
            $users = $users->where('user_type', 402);
            $user_type = 402;
        }

        if (!is_null(request()->post_type) && request()->post_type == 'on-demand-team') {
            //$post_type = GetPostType('employees');
            $users = $users->where('user_type', 403);
            $user_type = 403;
        }

        // dump($users->toSql());

        $count = $users->count();
        $users = $users->page();

        // dd($users);
        // $posts = User::with('upload');
        return Active::Index(compact('users', 'count', 'post_type', 'trash'));
    }

    public function create()
    {
        $post_type = GetPostType('users');
        $constants = Constant::where('parent_id', 30)->get();
        $genders = Constant::where('parent_id', 42)
            ->orWhere('post_type', 'employee')
            // ->orWhere('post_type', 'bakkah-employee')
            ->get();
        $countries = Constant::where('post_type', 'countries')->get();

        $roles = Role::all();
        // $roles = [];

        $training_field = Constant::where('parent_id', 404)->get();
        $activity_level = Constant::where('parent_id', 412)->get();
        $level_of_education = Constant::where('parent_id', 416)->get();
        $session_can_handle = Constant::where('parent_id', 421)->get();

        $user_type = '';
        if ($post_type == 'users') {
            $user_type = 41;
        }
        if ($post_type == 'trainers') {
            $user_type = 326;
        }
        if ($post_type == 'employees') {
            $user_type = 315;
        }
        if ($post_type == 'developers') {
            $user_type = 402;
        }
        if ($post_type == 'on-demand-team') {
            $user_type = 403;
        }

        return Active::Create([
            'eloquent' => new User(),
            'object' => User::class,
            'constants' => $constants,
            'genders' => $genders,
            'post_type' => $post_type,
            'countries' => $countries,
            'training_field' => $training_field,
            'activity_level' => $activity_level,
            'level_of_education' => $level_of_education,
            'session_can_handle' => $session_can_handle,
            'user_type' => $user_type,
            'roles' => $roles
        ]);
    }

    public function store(UserRequest $request)
    {

        $validated = $request->validated();
        $validated['name'] = null;
        $validated['trainer_courses_for_certifications'] = null;
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;
        $validated['password'] = $request->password ? bcrypt($request->password) : bcrypt('bakkah');
        //dd($validated);
        $user = User::create($validated);

        $user->roles()->attach(request()->roles);

        $post_type = request()->post_type;
        //User::SetMorph($user->id);
        //        User::UploadFile($user);

        $profile = $user->profile()->create([
            'iqama_number' => request()->iqama_number,
            'passport_id' => request()->passport_id,
            'passport_expiry_date' => request()->passport_expiry_date,
            'experience' => request()->experience,
            'training_field_id' => request()->training_field_id,
            'activity_level_id' => request()->activity_level_id,
            'certifications' => request()->certifications,
            'level_education_id' => request()->level_education_id,
            'session_handle_id' => request()->session_handle_id,
            'certifications' => request()->certifications,
            'courses_b2c' => request()->courses_b2c,
            'courses_b2b' => request()->courses_b2b,
            // 'morning_rate_online' => request()->morning_rate_online??0,
            // 'evening_rate_online' => request()->evening_rate_online??0,
            // 'morning_rate_classroom' => request()->morning_rate_classroom??0,
            // 'evening_rate_classroom' => request()->evening_rate_classroom??0,
            // 'daily_rate_online' => request()->daily_rate_online??0,
            // 'daily_rate_classroom' => request()->daily_rate_classroom??0,
            'classification' => request()->classification,
            'number_projects' => request()->number_projects,
            'joining_bakkah' => request()->joining_bakkah,
            'note' => request()->note,
        ]);

        $this->uploadsPDF($profile, 'cv', null);
        $this->uploadsPDF($profile, 'certificates', null);
        $this->uploadsPDF($profile, 'financial_info', null);

        return Active::Inserted($user->trans_name,[
            'post_type' => $post_type,
        ]);
    }

    public function edit(User $user)
    {
        $post_type = GetPostType('users');
        $constants = Constant::where('parent_id', 30)->get();
        $genders = Constant::where('parent_id', 42)
            ->orWhere('post_type', 'employee')
            ->get();
        $constants = Constant::where('parent_id', 30)->get();
        $countries = Constant::where('post_type', 'countries')->get();

        $roles = Role::all();
        // $roles = [];

        $training_field = Constant::where('parent_id', 404)->get();
        $activity_level = Constant::where('parent_id', 412)->get();
        $level_of_education = Constant::where('parent_id', 416)->get();
        $session_can_handle = Constant::where('parent_id', 421)->get();

        $user_type = '';
        if ($post_type == 'users') {
            $user_type = 41;
        }
        if ($post_type == 'trainers') {
            $user_type = 326;
        }
        if ($post_type == 'employees') {
            $user_type = 315;
        }
        if ($post_type == 'developers') {
            $user_type = 402;
        }
        if ($post_type == 'on-demand-team') {
            $user_type = 403;
        }

        return Active::Edit([
            'eloquent' => $user,
            'post_type' => $post_type,
            'constants' => $constants,
            'genders' => $genders,
            'countries' => $countries,
            'training_field' => $training_field,
            'activity_level' => $activity_level,
            'level_of_education' => $level_of_education,
            'session_can_handle' => $session_can_handle,
            'user_type' => $user_type,
            'roles' => $roles
        ]);
    }

    public function update(UserRequest $request, User $user)
    {

        $validated = $request->validated();
        $validated['name'] = null;
        $validated['trainer_courses_for_certifications'] = null;
        $validated['updated_by'] = auth()->user()->id;

        $user = User::find($user->id);
        $old_password = $user->password;
        $user->update($validated);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        } else {
            $user->update([
                'password' => $old_password
            ]);
        }

        $user->roles()->sync(request()->roles);

        User::UploadFile($user, ['method' => 'update']);
        User::SetMorph($user->id);

        $post_type = request()->post_type;

        Profile::updateOrCreate([
                'user_id' => $user->id
            ],[
            'iqama_number' => request()->iqama_number,
            'passport_id' => request()->passport_id,
            'passport_expiry_date' => request()->passport_expiry_date,
            'experience' => request()->experience,
            'training_field_id' => request()->training_field_id,
            'activity_level_id' => request()->activity_level_id,
            'certifications' => request()->certifications,
            'level_education_id' => request()->level_education_id,
            'session_handle_id' => request()->session_handle_id,
            'certifications' => request()->certifications,
            'courses_b2c' => request()->courses_b2c,
            'courses_b2b' => request()->courses_b2b,
            'morning_rate_online' => request()->morning_rate_online??0,
            'evening_rate_online' => request()->evening_rate_online??0,
            'morning_rate_classroom' => request()->morning_rate_classroom??0,
            'evening_rate_classroom' => request()->evening_rate_classroom??0,
            'daily_rate_online' => request()->daily_rate_online??0,
            'daily_rate_classroom' => request()->daily_rate_classroom??0,
            'classification' => request()->classification,
            'number_projects' => request()->number_projects,
            'joining_bakkah' => request()->joining_bakkah,
            'note' => request()->note,
        ]);

        $this->uploadsPDF($user->profile, 'cv', null);
        $this->uploadsPDF($user->profile, 'certificates', null);
        $this->uploadsPDF($user->profile, 'financial_info', null);


        return Active::Updated($user->trans_name, [
            'post_type' => $post_type,
        ]);
    }

    public function destroy(User $user, Request $request)
    {
        User::where('id', $user->id)->SoftTrash();
        return Active::Deleted($user->trans_name);
    }

    public function restore($user)
    {
        User::where('id', $user)->RestoreFromTrash();
        $user = User::where('id', $user)->first();
        return Active::Restored($user->trans_name);
    }

    public function import_from_wp()
    {
        dd('stoped');
        $from_table = 'accounts';
        $bak_posts = DB::connection('mysql2')
            ->table($from_table)
            //            ->whereIn('id', ['1'])
            ->select('*')
            ->orderBy('email')
            ->get();
        //        dump($bak_posts->count());
        //        dd($bak_posts->unique('email')->count());
        foreach ($bak_posts->unique('email') as $post) {

            $_count = User::where('email', $post->email)->count();
            //            dd($_count);
            if ($_count == 0) {
                $post_type = '41';
                $gender = 43;
                if ($post->gender == 'female') {
                    $gender = 44;
                }
                request()->en_name = $post->fullname;
                request()->ar_name = $post->fullname;
                //            dump($post->company);
                $validated = [
                    'name' => null,
                    'mobile' => $post->mobile,
                    'email' => $post->email,
                    'gender_id' => $gender,
                    'job_title' => $post->job_title,
                    'company' => $post->company,
                    'date_time' => $post->date_time,
                    'country' => $post->country,
                    'city' => $post->city,
                    'ref_id' => $post->id,
                    'user_type' => $post_type,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ];
                //            dump($validated);
                $user = User::create($validated);
            }
        }
        dd('Done');
    }

    public function changePassword(User $user)
    {
        return view('admin.users.change-password', ['eloquent' => $user, 'folder' => 'users']);
    }

    public function savePassword(Request $request, User $user)
    {

        $validated = $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            return back()->with('success', 'Password successfully changed!');
        }
        return back()->with('error', 'Old password does not match your password!');
    }

    private function uploadsPDF($model, $name='pdf', $locale='en'){

        $full_name = $name;
        if($locale) {
            $full_name = $locale.'_'.$name;
        }

        if(request()->has($full_name)){

            $upload = $model->uploads()
            ->where('post_type', $name)
            ->where('locale', $locale)
            ->first();

            $pdf = request()->file($full_name);
            $title = $pdf->getClientOriginalName();

            $fileName = date('Y-m-d-H-i-s') . '-' . $full_name. '-' . $title;
            $fileName = strtolower($fileName);

            if($pdf->move(public_path('upload/pdf/'), $fileName)){

                if(is_null($upload))
                {
                    $model->uploads()->create([
                        'title'=>$title,
                        'file'=>$fileName,
                        'extension'=>'pdf',
                        'post_type'=>$name,
                        'created_by'=>$model->created_by,
                        'updated_by'=>$model->updated_by,
                        'locale'=>$locale,
                    ]);
                }
                else
                {
                    $this->unlinkPDF($name, $upload->file);
                    $model->uploads()->where('post_type', $name)
                    ->where('locale', $locale)
                    ->update([
                        'title'=>$title,
                        'file'=>$fileName,
                        'extension'=>'pdf',
                        'post_type'=>$name,
                        'created_by'=>$model->created_by,
                        'updated_by'=>$model->updated_by,
                        'locale'=>$locale,
                    ]);
                }
            }
        }
    }

    private function unlinkPDF($name, $image){
        if(request()->hasFile($name) && !empty($name) && !is_null($name) && !empty($image) && !is_null($image))
        {
            if(file_exists(public_path('/upload/pdf/') . $image)){
                unlink(public_path('/upload/pdf/') . $image);
            }
        }
    }
}
