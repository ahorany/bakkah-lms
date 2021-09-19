<?php

namespace App\Http\Controllers\Admin;

use App\Constant;
use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DetailRequest;
use App\Models\Admin\Detail;
use App\Models\Admin\PostMorph;
use App\Models\Training\Course;
use App\Models\Training\TrainingOption;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    public function __construct()
    {
        Active::$folder = 'details';
    }

    public function index(){

        $trash = GetTrash();
        if(request()->has('master_id')) {
            $postMorph = PostMorph::find(request()->master_id);

            $details = Detail::where('master_id', request()->master_id);
            $count = $details->count();
            $details = $details->page();

            $title = $postMorph->postable->trans_title . ' ( ' . $postMorph->constant->trans_name . ' ) ';
            session()->put('infastructure__title', $title);
            session()->put('infastructure__icon', 'fas fa-chalkboard');

            return Active::Index(compact('details', 'count', 'trash'));
        }
    }

    public function create(){

        if(request()->constant_id==313 || request()->constant_id==314){
            $course = TrainingOption::find(request()->master_id);
        }
        else {
            $course = Course::find(request()->master_id);
        }
        $constant = Constant::find(request()->constant_id);

        $title = $course->trans_title . ' ( ' . $constant->trans_name . ' ) ';
        session()->put('infastructure__title', $title);
        session()->put('infastructure__icon', 'fas fa-chalkboard');

        $IsFound = $course->details()->where('constant_id', request()->constant_id)->count();
        if($IsFound!=0){
            return redirect()->route('admin.details.edit', ['detail'=>$course->details()->where('constant_id', request()->constant_id)->first()->id]);
        }
        return view('admin.'.Active::$folder.'.create');
//        return Active::Create(['object'=>Detail::class,]);
    }

    public function store(DetailRequest $request){

        $validated = $this->validated($request->validated());
        $validated['created_by'] = auth()->user()->id;
        $validated['constant_id'] = $request->constant_id;

        if(request()->constant_id==313 || request()->constant_id==314){
            $course = TrainingOption::find(request()->master_id);
        }else{
            $course = Course::find($request->master_id);
        }
        $detail = $course->details()->create($validated);

        Active::Flash('inserted', __('flash.inserted'), 'success');
        return redirect()->route('admin.details.edit', ['detail'=>$detail->id]);
//        return Active::Inserted($detail->id, [
//            'master_id'=>$validated['master_id'],
//        ]);
    }

    public function edit(Detail $detail){

        if($detail->detailable_type=='App\Models\Training\TrainingOption')
            $course = $detail->detailable->course;
        else
            $course = Course::find($detail->detailable_id);

        $constant = Constant::find($detail->constant_id);

        $title = $course->trans_title . ' ( ' . $constant->trans_name . ' ) ';
        session()->put('infastructure__title', $title);
        session()->put('infastructure__icon', 'fas fa-chalkboard');

        return Active::Edit(['eloquent'=>$detail]);
    }

    public function update(DetailRequest $request, Detail $detail){

        $validated = $this->Validated($request->validated());

        Detail::find($detail->id)->update($validated);

        Active::Flash('updated', __('flash.updated'), 'success');
        return back();
//        return Active::Updated($detail->id);
    }

    public function destroy(Detail $detail, Request $request){
        Detail::where('id', $detail->id)->SoftTrash();
        return Active::Deleted($detail->id);
    }

    public function restore($detail){
        Detail::where('id', $detail)->RestoreFromTrash();

        $detail = Detail::where('id', $detail)->first();
        return Active::Restored($detail->id);
    }

    private function Validated($validated){
        $validated['details'] = null;
        $validated['updated_by'] = auth()->user()->id;
        return $validated;
    }
}
