<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\CourseRequest;
use App\Models\Admin\Partner;
use App\Models\Admin\Role;
use App\Models\Training\Course;
use App\Constant;
use App\Mail\MessageMail;
use App\Mail\ReplyMail;
use App\Models\Training\CourseRegistration;
use App\Models\Training\Group;
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

// use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'messages';
    }

    public function inbox(){

        $condition = " WHERE";
        $user_auth_role = \auth()->user()->roles()->first()->id ;
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
        INNER JOIN courses ON messages.course_id = courses.id
        INNER JOIN users ON messages.user_id = users.id
        LEFT JOIN replies ON messages.id = replies.message_id";

        if($user_auth_role == 2){
            $condition = "AND";
            $sql .= " INNER JOIN courses_registration ON courses_registration.course_id = courses.id
            WHERE courses_registration.user_id=".auth()->id()."
            and courses_registration.role_id=2";
        }

        if($user_auth_role == 3) {
            $is_inbox = false;
            $condition = "AND";
            $sql .= " INNER JOIN courses_registration ON courses_registration.course_id = courses.id
            WHERE courses_registration.user_id=".auth()->id()."
            and users.id=".auth()->id()."
            and courses_registration.role_id=3";
        }

            // search
        if (request()->has('search')){
            $search = request()->search;
            $sql .= " ".$condition." messages.title  LIKE '%$search%'";
        }

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
        return view('training.messages.index',compact('messages','is_inbox'));
    }


    public function addMessage(){
        $user_auth_role = \auth()->user()->roles()->first()->id ;
        if($user_auth_role == 1){
            $courses =  Course::with(['users'])->get();
        }else{
            $courses = CourseRegistration::where('user_id',auth()->user()->id)->with(['course.users'])->get();
        }
        $roles = Role::where('id','!=',3)->get();
        return view('training.messages.form',compact('courses','roles'));
    }

    public function sendMessage(){
        $rules = [
            "course_id"   => "required|exists:courses,id",
            "recipient_id"   => "required|exists:roles,id",
            "subject"   => "required|max:100",
            "description"   => "required",
        ];

        $user_auth_role = \auth()->user()->roles()->first()->id ;
        if($user_auth_role != 1){
            $courseRegistration = CourseRegistration::where('course_id',request()->course_id)->where('user_id',auth()->id())->first();
            if(!$courseRegistration){
                abort(404);
            }
        }

        $validator = Validator::make(\request()->all(), $rules);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

       $msg = Message::create([
            'user_id' => auth()->user()->id,
            'course_id' => request()->course_id,
            'role_id' => request()->recipient_id,
            'title' => request()->subject,
            'description' => request()->description,
        ]);

       $recipients = CourseRegistration::where('course_id', request()->course_id)->where('role_id',2)->get();

        foreach ($recipients as $recipient){
            RecipientMessage::create([
               'user_id' => $recipient->user_id,
               'role_id' => $recipient->role_id,
               'message_id' => $msg->id,
            ]);

            $user = User::where('id',$recipient->user_id)->first();
            Mail::to($user->email)->send(new MessageMail($msg->id , $user->id));
        }

        $admins = User::whereHas('roles',function ($q){
            $q->where('role_id',1);
        })->get();

        foreach ($admins as $admin){
            RecipientMessage::create([
                'user_id' => $admin->id,
                'role_id' => 1,
                'message_id' => $msg->id,
            ]);

            $user = User::where('id',$admin->id)->first();
            Mail::to($user->email)->send(new MessageMail($msg->id , $user->id));
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
       $message =  Message::where('id',request()->message_id)
            ->with(['course'])
            ->first();

       if(!$message || !$message->course){
           abort(404);
       }

        $rules = [
            "reply"   => "required",
        ];

        $validator = Validator::make(\request()->all(), $rules);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $reply = Reply::whereNotNull('id')->create([
            'title' => request()->reply,
            'message_id' => request()->message_id,
            'user_id' => auth()->user()->id,
        ]);

        $recipeient_replies = RecipientMessage::where('message_id',$reply->message_id)->get();
        $person_reply = RecipientMessage::where('user_id',$reply->user_id)->first();
        // dd($reply);
        // dd($person_reply);

            if($person_reply == null){
                foreach($recipeient_replies as $recipeient_reply){
                    $user = User::where('id',$recipeient_reply->user_id)->first();
                    Mail::to($user->email)->send(new ReplyMail($reply->id , $user->id));
                }
            }elseif($person_reply->role_id == 2){
                $message = Message::where('id',$reply->message_id)->with('user')->first();
                Mail::to($message->user->email)->send(new ReplyMail($reply->id , $message->user->id));
            }elseif($person_reply->role_id == 1){
                $message = Message::where('id',$reply->message_id)->with('user')->first();
                Mail::to($message->user->email)->send(new ReplyMail($reply->id , $message->user->id));
            }



        return redirect()->route('user.messages.inbox');
    }

}
