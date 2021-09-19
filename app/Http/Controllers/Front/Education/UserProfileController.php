<?php

namespace App\Http\Controllers\Front\Education;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{

    public function __construct(){
        $this->path = FRONT.'.education.users';
    }

    public function dashboard() {
        return view($this->path.'.index');
    }

    public function home() {
        return view($this->path.'.home');
    }

    public function info() {
        return view($this->path.'.info');
    }

    // public function notification() {
    //     return view($this->path.'.notification');
    // }

    public function my_courses() {
        return view($this->path.'.my_courses');
    }

    public function certifications() {
        return view($this->path.'.certifications');
    }

    public function payment_info() {
        return view($this->path.'.payment_info');
    }

    public function referral() {
        return view($this->path.'.referral');
    }

    public function invoice() {
        return view($this->path.'.invoice');
    }

    public function request_ticket() {
        return view($this->path.'.request_ticket');
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }

    public function cart() {
        return view($this->path.'.cart');
    }

}
