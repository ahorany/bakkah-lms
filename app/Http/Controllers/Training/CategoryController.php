<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\CategoryRequest;
use App\Models\Admin\Partner;
use App\Models\Training\Course;
use App\Models\Training\Category;
use App\Constant;
use App\Models\Training\Certificate;
use App\Models\Training\Group;
use App\Models\Training\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use DB;

// use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
         $this->middleware('permission:training.categories.index');
        // $this->middleware('permission:course.create', ['only' => ['create','store']]);
        // $this->middleware('permission:course.edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:course.delete', ['only' => ['destroy']]);
        // $this->middleware('permission:course.restore', ['only' => ['restore']]);


        Active::$namespace = 'training';
        Active::$folder = 'categories';
    }

    public function index(){

        $post_type = 'category';
        $trash = GetTrash();
        if($trash){
            if(checkUserIsTrainee()){
                abort(404);
            }
        }

        $categories = Category::whereNotNull('id');

        if (!is_null(request()->category_search)) {
            $categories = $this->SearchCond($categories);
        }


        // $categories = Constant::where('post_type', 'course')->get();

        $count = $categories->count();
        $categories = $categories->page();
        // dd($completed_learners);
        return Active::Index(compact('categories', 'count', 'post_type', 'trash'));
    }


    private function SearchCond($eloquent){

        $eloquent1 = $eloquent->where(function ($query) {
            $query->where('title', 'like', '%' . request()->category_search . '%');
        });
        return $eloquent1;
    }

    public function create(){

        return Active::Create();
    }

    public function store(CategoryRequest $request){

        $validated = $this->Validated($request->validated());
        $validated['created_by'] = auth()->user()->id;

        $categoy = Category::create($validated);

        return Active::Inserted($categoy->trans_title);
    }

    public function edit(Category $category){
        return Active::Edit(['eloquent'=>$category]);
    }

    public function update(CategoryRequest $request, Category $category){

        $validated = $this->Validated($request->validated());
        Category::find($category->id)->update($validated);

        return Active::Updated($category->trans_title);
    }


    public function destroy(Category $category){

        Category::where('id', $category->id)->SoftTrash();
        return Active::Deleted($category->trans_title);
    }

    public function restore($category){

        Category::where('id', $category)->RestoreFromTrash();
        $category = Category::where('id', $category)->first();
        return Active::Restored($category->trans_title);
    }

    private function Validated($validated){
        $validated['title'] = null;
        return $validated;
    }

}
