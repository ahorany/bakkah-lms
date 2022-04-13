<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\CourseRequest;
use App\Models\Admin\Partner;
use App\Models\Training\Role;
use App\Models\Training\Course;
use App\Constant;
use App\Mail\MessageMail;
use App\Mail\ReplyMail;
use App\Models\Training\CourseRegistration;
use App\Models\Training\Group;
use App\Models\Training\Like;
use App\Models\Training\Message;
use App\Models\Training\RecipientMessage;
use App\Models\Training\Reply;
use App\Models\Training\Unit;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

// use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'messages';
    }

    public function addMessage(){
        $user = \auth()->user();
        $user_auth_role = $user->roles()->first();
        if($user_auth_role->role_type_id != 511 && $user_auth_role->role_type_id != 512){
            $courses = DB::select("SELECT id , title FROM courses
                                          WHERE branch_id = ".getCurrentUserBranchData()->branch_id
                ." AND deleted_at IS NULL");
        }else{
            $courses = DB::select("SELECT courses.id , courses.title FROM courses_registration
                                         INNER JOIN courses ON courses.id = courses_registration.course_id
                                                            AND courses.branch_id = ".getCurrentUserBranchData()->branch_id
                ."                                          AND deleted_at IS NULL
                                        WHERE courses_registration.user_id = ".$user->id);
        }
        $roles = Role::where(function ($q){
            $q->where('role_type_id','!=',512)->orWhere('role_type_id',null);
        })->where('branch_id',getCurrentUserBranchData()->branch_id)->get();

        return view('training.messages.form',compact('courses', 'roles'));
    }

    public function sendMessage()
    {
        $rules = [
            'recipient_id' => ['required', 'not_in:4',
                Rule::exists('roles', 'id')
                    ->where('branch_id', getCurrentUserBranchData()->branch_id)
            ],
            "subject" => "required|max:100",
            "description" => "required",
            'course_id' => ['required',
                Rule::exists('courses', 'id')
                    ->where('branch_id', getCurrentUserBranchData()->branch_id)
            ],
        ];

        $user = \auth()->user();
        $user_auth_role = $user->roles()->first();
        if ($user_auth_role->role_type_id == 511 || $user_auth_role->role_type_id == 512) {
            $courseRegistration = CourseRegistration::where('course_id', request()->course_id)->where('user_id', $user->id)->first();
            if (!$courseRegistration) {
                abort(404);
            }
        }

        $validator = Validator::make(\request()->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $recipient_role = Role::select('id', 'role_type_id')->where('id', request()->recipient_id)->first();
        // if recipient is trainee => ABORT(404)
        if ($recipient_role->role_type_id == 512) {
            abort(404);
        }

        $msg = Message::create([
            'user_id' => $user->id,
            'course_id' => request()->course_id,
            'role_id' => request()->recipient_id,
            'title' => request()->subject,
            'description' => request()->description,
            'type' => 'message',
        ]);


        // if Instructor get users recipients data
        if ($recipient_role->role_type_id == 511) {
            $recipients = DB::select("SELECT users.email,user_branches.name,courses_registration.user_id ,courses_registration.role_id FROM courses_registration
                                                   INNER JOIN users ON users.id = courses_registration.user_id
                                                                   AND users.deleted_at IS NULL
                                                   INNER JOIN user_branches ON user_branches.user_id = users.id
                                                           AND  user_branches.branch_id = " . getCurrentUserBranchData()->branch_id . "
                                                   WHERE courses_registration.course_id = ? AND  courses_registration.role_id = ?", [request()->course_id, request()->recipient_id]);
        } else {
            $recipients = DB::select("SELECT users.email ,user_branches.name,users.id as user_id , model_has_roles.role_id FROM users
                                                   INNER JOIN user_branches ON user_branches.user_id = users.id
                                                           AND  user_branches.branch_id = " . getCurrentUserBranchData()->branch_id . "
                                                    INNER JOIN model_has_roles ON model_has_roles.model_id = users.id
                                                           AND  model_has_roles.role_id = ?
                                                    WHERE users.deleted_at IS NULL", [request()->recipient_id]);
        }


      if(env('SEND_MAIL')==true){
            $message_with_sender_with_course = DB::select("SELECT courses.title,user_branches.name,messages.title as msg_title,messages.description as msg_description FROM messages
                                                            INNER JOIN courses ON courses.id = messages.course_id AND courses.deleted_at IS NULL
                                                            INNER JOIN user_branches ON user_branches.user_id =  messages.user_id AND user_branches.branch_id = " . getCurrentUserBranchData()->branch_id . "
                                                            WHERE messages.id = " . $msg->id . " AND messages.type = 'message' AND messages.deleted_at IS NULL");
      }

     foreach ($recipients as $recipient){
        RecipientMessage::create([
            'user_id' => $recipient->user_id,
            'role_id' => $recipient->role_id,
            'message_id' => $msg->id,
         ]);

         if(env('SEND_MAIL')==true){
            Mail::to($recipient->email)->send(new MessageMail($message_with_sender_with_course[0] , $recipient));
         }
      }

        return redirect()->route('user.messages.inbox',['type' => 'sent']);
    }

    public function replyMessage($id){
        $message = Message::where('id',$id)->with(['course.course','user','replies.user'])->first();
        if(!$message || !$message->course){
            abort(404);
        }
        return view('training.messages.reply',compact('message'));
    }

    public function addreply(){
        $message =  Message::where('id',request()->message_id)->first();
        if(!$message){
            abort(404);
        }

        $rules = [
            "reply"   => "required|string",
        ];

        $validator = Validator::make(\request()->all(), $rules);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        Reply::create([
            'title'      => request()->reply,
            'message_id' => request()->message_id,
            'user_id'    => auth()->user()->id,
        ]);


        return redirect()->back();
    }

    ////////////////////////////////////////////////////////
    ///
    public function inbox(){
        $condition = " WHERE";
        $user_auth_role = \auth()->user()->roles()->first();
        $is_inbox = true;
        $post_type = 'course';
        $trash = GetTrash();
        $messages = [];

        $sql = "SELECT
        COUNT(replies.id) as replies_count,
        messages.id as msg_id ,
        messages.role_id as recipient ,
        users.name as username_send_msg,
        messages.title as msg_title ,
        messages.description as msg_description,
        messages.created_at as msg_date,
        courses.title as course_title
        FROM `messages`
        INNER JOIN courses ON messages.course_id = courses.id AND courses.branch_id = ".getCurrentUserBranchData()->branch_id." AND courses.deleted_at IS NULL
        INNER JOIN users   ON messages.user_id = users.id       AND users.deleted_at IS NULL
        LEFT JOIN  replies ON messages.id = replies.message_id";

        if($user_auth_role->role_type_id == 511){
            $condition = "AND";
            $sql .= " INNER JOIN courses_registration ON courses_registration.course_id = courses.id
            WHERE courses_registration.user_id=".auth()->id()."";
        }

        if($user_auth_role->role_type_id == 512) {
            $is_inbox = false;
            $condition = "AND";
            $sql .= " INNER JOIN courses_registration ON courses_registration.course_id = courses.id
                      INNER JOIN roles ON roles.id = courses_registration.role_id AND roles.deleted_at IS NULL
            WHERE courses_registration.user_id=".auth()->id()."
            and users.id=".auth()->id()."
            and roles.role_type_id=512";
        }

        // search
        if (request()->has('search')){
            $search = request()->search;
            $sql .= " ".$condition." messages.title  LIKE '%$search%'";
        }

        $type = "inbox";
        if (request()->has('type')){
            $type = request()->type;
            if ($type == "sent"){
                $sql .= " ".$condition." messages.user_id=".auth()->id();
            }elseif($type == "inbox"){
                $sql .= " ".$condition." messages.user_id !=".auth()->id();
            }
        }

        $sql .= " GROUP BY messages.id";

        $messages = DB::select(DB::raw($sql));
        return view('training.messages.index',compact('type', 'messages', 'is_inbox'));
    }







//
//    public function like(){
//
//        $operation = request()->operation??'liked_it';
//
//        $table_name = request()->table_name??'messages';
//        $likeable_type = Constant::where('slug', $table_name)->pluck('id')->first();
//        $operation_type = Constant::where('post_type', $operation)->pluck('id')->first();
//
//        $query = DB::table($table_name)
//            ->where('id', request()->likeable_id);
//
//        $like = Like::where('likeable_id', request()->likeable_id)
//            ->where('likeable_type', $likeable_type)
//            ->where('operation', $operation_type)
//            ->where('created_by', auth()->id());
//
//        $like_old = $like->first();
//        if(is_null($like_old)){
//
//            $like_old_from_null = Like::where('likeable_id', request()->likeable_id)
//                ->where('likeable_type', $likeable_type)
//                ->where('created_by', auth()->id())
//                ->first();
//
//            Like::updateOrCreate([
//                'likeable_id'=>request()->likeable_id,
//                'likeable_type'=>$likeable_type,
//                'created_by'=>auth()->id(),
//                'updated_by'=>auth()->id(),
//            ], [
//                'operation'=>$operation_type,
//                'is_like'=>1,
//            ]);
//
//            if(is_null($like_old_from_null)){
//                $query->increment($operation);
//            }
//            else{
//
//                if($like_old_from_null->is_like!=0){
//                    $query->decrement(($operation=='liked_it')?'loved_it':'liked_it');
//                }
//                $query->increment($operation);
//            }
//        }
//        else{
//
//            $is_like = 1;
//            if($like_old->is_like==0){
//                $query->increment($operation);
//            }
//            else{
//                $query->decrement($operation);
//                $is_like = 0;
//            }
//
//            $like->update([
//                'is_like'=>$is_like,
//            ]);
//        }
//
//        $like_new = $like->first();
//        return $like_new;
//    }
}
