<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Mail\LearnerComplaintEmail;
use App\ProfileQuestionUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LearnComplaintsController extends Controller
{

    public function __construct()
    {
        Active::$folder = 'learn_complaints';
    }

    public function index()
    {
        $post_type = GetPostType();
        $trash = GetTrash();
        $complaints = ProfileQuestionUser::where('post_type',$post_type)->with('products');
        $count = $complaints->count();
        $complaints = $complaints->page();
        return Active::Index(compact('complaints', 'count', 'post_type', 'trash'));
    }

    public function get_complaints($id){
        $my_status = ProfileQuestionUser::findOrFail($id);
        if($my_status->status == 0){
            $my_status->update([
                'status' => 1,
            ]);
            $user = User::where('id',$my_status->user_id)->first();
            Mail::to($user->email)->send(new LearnerComplaintEmail);
        }
        return redirect()->back();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
