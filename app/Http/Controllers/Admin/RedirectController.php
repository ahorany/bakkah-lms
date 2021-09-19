<?php

namespace App\Http\Controllers\Admin;

use App\Constant;
use App\Helpers\Active;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RedirectRequest;
use App\Models\Admin\Redirect;

class RedirectController extends Controller
{

    public function __construct()
    {
        Active::$folder = 'redirects';
    }

    public function index()
    {
        $post_type = GetPostType('redirects');
        $trash = GetTrash();
        $redirects = Redirect::whereNotNull('id')->with('type');

        if(!is_null(request()->title_search)) {
            $redirects = $redirects->where(function($query){
                $query->where('source_url', 'like', '%'.request()->title_search.'%');
            });
        }

        $count = $redirects->count();
        $redirects = $redirects->page();
        return Active::Index(compact('redirects', 'count', 'post_type', 'trash'));
    }

    public function create()
    {
        $redirection_types = Constant::where('post_type','redirection-type')->get();
        return Active::Create(compact('redirection_types'));
    }

    public function store(RedirectRequest $request)
    {
        $validated = $request->validated();
        $redirect = Redirect::create($validated);
        return Active::Inserted($redirect->trans_name, ['post_type'=>$redirect->post_type]);
    }

    // public function show($id)
    // {

    // }

    public function edit(Redirect $redirect){
        $redirection_types =  Constant::where('post_type','redirection-type')->get();
        return Active::Edit(['eloquent'=>$redirect,'redirection_types'=>$redirection_types]);
    }

    public function update(RedirectRequest $request, Redirect $redirect){
        $validated = $request->validated();
        Redirect::find($redirect->id)->update($validated);
        Redirect::UploadFile($redirect, ['method'=>'update']);
        return Active::Updated($redirect->trans_name);
    }

    public function destroy(Redirect $redirect)
    {
        Redirect::where('id', $redirect->id)->SoftTrash();
        return Active::Deleted($redirect->trans_name);
    }

    public function restore($redirect){
        Redirect::where('id', $redirect)->RestoreFromTrash();
        $redirect = Redirect::where('id', $redirect)->first();
        return Active::Restored($redirect->trans_name);
    }

    private function Validated($validated){
        $validated['source_url'] = null;
        $validated['destination_url'] = null;
        $validated['constant_id'] = null;
        return $validated;
    }
}
