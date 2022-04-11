<?php


namespace App\Http\Controllers\Api;


use App\Models\Training\Course;
use App\Models\Training\CourseRegistration;
use App\Models\Training\Session;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Mail\UserMail;
use Illuminate\Support\Facades\Mail;

class UserApiController
{

    private function validation($request){
        $session_id = '';
        $session_date_from = '';
        $session_date_to = '';
        $attendance_count = '';
        if ($request->session_id){
            $session_id        = "required";
            $session_date_from =  "required|date";
            $session_date_to   = "required|date";
            $attendance_count   = "required|numeric|min:0|not_in:0";
        }

        $rules = [
            "name"                => "required|string",
            "email"               => "required|email",
            "password"            => "required",
            "course_id"           => "required|exists:courses,ref_id",
            "session_id"          => $session_id,
            "session_date_from"   => $session_date_from,
            "session_date_to"     => $session_date_to,
            "attendance_count"    => $attendance_count,
            "paid_status"         => "required|string",
            "expire_date"         => "required|date",
        ];


        return Validator::make(\request()->all(), $rules);
    }

    public function add_users(Request $request){
        try{
            $validator = $this->validation($request);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'code' => 403 ,
                    'message' => "Validation Error" ,
                    'errors' => $validator->errors()
                ],403 );
            }


            if ($request->paid_status == 'paid'){
                $paid_status = 503;
            }else{
                $paid_status = 504;
            }

            $user = $this->addUser($request);

            $course = Course::select('id','ref_id')->where('ref_id',$request->course_id)->first();

            $courseRegistration =  CourseRegistration::where( 'user_id',$user->id)
                ->where('course_id',$course->id)
                ->first();

            if ($request->session_id){
                $session = Session::firstOrCreate([
                    'ref_id'           => $request->session_id,
                    'course_id'        => $course->id,
                ],[
                    'date_from'        => $request->session_date_from,
                    'date_to'          => $request->session_date_to,
                    'attendance_count' => $request->attendance_count,
                    'branch_id'        => 1,
                ]);


                if(!$courseRegistration){
                    $courseRegistration =  CourseRegistration::create([
                        'user_id'     => $user->id,
                        'course_id'   => $course->id,
                        'role_id'     => 3,
                        'expire_date' => $request->expire_date,
                        'paid_status' => $paid_status,
                        'session_id'  => $session->id,
                    ]);
                }else{
                    $courseRegistration->update([
                        'user_id'     => $user->id,
                        'course_id'   => $course->id,
                        'expire_date' => $request->expire_date,
                        'paid_status' => $paid_status,
                        'session_id'  => $session->id,
                    ]);
                }

            }else{
                if(!$courseRegistration){
                    $courseRegistration = CourseRegistration::create([
                        'user_id'     => $user->id,
                        'course_id'   => $course->id,
                        'role_id'     => 3,
                        'expire_date' => $request->expire_date,
                        'paid_status' => $paid_status,
                    ]);


                }else{
                    $courseRegistration->update([
                        'user_id'     => $user->id,
                        'course_id'   => $course->id,
                        'expire_date' => $request->expire_date,
                        'paid_status' => $paid_status,
                    ]);
                }
            }


            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => "Add User Successfully" ,
                'data' => ["user" => $courseRegistration]
            ],200);

        }catch (\Exception $exception){
            return response()->json([
                'status' => 'fail',
                'code' => 500 ,
                'message' => "Server Error" ,
            ],500 );
        }
    }


    private function addUser($request){
        $request->request->add(['en_name'=>$request->name]);
        $request->request->add(['ar_name'=>$request->name]);
        $user = User::firstOrCreate([
            'email'    => $request->email,
        ],[
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt("$request->password"),
        ]);

        $user->assignRole([3]);
        Mail::to($user->email)->send(new UserMail($user->id ,  $request->password));

        return $user;
    }



    public function updateAttendanceCount(){

        $rules = [
            "email"               => 'required',
            "attendance_count"    => "required|numeric|min:0|not_in:0",
            "session_id"          => "required",
        ];

        $validator = Validator::make(\request()->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'status' => 'fail',
                'code' => 403 ,
                'message' => "Validation Error" ,
                'errors' => $validator->errors()
            ],403 );
        }

     try{
         $courses_registration =   DB::select("SELECT courses_registration.id as courses_registration_id,courses_registration.attendance_count   FROM users
                                                            INNER JOIN courses_registration ON courses_registration.user_id = users.id
                                                            INNER JOIN sessions ON sessions.id = courses_registration.session_id
                                                                                AND sessions.ref_id = ?
                                                                                AND sessions.deleted_at IS NULL
                                    WHERE users.email = ?
                                    AND sessions.branch_id = 1
                                    AND users.deleted_at IS NULL ",[\request()->session_id,request()->email]);

           if (isset($courses_registration[0])){
               CourseRegistration::where('id',$courses_registration[0]->courses_registration_id)->update([
                   'attendance_count' => \request()->attendance_count,
               ]);
              return response()->json([
                 'status' => 'success',
                 'code' => 200,
                 'message' => "Update User Attendance Count Successfully" ,
              ],200);
           }else{
               return response()->json([
                   'status' => 'fail',
                   'code' => 404 ,
                   'message' => "Not found This user in this course" ,
               ],404 );
           }
     }catch (\Exception $exception){
            return response()->json([
                'status' => 'fail',
                'code' => 500 ,
                'message' => "Server Error" ,
            ],500 );
        }
    }


}
