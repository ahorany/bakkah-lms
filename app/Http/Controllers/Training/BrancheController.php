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
        $this->middleware('permission:training.branches.index');

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

    public function edit(Branche $branch){
        return Active::Edit(['eloquent'=>$branch]);
    }

    public function update(BrancheRequest $request,Branche $branch){
        $validated = $request->validated();
        $validated['updated_by'] = auth()->user()->id;
        $validated['active'] = request()->has('active')?1:0;
        Branche::find($branch->id)->update($validated);
        Branche::UploadFile($branch, ['method'=>'update']);
        \App\Models\SEO\Seo::seo($branch);
        return Active::Updated($branch->name);
    }

    public function destroy(Branche $branch){
        Branche::where('id', $branch->id)->SoftTrash();
        return Active::Deleted($branch->name);
    }

    public function restore($branch){
        Branche::where('id', $branch)->RestoreFromTrash();
        $branch = Branche::where('id', $branch)->first();
        return Active::Restored($branch->name);
    }

}
