<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\BrancheRequest;
use App\Http\Requests\Training\CourseRequest;
use App\Models\Admin\Partner;
use App\Models\Training\Branche;
use App\Models\Training\Course;
use App\Constant;
use App\Models\Training\Criteria;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training.settings.index');

        Active::$namespace = 'training';
        Active::$folder = 'settings';
    }


    public function index(){
        $branche = Branche::where("id",session()->get('branche_id'))->with(['criteria'])->first();
        $criteria = Criteria::all();
        return view("training.settings.index",compact('branche','criteria'));
    }

    public function update(Request $request){
       return $request;
    }


}
