<?php

namespace Modules\CRM\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Helpers\Active;
use App\User;
use Modules\CRM\Entities\Organization;
use Illuminate\Contracts\Support\Renderable;
use Modules\CRM\Http\Requests\OrganizationRequest;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Active::$folder = 'crm::organizations';
        Active::$namespace = null;
    }

    public function index()
    {
        $trash = GetTrash();
        $organizations = Organization::whereNotNull('id');
        $count = $organizations->count();
        $post_type='organizations';
        $organizations = $organizations->page();
        return Active::Index(compact('organizations', 'post_type', 'count', 'trash'));
    }

    public function create()
    {
        return Active::Create(['post_type'=>null]);
    }

    public function store(OrganizationRequest $request)
    {
        $validated = $request->validated();
        // return $request;
        $validated['title'] = null;
        $validated['name'] = null;
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;

        $data_name = json_encode([
            'en'=>$validated['en_name']??null,
            'ar'=>$validated['ar_name']??null
        ], JSON_UNESCAPED_UNICODE);

        $args = [
            'name'=>$data_name,
            'job_title'=>$validated['job_title']??null,
            'company'=>$validated['en_title']??null,
            'mobile'=>$validated['mobile']??null,
            'user_type'=>41,
            'locale'=>app()->getLocale(),
        ];
        $user = User::updateOrCreate([
            'email'=>$validated['email'],
        ], array_merge($args));
        $user_id = $user->id??0;

        $organization = Organization::create($validated);
        return Active::Inserted($organization->trans_name, ['post_type'=>null]);
    }

    public function show($id)
    {
        return view('crm::show');
    }

    public function edit(Organization $organization){
        return Active::Edit(['eloquent'=>$organization]);
    }

    public function update(OrganizationRequest $request, Organization $organization)
    {

        $validated = $request->validated();
        // return $validated;
        $validated['title'] = null;
        $validated['name'] = null;

        $organization->update($validated);

        Organization::UploadFile($organization, [
            'post_type'=>'image'
            , 'locale'=>'en'
            , 'upload_title'=>'upload_title'
            , 'upload_excerpt'=>'upload_title'
            , 'method'=>'update'
        ], $name='file');

        $data_name = json_encode([
            'en'=>$validated['en_name']??null,
            'ar'=>$validated['ar_name']??null
        ], JSON_UNESCAPED_UNICODE);

        $args = [
            'name'=>$data_name,
            'job_title'=>$validated['job_title']??null,
            'company'=>$validated['en_title']??null,
            'mobile'=>$validated['mobile']??null,
            'user_type'=>41,
            'locale'=>app()->getLocale(),
        ];

        // User::where('email',$validated['email'])->update(array_merge($args));
        $user = User::updateOrCreate([
            'email'=>$validated['email'],
        ], array_merge($args));
        $user_id = $user->id??0;

        return Active::Updated($organization->trans_name);
    }

    public function destroy(Organization $organization)
    {
        $organization->SoftTrash($organization);
        return Active::Deleted($organization->trans_name);
    }

    public function restore($organization){
        Organization::where('id', $organization)->RestoreFromTrash();
        $organization = Organization::where('id', $organization)->first();
        return Active::Restored($organization->trans_name);
    }
}
