<?php

namespace App\Helpers\Models\Training;

use App\Constant;
use App\Helpers\Lang;
use App\Models\Training\Course;
use App\Models\Training\TrainingOption;
use Carbon\Carbon;
// use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SessionHelper {

    protected $coin_id;
    protected $course;

    public function __construct($coin_id=null)
    {
        // $this->coin_id = Cache::get('coinID_'.request()->ip());//334 (SAR), 335(USD)
        $this->coin_id = $coin_id;
        if(is_null($coin_id)){
            $this->coin_id = GetCoinId();

            if(isset($_GET['coin_id'])){
                $this->coin_id = $_GET['coin_id'];
            }
        }
        // echo 'coin_id: '.$this->coin_id;
        $this->coin_price = ($this->coin_id==334) ? USD_PRICE : 1;
    }

    public function SetCourse($course){
        $this->course = $course;
    }

    //return static field (price or price_usd)
    public function PriceByContry(){
        return ($this->coin_id==335)?'price_usd':'price';
        // return 'price';
    }

    //return static field (exam_price or exam_price_usd)
    public function ExamPriceByContry(){
        return ($this->coin_id==335)?'exam_price_usd':'exam_price';
        // return 'exam_price';
    }

    //return static field (take2_price or take2_price_usd)
    public function Take2PriceByContry(){
        return ($this->coin_id==335)?'take2_price_usd':'take2_price';
        // return 'take2_price';
    }

    //return 1 / coin price
    public function GetCoinPrice(){
        return 1 / $this->coin_price;
    }

    //return @course price
    public function Price(){
        return $this->course->session_price;// * $this->GetCoinPrice();
    }

    //return discount percent
    public function Discount(){

        if(isset($_GET['promo']))
        {
            if($this->course->discount_value < 15)
            {
                return 15;
            }
        }
        return $this->course->discount_value;// * $this->GetCoinPrice();
    }

    //return discount value
    public function DiscountValue(){

        $price = $this->Price();
        $discount = $price * $this->Discount() / 100;
        return $discount;
    }

    //return course price after discount
    public function PriceAfterDiscount(){

        $price = $this->Price();
        $DiscountValue = $this->DiscountValue();
        return $price - $DiscountValue;
    }

    //return exam price
    public function ExamPrice($ExamIsIncluded=-1){

        if($ExamIsIncluded==-1){
            $ExamIsIncluded = $this->ExamIsIncluded();
        }
        if($ExamIsIncluded==1)
        {
            return $this->course->session_exam_price;// * $this->GetCoinPrice();
        }
        return 0;
    }

    public function ExamIsIncluded(){
        return $this->course->exam_is_included;
    }

    public function ExamIsIncludedWithOld(){
        $exam_price = old('exam_price', $this->ExamIsIncluded());
        return $exam_price;
    }

    public function SessionExamPrice(){
        return $this->course->session_exam_price;
    }

    //return @course price + exam price if (exam is include)
    public function PriceWithExamPrice(){
        $price = $this->PriceAfterDiscount();
        $price += $this->ExamPrice();
        $price += old('exam_price', 0);
        $price += old('take2_option', 0);
        return $price;
    }

    public function VAT(){
        $vat = ($this->coin_id==334) ? $this->course->vat : 0;
        return $vat;
    }

    public function VATValue(){
        $price = $this->PriceAfterDiscount();
        $value = $price * ($this->VAT() / 100);
        return $value;
    }

    public function TotalAfterVAT(){
        $price = $this->PriceAfterDiscount();
        $value = ($this->VAT() == 0) ? $price : $price + ($price * ($this->VAT() / 100));
        return $value;
    }

    //return @vat for price of course + exam price if(exam is include)
    public function VATFortPriceWithExamPrice(){

        $price = $this->PriceWithExamPrice();
        $vat = ($this->VAT() / 100);
        $price *= $vat;

        return $price;
    }

    //return @price + exam price (after sum vat for both)
    public function PriceAfterDiscountWithExamPriceAfterVAT(){

        $price = $this->PriceWithExamPrice();
        $vat = $this->VATFortPriceWithExamPrice();
        $price += $vat;

        return $price;
        // return $price.'===>'.$this->Discount();
    }

    //return price + exam without discount after vat for both
    public function PriceWithExamPriceAfterVAT(){

        $price = $this->Price();
        $price += $this->ExamPrice();
        $vat = ($this->VAT() / 100);
        $price_vat = $price * $vat;
        $price += $price_vat;

        return $price;
    }

    public function DiscountEndDate(){

        // if(auth()->check()){
        //     if(auth()->user()->id==1){
        //         dump($this->course->discount_end_date);
        //     }
        // }
        if(!is_null($this->course->discount_end_date)){
            $date = Carbon::parse($this->course->discount_end_date);
            // $date = Carbon::parse($this->course->discount_date_to);
            // $now = Carbon::now();
            // $diff = $date->diffInDays($now);
            // return $diff;
            return $date->diffForHumans();
        }
    }

    public function PDUs(){
        return $this->course->options_PDUs .' '.__('education.hours');
    }

    public function SessionDuration(){

        $session_duration_name = Lang::TransTitle($this->course->session_duration_name);
        if($this->course->session_duration_id==39 && app()->getLocale()=='ar'){
            if($this->course->session_duration <= 2 || $this->course->session_duration >= 11)
            {
                $session_duration_name = 'يوم';
            }
        }
        return $this->course->session_duration . ' '. $session_duration_name;
    }

    public function Take2Price(){
        // return $this->course->trainingOption->take2_price;
        return $this->course->take2_price;
    }

    public function ExamSimulationPrice(){
        return $this->course->exam_simulation_price;
    }

    public function ExamSimulationByContry(){
        return ($this->coin_id==335)?'exam_simulation_price_usd':'exam_simulation_price_sar';
        // return 'take2_price';
    }

    public function Sessions($limit = null, $category_id=null, $courses_ids = null)
    {
        $courses = $this->Query('leftJoin', false, 0, [1], $category_id)
        ->orderBy('discount_details.value', 'desc')
        ->orderBy('courses.order', 'asc');

        if(!is_null($courses_ids)) {
            $courses = $courses->whereIn('courses.id', $courses_ids);
        }

        $courses = $courses->take($limit)
        ->get()
        ->map(function ($data) {
            if(!is_null($data->session_id) || $data->constant_id==13){
                return $data;
            }
        })
        ->reject(function ($data) {
            return empty($data);
        });

        return $courses;
    }

    public function SessionsForRetarget()
    {
        $courses = $this->Query('leftJoin', true, 0)
        ->orderBy('discount_details.value', 'desc')
        ->orderBy('courses.order', 'asc')
        ->get()
        ->map(function ($data) {
            if(!is_null($data->session_id) || $data->constant_id==13){
                return $data;
            }
        })
        ->reject(function ($data) {
            return empty($data);
        });
        return $courses;
    }

    public function SessionsByPartners($partner_id){
        $courses = $this->Query('leftJoin', false, 0)
        ->where('courses.partner_id', $partner_id)
        ->orderBy('discount_details.value', 'desc')
        ->get()
        ->map(function ($data) {
            if(!is_null($data->session_id) || $data->constant_id==13){
                return $data;
            }
        })
        ->reject(function ($data) {
            return empty($data);
        });
        return $courses;
    }

    // public function SessionsByCategory($slug){
    //     $courses = $this->Query('leftJoin', false, 0)
    //     ->where('courses.partner_id', $partner_id)
    //     ->orderBy('discount_details.value', 'desc')
    //     ->get()
    //     ->map(function ($data) {
    //         if(!is_null($data->session_id) || $data->constant_id==13){
    //             return $data;
    //         }
    //     })
    //     ->reject(function ($data) {
    //         return empty($data);
    //     });
    //     return $courses;
    // }

    /*public function HotDeails(){
        $courses = $this->Query('join', false)
        ->orderBy('discount_details.value', 'desc')
        ->get()
        ->map(function ($data) {
            if(!is_null($data->session_id) || $data->constant_id==13){
                return $data;
            }
        })
        ->reject(function ($data) {
            return empty($data);
        });
        return $courses;
    }*/
    public function HotDeails($limit = null, $category_id=null)
    {
        $courses = $this->Query('join', false, 0, [1], $category_id)
        ->orderBy('discount_details.value', 'desc')
        ->get()
        ->map(function ($data) {
            if(!is_null($data->session_id) || $data->constant_id==13){
                return $data;
            }
        })
        ->reject(function ($data) {
            return empty($data);
        });
        return $courses;
    }

    public function TrainingOption($show_in_website=[1]){

        $courses = $this->Query('leftJoin', true, 0, $show_in_website);

        if(request()->has('course_id') && request()->course_id != -1 && !is_null(request()->course_id)){
            $courses = $courses->where('courses.id', request()->course_id);
        }
        if(request()->has('date_from') && !is_null(request()->date_from)){
            $courses = $courses->whereDate('sessions.date_from', '>=', request()->date_from);
        }
        if(request()->has('date_to') && !is_null(request()->date_to)){
            $courses = $courses->whereDate('sessions.date_to', '<=', request()->date_to);
        }
        if(request()->has('training_option_id') && request()->training_option_id!=-1 && !is_null(request()->training_option_id)){
            $courses = $courses->where('training_options.constant_id', '=', request()->training_option_id);
        }
        $courses = $courses->orderBy('sessions.date_from', 'asc')
        // ->orderBy('discount_details.value', 'desc')
        ->get()
        ->map(function ($data) {
            if(!is_null($data->session_id) || $data->constant_id==13){
                return $data;
            }
        })
        ->reject(function ($data) {
            return empty($data);
        });
        return $courses;
    }

    public function Single($course_slug, $GetAllSessions=false){
        $courses = $this->Query('leftJoin', $GetAllSessions)
        ->where('courses.slug', $course_slug)
        ->orderBy('sessions.session_start_time', 'asc')
        ->get()
        ->map(function ($data) {
            if(!is_null($data->session_id) || $data->constant_id==13){
                return $data;
            }
        })
        ->reject(function ($data) {
            return empty($data);
        })
        ;
        return $courses;
    }

    public function SingleForCheckout($course_slug, $show_in_website=[1]){

        $courses = $this->Query('leftJoin', true, 0, $show_in_website)
        ->where('courses.slug', $course_slug)
        ->orderBy('sessions.session_start_time', 'asc')
        ->get()
        ->map(function ($data) {
            if(!is_null($data->session_id) || $data->constant_id==13){
                return $data;
            }
        })
        ->reject(function ($data) {
            return empty($data);
        })
        ;
        return $courses;
    }

    public function CardsSingle($course, $SingleCourses){

        $trainingOptions = TrainingOption::with('course')->join('constants', 'constants.id', 'training_options.constant_id')
        ->where('course_id', $course->id)
        ->orderBy('constants.order')
        ->pluck('constants.id')
        ->toArray();

        $courses_cards = [];
        foreach($SingleCourses as $course){
            if(in_array($course->constant_id, $trainingOptions)){
                array_push($courses_cards, $course);
                $trainingOptions = \array_diff($trainingOptions, [$course->constant_id]);
            }
            if(count($courses_cards) == 2){
                break;
            }
        }
        return $courses_cards;
    }

    // private function Map(){
    // }

    private function SessionOBJ($GetAllSessions=false){

        if($GetAllSessions){
            $sessions_query = '(select *
                from sessions
                where session_start_time >= "'.DateTimeNowAddHours().'"
                and show_in_website = 1
                and deleted_at is null
            ) as sessions';
        }else{

            $SessionQuery = "select training_option_id, min(date_from) as date_from
            from sessions
            where date_from is not null
            and date(date_from) >= '".DateTimeNowAddHours()."'
            and deleted_at is null
            group by training_option_id
            order by date_from";

            $sessions_query = '(
                select sessions.*
                from sessions
                join ('.$SessionQuery.') as SessionQuery on SessionQuery.training_option_id = sessions.training_option_id
                and SessionQuery.date_from = sessions.date_from
                where sessions.deleted_at is null
            ) as sessions';
            // echo $sessions_query;
            // $sessions_query = '(select *
            // from sessions
            // where id in(
            //         select min(id)
            //         from sessions
            //         where session_start_time >= "'.DateTimeNowAddHours().'"
            //         and deleted_at is null
            //         group by training_option_id
            //     )
            // ) as sessions';
        }
        return $sessions_query;
    }

    private function Query($DiscountJoinFns='leftJoin', $GetAllSessions=false, $is_private=0
        , $show_in_website=[1], $category_id=null){

        $DateTimeNow = DateTimeNow();

        $sessions_price = 'sessions.'.$this->PriceByContry();//price_usd
        $sessions_exam_price = 'sessions.'.$this->ExamPriceByContry();//exam_price_usd
        $sessions_take2_price = 'training_options.'.$this->Take2PriceByContry();//take2_price_usd
        //$sessions_exam_simulation_price = 'training_options.'.$this->ExamSimulationByContry();//take2_price_usd
        $constatns_course = Constant::where('post_type', 'course')->pluck('id')->toArray();
        $constatns_language = Constant::where('post_type', 'language')->pluck('id')->toArray();

        $sessions_query = $this->SessionOBJ($GetAllSessions);

        $courses = Course::join('uploads', function($query){
            $query->on('courses.id', 'uploads.uploadable_id')
            ->where('uploads.post_type', 'image')
            ->where('uploads.uploadable_type', 'App\\Models\\Training\\Course');
        })
        ->leftJoin('training_options', 'courses.id', 'training_options.course_id')
        ->join('post_morphs', function($join)use($constatns_course, $category_id){
            $join->on('post_morphs.postable_id', 'courses.id')
            ->where('post_morphs.postable_type', 'App\\Models\\Training\\Course')
            ->whereNull('post_morphs.table_id')
            ->whereIn('post_morphs.constant_id', $constatns_course)
            ;
            if(!is_null($category_id)){
                $join->where('post_morphs.constant_id', $category_id);
            }
        })
        ->join('post_morphs as language_morphs', function($join)use($constatns_language){
            $join->on('language_morphs.postable_id', 'courses.id')
            ->where('language_morphs.postable_type', 'App\\Models\\Training\\Course')
            ->whereNull('language_morphs.table_id')
            ->whereIn('language_morphs.constant_id', $constatns_language)
            ;
        })
        ->leftJoin(DB::raw($sessions_query),
        function($join){
           $join->on('sessions.training_option_id', '=', 'training_options.id');
        })
        ->join('constants', 'constants.id', 'training_options.constant_id')
        ->leftJoin('constants as durations', 'durations.id', 'sessions.duration_type')
        ->join('constants as languages', 'languages.id', 'language_morphs.constant_id')
        ->$DiscountJoinFns(DB::raw("(
            select discount_details.*, discounts.excerpt, discounts.code, discounts.start_date, discounts.end_date
            from discount_details
            inner join discounts on discount_details.master_id = discounts.id
            where discounts.is_private = ".$is_private."
            and discount_details.deleted_at is null
            and discounts.deleted_at is null
            and discounts.coin_id = ".$this->coin_id."
        ) as discount_details"), function($join) use($DateTimeNow)
        {
            // discount_countries
            $join->on(function($w){
                $w->on('discount_details.session_id', 'sessions.id')
                ->where('constants.slug', 'auto-date')
                // ->orderBy('discount_details.id', 'desc')
                ;
            })
            // ;
            ->orWhere(function($w) use($DateTimeNow){
                $w->on('discount_details.session_id', 'sessions.id')
                ->where('constants.slug', 'custom-date')
                // ->where('training_options.constant_id', 11)
                ->whereDate('discount_details.start_date' ,'<=', $DateTimeNow)
                ->whereDate('discount_details.end_date', '>', $DateTimeNow)
                // ->whereDate('discount_details.date_from' ,'<=', $DateTimeNow)
                // ->whereDate('discount_details.date_to', '>', $DateTimeNow)
                ->orderBy('discount_details.id', 'desc');// remove it
            });
        })
        ->whereIn('courses.show_in_website', $show_in_website)
        ->select('sessions.id as session_id', 'courses.id', 'courses.title', 'courses.slug', 'training_options.exam_is_included'
        , 'courses.created_at'
        , 'training_options.id as training_option_id'
        , 'training_options.created_at as training_options_date'
        , 'training_options.constant_id', 'constants.name as constant_name', 'uploads.file'
        , $sessions_price.' as session_price', 'sessions.vat as vat'
        , $sessions_exam_price.' as session_exam_price', $sessions_take2_price.' as take2_price'
        //, $sessions_exam_simulation_price.' as exam_simulation_price'
        , 'sessions.money_back_guarantee', 'sessions.except_fri_sat', 'sessions.duration as session_duration'
        , 'sessions.date_from as session_date_from', 'sessions.date_to as session_date_to'
        , 'durations.id as session_duration_id', 'durations.name as session_duration_name', 'training_options.PDUs as options_PDUs'
        , 'training_options.details as options_details', 'sessions.session_time'
        , 'post_morphs.constant_id as category_id'
        , 'languages.name as language_name'
        , 'discount_details.id as discount_id'
        , 'discount_details.value as discount_value', 'discount_details.date_to as discount_date_to'
        , 'discount_details.excerpt', 'discount_details.code', 'courses.order'
        , 'sessions.retarget_discount', 'constants.slug as option_slug', 'constants.post_type as option__post_type'
        , 'discount_details.start_date as discount_start_date', 'discount_details.end_date as discount_end_date'
        , 'courses.rating', 'courses.reviews');

        return $courses;

        // ->leftJoin('discount_countries', function($join){
        //     $join->on('discount_countries.discount_id', 'discount_details.master_id');
        //     // if(Cache::has('countryID_'.request()->ip()))
        //     {
        //         $join->where('discount_countries.country_id', Cache::get('countryID_'.request()->ip()));
        //     }
        // })

        // ,'sessions.price as price','sessions.price_usd as price_usd','sessions.exam_price as exam_price','sessions.exam_price_usd as exam_price_usd','training_options.take2_price as course_take2_price','training_options.take2_price_usd as course_take2_price_usd'
    }
}
