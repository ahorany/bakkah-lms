<?php

namespace App\Http\Controllers\Admin;

use App\Constant;
use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Admin\CareerRequest;
use App\Models\Admin\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function __construct()
    {
        Active::$folder = 'careers';
    }

    public function index(){

        $trash = GetTrash();
        $careers = Career::whereNotNull('id');
        $count = $careers->count();
        $post_type='careers';
        $careers = $careers->page();
        return Active::Index(compact('careers', 'post_type', 'count', 'trash'));
    }

    public function create(){
        $career_types = Constant::where('parent_id', 318)->get();
        $countries = Constant::where('post_type', 'countries')->get();
        return Active::Create(compact('career_types', 'countries'));
    }

    public function store(CareerRequest $request){

        // $validated = $this->Validated($request->validated());
        // $validated['created_by'] = auth()->user()->id;
        // $contact = Contact::create($validated);
        // return Active::Inserted($contact->trans_name);
    }

    // public function edit(Contact $contact){
    //     $request_type=Constant::where('parent_id',45)->get();
    //     return Active::Edit(['eloquent'=>$contact, 'post_type'=>$contact->post_type,
    //         'request_type'=>$request_type]);
    // }

    // public function update(ContactRequest $request, Contact $contact){

    //     $validated = $this->validated($request->validated());
    //     $validated['updated_by'] = auth()->user()->id;
    //     Contact::find($contact->id)->update($validated);
    //     return Active::Updated($contact->trans_name);
    // }

    // public function destroy(Contact $contact){
    //     Contact::where('id', $contact->id)->SoftTrash();
    //     return Active::Deleted($contact->title);
    // }

    // public function restore($session){
    //     Contact::where('id', $session)->RestoreFromTrash();

    //     $session = Constant::where('id', $session)->first();
    //     return Active::Restored($session->title);
    // }

    // private function Validated($validated){
    //     $validated['updated_by'] = auth()->user()->id;
    //     return $validated;
    // }
}
