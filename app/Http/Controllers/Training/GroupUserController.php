<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Group;
use App\Models\Training\GroupUser;
use App\User;


class GroupUserController extends Controller
{


    public function group_users()
    {
        $group_id = request()->group_id;
        $group = Group::with(['upload', 'users'])->where('id',$group_id)->first();
        return view('training.groups.users.index', compact('group'));
    }

    public function delete_user_group(){
        $user_id = \request()->user_id;
        $group_id = \request()->group_id;
        $user =  User::findOrFail($user_id);
        $group =  Group::findOrFail($group_id);
        GroupUser::where('user_id',$user->id)->where('group_id',$group->id)->delete();
        return response()->json(['status' => 'success']);
    }

    public function search_user_group(){
       $users = User::query();

       $lock = true;
       if(is_null(request()->email) && is_null(request()->name)){
           $users = [];
           $lock = false;
       }
        if(!is_null(request()->email)) {
            $users = $users->where(function($query){
                $query->where('email', 'like', '%'.request()->email.'%');
            });
        }

        if(!is_null(request()->name)) {
            $users = $users->where(function($query){
                $query->where('name', 'like', '%'.request()->name.'%');
            });
        }


        if($lock){
            $users = $users->get();
        }
        return response()->json([ 'status' => 'success' ,'users' => $users]);
    }

    public function add_users_group(){
        $group = Group::find(\request()->group_id);

        if(!$group){
            return response()->json([ 'status' => 'fail']);
        }

        foreach (\request()->users as $key =>  $value){
            if ($value == true){
                GroupUser::updateOrcreate([
                    'user_id' => $key,
                    'group_id' => $group->id
                ],
                [
                    'user_id' => $key,
                    'group_id' => $group->id,
                ]);
            }else if ($value == false){
                GroupUser::where('user_id',$key)->where('group_id',$group->id)->delete();
            }
        }
        $group = Group::with(['users'])->where('id',$group->id)->first();

        return response()->json([ 'status' => 'success' ,'group' => $group]);


    }
}
