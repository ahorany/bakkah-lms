<?php


namespace App\Http\Controllers\Api;


use App\Models\Training\CourseRegistration;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UserApiController
{

    public function add_users(Request $request){
      // check api key

//            "email"   => "required|email|unique:users,email",
        $rules = [
            "name"   => "required|string",
            "email"   => "required|email",
            "password"   => "required",
            "course_id"   => "required|exists:courses,id",
        ];

        $validator = Validator::make(\request()->all(), $rules);

         if($validator->fails()) {
             return response()->json([
                 'status' => 'fail',
                 'code' => 403 ,
                 'message' => "Validation Error" ,
                 'errors' => $validator->errors()
             ],403 );
         }


      $user = User::firstOrCreate([
          'email' => $request->email,
      ],[
          'name' => $request->name,
          'email' => $request->email,
          'password' => bcrypt($request->password),
          'user_type' => 41,
      ]);

       $courseRegistration =  CourseRegistration::where( 'user_id',$user->id)
            ->where('course_id',$request->course_id)->first();

       if(!$courseRegistration){
           CourseRegistration::create([
               'user_id'   => $user->id,
               'course_id' => $request->course_id,
               'expire_date' => $request->expire_date,
           ]);
       }


        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => "Add User Successfully" ,
//            'data' => ["user" => $user , "course" => ]
        ],200);
    }


}
