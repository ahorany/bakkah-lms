<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccordionRequest;
use App\Models\Admin\Accordion;
use App\Models\Admin\PostMorph;
use Illuminate\Http\Request;

class AccordionController extends Controller
{
    public function __construct()
    {
        Active::$folder = 'accordions';
    }

    public function index(){

        $trash = GetTrash();
        if(request()->has('master_id')) {
            $postMorph = PostMorph::find(request()->master_id);

            $accordions = Accordion::with('user')->where('master_id', request()->master_id);
            $count = $accordions->count();
            $accordions = $accordions->page();

            $title = $postMorph->postable->trans_title . ' ( ' . $postMorph->constant->trans_name . ' ) ';
            session()->put('infastructure__title', $title);
            session()->put('infastructure__icon', 'fas fa-chalkboard');
            return Active::Index(compact('accordions', 'count', 'trash'));
        }
    }

    public function create(){
        return Active::Create(['object'=>Accordion::class,]);
    }

    public function store(AccordionRequest $request){

        $validated = $this->validated($request->validated());
        $validated['created_by'] = auth()->user()->id;
        $validated['master_id'] = $request->master_id;

        $accordion = Accordion::create($validated);

        return Active::Inserted($accordion->trans_title, [
            'master_id'=>$validated['master_id'],
        ]);
    }

    public function edit(Accordion $accordion){
        return Active::Edit(['eloquent'=>$accordion]);
    }

    public function update(AccordionRequest $request, Accordion $accordion){

        $validated = $this->Validated($request->validated());

        Accordion::find($accordion->id)->update($validated);

        return Active::Updated($accordion->trans_title);
    }

    public function destroy(Accordion $accordion, Request $request){
        Accordion::where('id', $accordion->id)->SoftTrash();
        return Active::Deleted($accordion->trans_title);
    }

    public function restore($accordion){
        Accordion::where('id', $accordion)->RestoreFromTrash();

        $accordion = Accordion::where('id', $accordion)->first();
        return Active::Restored($accordion->trans_title);
    }

    private function Validated($validated){
        $validated['title'] = null;
        $validated['details'] = null;
        $validated['updated_by'] = auth()->user()->id;
        return $validated;
    }
}
