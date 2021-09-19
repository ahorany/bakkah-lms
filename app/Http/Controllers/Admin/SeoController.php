<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Infastructure;

class SeoController extends Controller
{
    public function __construct()
    {
        Active::$folder = 'SEO';
    }

    public function edit($infa_id)
    {
        Active::Link($infa_id);
        $infa = Infastructure::find($infa_id);
        return Active::Edit(['eloquent'=>$infa,'object'=>Infastructure::class,]);
    }

    public function update(Infastructure $infastructure){
        \App\Models\SEO\Seo::seo($infastructure);
        Active::Flash($infastructure->trans_title, __('flash.inserted'), 'success');
        return back();
    }

}
