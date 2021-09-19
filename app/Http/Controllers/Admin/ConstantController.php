<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConstantRequest;
use Illuminate\Http\Request;
use App\Helpers\Active;
use App\Constant;

class ConstantController extends Controller
{
    public function __construct()
    {
        Active::$folder = 'constants';
    }

    public function index(){

		$post_type = GetPostType('constants');
		$trash = GetTrash();

		$constants = Constant::with('user')->where('post_type', $post_type);
		$count = $constants->count();
		$constants = $constants->page();
	    return Active::Index(compact('constants', 'count', 'post_type', 'trash'));
    }

    public function create(){
        return Active::Create(['object'=>Constant::class,]);
    }

    // protected function Track($object, $infastructure_id){
    // 	$object->tracks()->create([
    // 	    'infastructure_id'=>$infastructure_id,
    // 	    'created_by'=>auth()->user()->id,
    // 	]);
    // }

    public function store(ConstantRequest $request){

    	$validated = $request->validated();
    	$validated['name'] = null;
    	$validated['excerpt'] = null;
    	$validated['created_by'] = auth()->user()->id;
    	$validated['updated_by'] = auth()->user()->id;

    	$constant = Constant::create($validated);
        \App\Models\SEO\Seo::seo($constant);
    	// Track($constant, 59);
        return Active::Inserted($constant->trans_name, ['post_type'=>$request->post_type]);
    }

    public function edit(Constant $constant){
        return Active::Edit(['eloquent'=>$constant, 'post_type'=>$constant->post_type]);
    }

    public function update(ConstantRequest $request, Constant $constant){

        $validated = $request->validated();
        $validated['name'] = null;
        $validated['excerpt'] = null;
    	$validated['updated_by'] = auth()->user()->id;

        Constant::find($constant->id)->update($validated);

        \App\Models\SEO\Seo::seo($constant);
        return Active::Updated($constant->title);
    }

    public function destroy(Constant $constant, Request $request){
        Constant::where('id', $constant->id)->SoftTrash();
        return Active::Deleted($constant->title);
    }

    public function restore($constant){
        Constant::where('id', $constant)->RestoreFromTrash();

        $constant = Constant::where('id', $constant)->first();
        return Active::Restored($constant->title);
    }
}
