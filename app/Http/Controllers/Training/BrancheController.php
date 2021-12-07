<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\BrancheRequest;
use App\Http\Requests\Training\CourseRequest;
use App\Models\Admin\Partner;
use App\Models\Training\Branche;
use App\Models\Training\Course;
use App\Constant;
use Illuminate\Database\Eloquent\Builder;

class BrancheController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'branches';
    }

    public function index(){
        $post_type = 'branches';
        $trash = GetTrash();
        $branches = Branche::query();
        $count = $branches->count();
        $branches = $branches->page();

        return Active::Index(compact('branches', 'count', 'post_type', 'trash'));
    }

    public function create(){
        return Active::Create();
    }

    public function store(BrancheRequest $request){

        $validated = $request->validated();
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;
        $validated['active'] = request()->has('active')?1:0;

        $branche= Branche::create($validated);
        \App\Models\SEO\Seo::seo($branche);
        return Active::Inserted($branche->name);
    }

    public function edit(Branche $branche){
        return Active::Edit(['eloquent'=>$branche]);
    }

    public function update(BrancheRequest $request,Branche $branche){
        $validated = $request->validated();
        $validated['updated_by'] = auth()->user()->id;
        $validated['active'] = request()->has('active')?1:0;
        Branche::find($branche->id)->update($validated);
        Branche::UploadFile($branche, ['method'=>'update']);
        \App\Models\SEO\Seo::seo($branche);
        return Active::Updated($branche->name);
    }

    public function destroy(Branche $branche){
        Branche::where('id', $branche->id)->SoftTrash();
        return Active::Deleted($branche->name);
    }

    public function restore($branche){
        Branche::where('id', $branche)->RestoreFromTrash();
        $branche = Branche::where('id', $branche)->first();
        return Active::Restored($branche->name);
    }

}
