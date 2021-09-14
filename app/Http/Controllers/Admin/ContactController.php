<?php

namespace App\Http\Controllers\Admin;

use App\Constant;
use App\Exports\ContactExport;
use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactRequest;

use App\Models\Admin\Contact;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{
    public function __construct()
    {
        Active::$folder = 'contacts';
    }

    public function index(){

        $post_type = 'learning';
        if(request()->has('group_slug') && !empty(request()->group_slug) && request()->group_slug=='consulting'){
            $post_type = 'consulting';
        }
        $trash = GetTrash();
        $contacts = Contact::whereNotNull('id')
        ->where('post_type', $post_type);
        $count = $contacts->count();
        $post_type='contact';
        $contacts = $contacts->page();
        return Active::Index(compact('contacts', 'post_type','count', 'trash'));
    }

    public function create(){
        $request_type=Constant::where('parent_id',45)->get();
        return Active::Create(compact('request_type'));
    }

    public function store(ContactRequest $request){

        $validated = $this->Validated($request->validated());
        $validated['created_by'] = auth()->user()->id;
        $contact = Contact::create($validated);
        return Active::Inserted($contact->trans_name);
    }

    public function edit(Contact $contact){
        $request_type=Constant::where('parent_id',45)->get();
        return Active::Edit(['eloquent'=>$contact, 'post_type'=>$contact->post_type,
            'request_type'=>$request_type]);
    }

    public function update(ContactRequest $request, Contact $contact){

        $validated = $this->validated($request->validated());
        $validated['updated_by'] = auth()->user()->id;
        Contact::find($contact->id)->update($validated);
        return Active::Updated($contact->trans_name);
    }

    public function destroy(Contact $contact){
        Contact::where('id', $contact->id)->SoftTrash();
        return Active::Deleted($contact->title);
    }

    public function restore($session){
        Contact::where('id', $session)->RestoreFromTrash();

        $session = Constant::where('id', $session)->first();
        return Active::Restored($session->title);
    }

    private function Validated($validated){
        $validated['updated_by'] = auth()->user()->id;
        return $validated;
    }

    public function export()
    {
        return Excel::download(new ContactExport, 'contact.xlsx');
    }
}
