<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Answer;
use App\Models\Training\Content;
use App\Models\Training\ContentDetails;
use App\Models\Training\Course;
use App\Models\Training\Exam;
use App\Models\Training\Question;
use App\Models\Training\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:course.units.list');
    }

    public function index()
    {
        $course_id = request()->course_id;
        $course = Course::select('id','title')->where('id',$course_id)->with(['units' => function($q) use ($course_id){
           return $q->where('course_id',$course_id)->with('subunits');
        }])->first();

        if (!$course) abort(404);

        $units = $this->buildTree($course->units);

        return view('training.courses.units.index',compact('course','units'));
    }

    private function buildTree($elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                $children = $this->buildTree($elements, $element->id);
                if ($children) {
                    $element->s = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }


    public function delete_unit(){
        Unit::whereId(request()->id)->delete();
        return response()->json(['status' => true]);
    }



    private function validationUnit($type = 'add'){
        // validation
        $rules = [
            'title' => 'required',
            'course_id'  =>'required|exists:courses,id',
            'unit_no'   => ['required',
                Rule::unique('units')
                    ->where('course_id',request()->course_id)
            ]
        ];

        if ($type == 'update'){
            $rules['unit_id']  = 'required|exists:units,id';

            $rules['unit_no'] = ['required',
                 Rule::unique('units')
                     ->ignore(\request()->unit_id, 'id')
                     ->where('course_id',request()->course_id)];
        }

        $validator = Validator::make(\request()->all(), $rules);

        foreach ( request()->subunits as $index => $subunit ) {
            if (is_null($subunit['title'])) {
                $validator->getMessageBag()->add('subunits', 'All subunits field required');
                break;
            }
            if (is_null($subunit['unit_no'])) {
                $validator->getMessageBag()->add('subunits', 'All subunits unit no field required');
                break;
            }else{
                foreach ( request()->subunits as $key => $s ) {
                    if ($index != $key && $subunit['unit_no'] == $s['unit_no']){
                        $validator->getMessageBag()->add('subunits', 'All subunits unit no field must be unique');
                        break;
                    }
                }
            }

        }



        if ($type == 'update'){
            foreach ( request()->subunits as $subunit ) {
                if ($subunit['unit_no'] == \request()->unit_no){
                    $validator->getMessageBag()->add('unit_no', 'All unit no  field must be unique');
                    $validator->getMessageBag()->add('subunits', 'All subunits unit no field must be unique');
                    break;
                }
                if (!is_null($subunit['unit_no'])) {
                    $unit = Unit::select('id')
                        ->where('course_id',\request()->course_id)
                        ->where('unit_no',$subunit['unit_no'])
                        ->where('id','!=',$subunit['id'])
                        ->first();
                    if ($unit){
                        $validator->getMessageBag()->add('subunits', 'All subunits unit no field must be unique');
                        break;
                    }
                }
            }

        }else{
            foreach ( request()->subunits as $subunit ) {
                if ($subunit['unit_no'] == \request()->unit_no){
                    $validator->getMessageBag()->add('unit_no', 'All unit no  field must be unique');
                    $validator->getMessageBag()->add('subunits', 'All subunits unit no field must be unique');
                    break;
                }
                if (!is_null($subunit['unit_no'])) {
                    $unit = Unit::select('id')
                        ->where('course_id',\request()->course_id)
                        ->where('unit_no',$subunit['unit_no'])
                        ->first();
                    if ($unit){
                        $validator->getMessageBag()->add('subunits', 'All subunits unit no field must be unique');
                        break;
                    }
                }
            }
        }

        return $validator;
    }


    public function add_unit(){

        $validator = $this->validationUnit();

        if(count($validator->errors()) > 0 ){
            return response()->json(['errors' => $validator->errors()]);
        }


        $unit = Unit::create([
               'title' => request()->title,
               'course_id' => request()->course_id,
               'unit_no' => request()->unit_no,
        ]);

        foreach ( request()->subunits as $subunit ){
            if (!is_null($subunit['title'])){
                Unit::create([
                    'title' => $subunit['title'],
                    'course_id' => request()->course_id,
                    'parent_id' => $unit->id,
                    'unit_no' => $subunit['unit_no'],
                ]);
            }
        }
            $unit = Unit::where('id',$unit->id)->with(['subunits'])->first();
            $unit->s = $unit->subunits;

            return response()->json(['status' => 'success','data' => $unit]);
    }

    public function update_unit(){
        $validator = $this->validationUnit('update');

        if(count($validator->errors()) > 0 ){
            return response()->json(['errors' => $validator->errors()]);
        }

        $unit = Unit::whereId(request()->unit_id)->update([
            'title' => request()->title,
            'course_id' => request()->course_id,
            'unit_no' =>\request()->unit_no,
        ]);

        if ($unit){
            foreach ( request()->subunits as $subunit ){
                if (!is_null($subunit['title'])){
                    Unit::updateOrCreate([
                        'id' => $subunit['id']
                    ],[
                        'title' => $subunit['title'],
                        'unit_no' => $subunit['unit_no'],
                        'course_id' => request()->course_id,
                        'parent_id' => request()->unit_id,
                    ]);
                }
            }
        }

        $unit = Unit::where('id',request()->unit_id)->with(['subunits'])->first();
        if(count($unit->subunits) > 0)
          $unit->s = $unit->subunits;

        return response()->json(['status' => 'success','data' => $unit]);

    }




}
