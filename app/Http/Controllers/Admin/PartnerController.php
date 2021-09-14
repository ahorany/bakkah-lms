<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PartnerRequest;
use App\Models\Admin\Partner;
use Illuminate\Http\Request;
use App\Constant;

class PartnerController extends Controller
{
    public function __construct()
    {
        Active::$folder = 'partners';
    }

    public function index(){

        $post_type = GetPostType('partner');
        $trash = GetTrash();
        $partners = Partner::with(['upload', 'user'])
            ->with('user')
            ->where('post_type', $post_type);

        if(!is_null(request()->title_search)) {
            $partners = $partners->where(function($query){
                $query->where('name', 'like', '%'.request()->title_search.'%');
            });
        }

        if(request()->in_education) {
            $partners = $partners->where('partners.in_education', (request()->in_education)?1:0);
        }
        if(request()->in_consulting) {
            $partners = $partners->where('partners.in_consulting', (request()->in_consulting)?1:0);
        }
        if(request()->show_in_home) {
            $partners = $partners->where('partners.show_in_home', (request()->show_in_home)?1:0);
        }

        $count = $partners->count();
        $partners = $partners->page();
        return Active::Index(compact('partners', 'count', 'post_type', 'trash'));
    }

    public function create(){
        return Active::Create();
    }

    public function store(PartnerRequest $request){

        $validated = $this->Validated($request->validated());
        $validated['created_by'] = auth()->user()->id;
        $partner = Partner::create($validated);
        \App\Models\SEO\Seo::seo($partner);

        return Active::Inserted($partner->trans_name, ['post_type'=>$partner->post_type]);
    }

    public function edit(Partner $partner){
        return Active::Edit(['eloquent'=>$partner]);
    }

    public function update(PartnerRequest $request, Partner $partner){

        $validated = $this->Validated($request->validated());

        Partner::find($partner->id)->update($validated);
        Partner::UploadFile($partner, ['method'=>'update']);
        \App\Models\SEO\Seo::seo($partner);

        return Active::Updated($partner->trans_name);
    }

    public function destroy(Partner $partner){
        Partner::where('id', $partner->id)->SoftTrash();
        return Active::Deleted($partner->trans_name);
    }

    public function restore($partner){
        Partner::where('id', $partner)->RestoreFromTrash();
        $partner = Partner::where('id', $partner)->first();
        return Active::Restored($partner->trans_name);
    }

    private function Validated($validated){
        $validated['name'] = null;
        $validated['excerpt'] = null;
        $validated['details'] = null;
        $validated['updated_by'] = auth()->user()->id;
        $validated['show_in_home'] = request()->has('show_in_home')?1:0;
        $validated['in_education'] = request()->has('in_education')?1:0;
        $validated['in_consulting'] = request()->has('in_consulting')?1:0;
        return $validated;
    }
}
