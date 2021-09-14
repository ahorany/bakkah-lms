<?php

namespace App\Http\Controllers\Training;

use App\Constant;
use App\Helpers\Active;
use App\Helpers\Lang;
use App\Http\Controllers\Controller;
use App\Http\Requests\Training\DiscountRequest;
use App\Models\Training\Course;
use App\Models\Training\Discount\Discount;
use App\Models\Training\Discount\DiscountDetail;
use App\Models\Training\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Random;
use App\Models\Training\TrainingOption;

class DiscountController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'discounts';
    }

    public function index(){

        $all_courses = Course::orderBy('order')->get();
        $delivery_methods = Constant::where('parent_id', 10)->get();

        $trash = GetTrash();
        $post_type = GetPostType();
        $discounts = Discount::where('post_type', $post_type)
        ->with('discountDetails.courseList')
        ->whereNotNull('id');

        if(request()->has('course_id') && request()->course_id != -1){
            $discounts = $discounts->whereHas('discountDetails', function(Builder $query){
                $query->where('course_id', request()->course_id);
            });
        }

        if(request()->has('training_option_id') && request()->training_option_id != -1){
            $discounts = $discounts->whereHas('discountDetails', function(Builder $query){
                $query->where('training_option_type_id', request()->training_option_id);
            });
        }

        // if(request()->has('training_option_id') && request()->training_option_id != -1){
        //     $discounts = $discounts->where('training_option_id', request()->training_option_id);
        // }

        if(!is_null(request()->discount_search)) {
            $discounts = $discounts->where(function($query){
                $query->where('excerpt', 'like', '%'.request()->discount_search.'%')
                      ->orWhere('code', 'like', '%'.request()->discount_search.'%');
            });
        }

        if(request()->is_private || request()->is_public) {
            $discounts = $discounts->where(function($query){
                $query->where('is_private', (request()->is_private)?1:0)
                      ->orWhere('is_private', (request()->is_public)?0:1);
            });

            // $discounts = $discounts->where('is_private', (request()->is_private)?1:0);
        }
        // if(request()->is_public) {
        //     $discounts = $discounts->orWhere('is_private', (request()->is_public)?0:1);
        // }

        $count = $discounts->count();
        $discounts = $discounts->page();
        return Active::Index(compact('discounts', 'all_courses', 'delivery_methods', 'post_type','count', 'trash'));
    }

    public function replicate($id){

        $discount = Discount::find($id);

        $copies_number = $discount->copies_number + 1;
        $copy = ' (Copy-'.$copies_number.')';

        request()->en_excerpt = $discount->en_excerpt . $copy;
        request()->ar_excerpt = $discount->ar_excerpt . $copy;

        $new_discount = Discount::create([
            'order'=>$discount->order,
            'excerpt'=>$discount->excerpt,
            'code'=>$discount->code . $copy,
            'type_id'=>$discount->type_id,
            'value'=>$discount->value,
            'start_date'=>$discount->start_date,
            'end_date'=>$discount->end_date,
            'created_by'=>$discount->created_by,
            'updated_by'=>$discount->updated_by ,
            'training_option_id'=>$discount->training_option_id,
            'country_id'=>$discount->country_id,
            'is_private'=>$discount->is_private,
            'coin_id'=>$discount->coin_id,
            'candidates_no'=>$discount->candidates_no,
            'post_type'=>$discount->post_type,
            'is_duplicated'=>$discount->is_duplicated,
            'copies_number'=>$discount->copies_number,
        ]);

        $discount_details = DiscountDetail::where('master_id', $id)->get();
        foreach($discount_details as $discount_detail){
            DiscountDetail::create([
                'master_id'=>$new_discount->id,
                'course_id'=>$discount_detail->course_id,
                'training_option_type_id'=>$discount_detail->training_option_type_id,
                'training_option_id'=>$discount_detail->training_option_id,
                'session_id'=>$discount_detail->session_id,
                'value'=>$discount_detail->value??null ,
                'date_from'=>$discount_detail->date_from,
                'date_to'=>$discount_detail->date_to,
                'created_by'=>$discount_detail->created_by,
                'updated_by'=>$discount_detail->updated_by,
            ]);
        }

        Discount::where('id', $id)->update([
            'copies_number'=>$copies_number,
        ]);
        // $new_discount = $discount->replicate();
        // return $new_discount;
        return Active::Inserted($new_discount->trans_excerpt, ['post_type' => 'discount']);
    }

    private function __create_edit_param($code=null){

        $type = Constant::where('parent_id',55)->get();
        $optionConstants = Constant::where('parent_id', 10)->get();
        $currencyConstants = Constant::whereIn('id', [334, 335])->get();
        $countries = Constant::countries();

        return [
            'type'=>$type,
            'optionConstants'=>$optionConstants,
            'countries' => $countries,
            'code' => $code,
            'currencyConstants' => $currencyConstants,
        ];
    }

    public function create(){

        $Random = new Random;
        $params = $this->__create_edit_param($Random->Generate());

        return Active::Create($params);
    }

    public function store(DiscountRequest $request){

        $validated = $this->Validated($request->validated());

        $count = Discount::where('code', $request->code)->count();
        if($count==0)
        {
            $validated['created_by'] = auth()->user()->id;
            $discount = Discount::create($validated);

            $this->StoreDiscountCountries($discount);

            return Active::Inserted($discount->trans_excerpt, ['post_type' => 'discount']);
        }
        Active::Flash('promocode', 'promo code in pre found', 'danger');
        return back();
    }

    public function edit(Discount $discount){

        $final_array=array();
        $discount_courses = DB::table('discount_details')
            ->select('course_id')
            ->where('master_id', '=', $discount->id)
            ->get()->toArray();

        $array = json_decode(json_encode($discount_courses), true);
        foreach ($array as $key=>$value){
            array_push($final_array,$value['course_id']);
        }

        $params = $this->__create_edit_param(null);

        $countries = Constant::countries();

        // $courses = Course::GetAll();
        $trainingOptions = TrainingOption::has('sessions')
        ->join('courses', 'training_options.course_id', '=', 'courses.id')
        ->join('constants', function($join){
            $join->on('training_options.constant_id', '=', 'constants.id')
            ->where('parent_id', '=', '10');
        })
        ->select('training_options.id', 'training_options.constant_id', 'courses.title', 'constants.name', 'constants.slug')
        ->get()
        ->map(function($trainingOption){
            return [
                'id'=>$trainingOption->id,
                'constant_id'=>$trainingOption->constant_id,
                'course_title'=>Lang::TransTitle($trainingOption->title),
                'option_name'=>Lang::TransTitle($trainingOption->name),
                'slug'=>$trainingOption->slug
            ];
        });

        // $courses = Course::all();//remove it
        return Active::Edit([
            'eloquent'=>$discount,
            'post_type'=>$discount->post_type,
            'type'=>$params['type'],
            'optionConstants'=>$params['optionConstants'],
            'currencyConstants'=>$params['currencyConstants'],
            // 'courses'=>$courses,
            'discount_courses'=>$final_array,
            'countries' => $countries,
            'trainingOptions'=>$trainingOptions,
            // 'all_course' => $all_courses,
        ]);
    }

    public function update(DiscountRequest $request, Discount $discount){
        //  "check_1" => "on"
//         DB::table('discount_details')->where('master_id', $discount->id)->delete();
//         foreach ($request->all() as $key=>$value){
//             if(substr($key,0,6)=='check_'){
// //                echo $key.'_'.$value.'<br>';
//                 $course_id = explode('_',$key)[1];
//                 DB::table('discount_details')->insert([
//                     [
//                         'master_id' => $discount->id,
//                         'course_id' => $course_id,
//                         'created_by' => auth()->user()->id,
//                         'created_at' => Now()
//                     ],
//                 ]);

//             }
//         }

        $validated = $this->validated($request->validated());
        $validated['updated_by'] = auth()->user()->id;

        $count = Discount::where('code', $request->code)
        ->where('id', '!=', $discount->id)
        ->count();
        if($count==0){

            Discount::find($discount->id)->update($validated);

            foreach(request()->discounts as $disc){
                DiscountDetail::where('id', $disc)->update([
                    'value' => request()->input('discount_value_'.$disc)
                ]);
            }

            $this->StoreDiscountCountries($discount);

            return Active::Updated($discount->trans_excerpt);
        }
        Active::Flash('promocode', 'promo code in pre found', 'danger');
        return back();
    }

    public function destroy(Discount $discount, Request $request){
        Discount::where('id', $discount->id)->SoftTrash();
        return Active::Deleted($discount->trans_excerpt);
    }

    public function restore($discount){
        Discount::where('id', $discount)->RestoreFromTrash();
        $discount = Discount::where('id', $discount)->first();
        return Active::Restored($discount->trans_excerpt);
    }

    public function add_details(){

        $validator = Validator::make(request()->all(), [
            'master_id' => 'numeric|exists:discounts,id',
            'training_option_id' => 'numeric|exists:training_options,id',
            'session_id' => 'numeric',
            // 'session_id' => 'numeric|exists:sessions,id',
            'value' => 'numeric',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors_array = [];
            foreach ($errors->all() as $message) {
                array_push($errors_array, $message);
            }
            return [
                'errors'=>$errors_array,
            ];
        }

        $session = Session::find(request()->session_id);
        $trainingOption = TrainingOption::find(request()->training_option_id);

        $date_from = request()->date_from??null;
        $date_to = request()->date_to??null;
        if(!is_null($session)){
            if($trainingOption->constant_id==13){
                $date_from = $session->date_from;
                $date_to = $session->date_to;
            }
        }

        $details_id = null;
        if(request()->has('details_id') && !is_null(request()->details_id)){
            $details_id = request()->details_id;
        }
        else{
            $discountDetail = DiscountDetail::where('session_id', request()->session_id)
            ->where('master_id', request()->master_id)
            ->first();
            if(!is_null($discountDetail)){
                $details_id = $discountDetail->id;
            }
        }

        if(request()->session_id==-1){
            request()->session_id = null;
        }

        if(is_null($details_id)){
            DiscountDetail::create([
                'master_id'=>request()->master_id,
                'course_id'=>$trainingOption->course_id,
                'training_option_type_id'=>$trainingOption->constant_id,
                'training_option_id'=>$session->training_option_id??request()->training_option_id,
                'session_id'=>request()->session_id,
                'created_by'=>auth()->user()->id,
                'updated_by'=>auth()->user()->id,
                'value'=>request()->value,
                'date_from'=>$date_from,
                'date_to'=>$date_to,
            ]);
        }
        else{
            DiscountDetail::where('id', $details_id)
            ->update([
                'master_id'=>request()->master_id,
                'course_id'=>$trainingOption->course_id,
                'training_option_type_id'=>$trainingOption->constant_id,
                'training_option_id'=>$session->training_option_id,
                'session_id'=>request()->session_id,
                'updated_by'=>auth()->user()->id,
                'value'=>request()->value,
                'date_from'=>$date_from,
                'date_to'=>$date_to,
            ]);
        }

        $eloquent = Discount::find(request()->master_id);
        return view('training.discounts.details', compact('eloquent'))->render();
    }

    public function destroy_from_details(){
        DiscountDetail::where('id', request()->discount_id)->SoftTrash();
        return null;
    }

    private function StoreDiscountCountries($discount){
        if(request()->has('countries')){
            $discount->countries()->sync(request()->countries);
        }
    }

    private function Validated($validated)
    {
        $validated['excerpt'] = null;
        $validated['updated_by'] = auth()->user()->id;

        $validated['is_private'] = isset(request()->is_private)?1:0;

        // $validated['start_date'] = Carbon::create($validated['start_date'])->format('Y-m-d H:i:s');
        // $validated['end_date'] = Carbon::create($validated['end_date'])->format('Y-m-d H:i:s');
        // new
        // $validated['start_date'] = Carbon::createFromFormat('Y-m-d H:i', $validated['start_date'])->format('Y-m-d H:i:s');
        // $validated['end_date'] = Carbon::createFromFormat('Y-m-d H:i', $validated['end_date'])->format('Y-m-d H:i:s');
        return $validated;
    }
}
