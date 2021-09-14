<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConstantRequest;
use App\Http\Requests\Admin\MessageRequest;
use App\Models\Admin\Message;
use Illuminate\Http\Request;
use App\Helpers\Active;
use App\Constant;

class InterestingEmailController extends Controller
{
    public function __construct()
    {
        Active::$folder = 'interestings';
    }

    public function index(){

        $post_type = GetPostType('interestings');
        $trash = GetTrash();

        $messages = Message::with('user');
        $count = $messages->count();
        $messages = $messages->page();

        return Active::Index(compact('messages', 'count', 'post_type', 'trash'));
    }

    public function create(){
        return Active::Create(['object'=>Message::class,]);
    }


    public function store(MessageRequest $request){
        $validated = $request->validated();
        $validated['title'] = null;
        $validated['details'] = null;
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;

        $msg = Message::create($validated);
        return Active::Inserted($msg->trans_name, ['post_type'=>$request->post_type]);
    }

    public function edit(Message $interesting){
        return Active::Edit(['eloquent'=>$interesting, 'post_type'=>$interesting->post_type]);
    }

    public function update(MessageRequest $request, Message $interesting){

        $validated = $request->validated();
        $validated['title'] = null;
        $validated['details'] = null;
        $validated['updated_by'] = auth()->user()->id;
        Message::find($interesting->id)->update($validated);

        return Active::Updated($interesting->trans_title);
    }

    public function destroy(Message $interesting, Request $request){
        Message::where('id', $interesting->id)->SoftTrash();
        return Active::Deleted($interesting->trans_title);
    }

    public function restore($interesting){
        Message::where('id', $interesting)->RestoreFromTrash();

        $interesting = Message::where('id', $interesting)->first();
        return Active::Restored($interesting->trans_title);
    }
}
