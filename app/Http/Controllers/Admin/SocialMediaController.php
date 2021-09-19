<?php

namespace App\Http\Controllers\Admin;

use App\Constant;
use App\Helpers\Active;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SocialMediaRequest;
use App\Models\Admin\SocialMedia;
use App\Models\Training\Social;

class SocialMediaController extends Controller
{

    public function __construct()
    {
        Active::$folder = 'social_media';
    }

    public function index()
    {
        $post_type = GetPostType('social_media');
        $trash = GetTrash();
        $social_media = SocialMedia::whereNotNull('id')->with('type');

        if(!is_null(request()->title_search)) {
            $social_media = $social_media->where(function($query){
                $query->where('title', 'like', '%'.request()->title_search.'%');
            });
        }

        $count = $social_media->count();
        $social_media = $social_media->page();
        return Active::Index(compact('social_media', 'count', 'post_type', 'trash'));
    }

    public function create()
    {
        $social_media_type =  Constant::where('post_type','social_media_type')->get();
        return Active::Create(compact('social_media_type'));
    }

    public function store(SocialMediaRequest $request)
    {
        $validated = $request->validated();
        $validated['title'] = null;

        $social_media = SocialMedia::create($validated);
        return Active::Inserted($social_media->trans_name, ['post_type'=>$social_media->post_type]);
    }

    // public function show($id)
    // {
    //     //
    // }

    public function edit(SocialMedia $social_media)
    {
        $social_media_type =  Constant::where('post_type','social_media_type')->get();
        return Active::Edit(['eloquent'=>$social_media,'social_media_type'=>$social_media_type]);
    }

    public function update(SocialMediaRequest $request, SocialMedia $social_media)
    {
        $validated = $request->validated();
        $validated['title'] = null;
        SocialMedia::find($social_media->id)->update($validated);
        SocialMedia::UploadFile($social_media, ['method'=>'update']);
        return Active::Updated($social_media->trans_name);
    }

    public function destroy(SocialMedia $social_media)
    {
        SocialMedia::where('id', $social_media->id)->SoftTrash();
        return Active::Deleted($social_media->trans_name);
    }

    public function restore($social_media){
        SocialMedia::where('id', $social_media)->RestoreFromTrash();
        $social_media = SocialMedia::where('id', $social_media)->first();
        return Active::Restored($social_media->trans_name);
    }

    private function Validated($validated){
        $validated['title'] = null;
        $validated['description'] = null;
        $validated['constant_id'] = null;
        return $validated;
    }
}
