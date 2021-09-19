<?php

namespace App\Http\Controllers;

class QplusController extends Controller
{
    public function __invoke()
    {
        if(session()->has('org_id'))
		{
			session()->forget('org_id');
        }
        session()->put('org_id', 'qplus');
        return redirect()->route('education.training-schedule');
    }
}
