<?php

namespace App\Http\Controllers\Xero;

use App\Http\Controllers\Controller;

class XeroController extends Controller
{
    public function authorization(){
        require_once('authorization.php');
    }

    public function callback(){
        require_once('callback.php');
    }

    public function authorizedResource(){
        require_once('authorizedResource.php');
    }
}
