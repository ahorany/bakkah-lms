<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\CategoryRequest;
use App\Models\Training\Category;
use DB;


class CategoryController extends Controller
{
    public function __construct()
    {
         $this->middleware('permission:training.categories.index');

        Active::$namespace = 'training';
        Active::$folder = 'categories';
    }

    public function index(){

        $post_type = 'category';
        $trash = GetTrash();

        $categories = Category::where('branch_id',getCurrentUserBranchData()->branch_id);

        if (!is_null(request()->category_search)) {
            $categories = $this->SearchCond($categories);
        }


        $count = $categories->count();
        $categories = $categories->page();
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
        $validated['updated_by'] = auth()->user()->id;
        $validated['branch_id'] = getCurrentUserBranchData()->branch_id;
        $categoy = Category::create($validated);
        return Active::Inserted($categoy->trans_title);
    }

    public function edit(Category $category){
        if (getCurrentUserBranchData()->branch_id != $category->branch_id){
            abort(404);
        }
        return Active::Edit(['eloquent'=>$category]);
    }

    public function update(CategoryRequest $request, Category $category){
        if (getCurrentUserBranchData()->branch_id != $category->branch_id){
            abort(404);
        }
        $validated = $this->Validated($request->validated());
        $validated['updated_by'] = auth()->user()->id;
        $category->update($validated);
        return Active::Updated($category->trans_title);
    }


    public function destroy(Category $category){
        Category::where('id', $category->id)->where('branch_id',getCurrentUserBranchData()->branch_id)->SoftTrash();
        return Active::Deleted($category->trans_title);
    }

    public function restore($category){
        Category::where('id', $category)->where('branch_id',getCurrentUserBranchData()->branch_id)->RestoreFromTrash();
        $category = Category::where('id', $category)->where('branch_id',getCurrentUserBranchData()->branch_id)->first();
        return Active::Restored($category->trans_title);
    }

    private function Validated($validated){
        $validated['title'] = null;
        return $validated;
    }

}
