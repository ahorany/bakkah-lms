<?php

namespace App\Exports;

use App\Models\Admin\Contact;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ContactExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $contacts = contact::all();
        return view('admin.contacts.export', compact('contacts'));
    }
}
