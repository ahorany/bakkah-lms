<?php

namespace App\Models\Training;

use App\Constant;
use App\Models\Admin\Partner;
use App\Models\Training\Discount\DiscountDetail;
use App\Traits\DetailMorphTrait;
use App\Traits\ImgTrait;
use App\Traits\Json\ExcerptTrait;
use App\Traits\JsonTrait;
use App\Traits\PostMorphTrait;
use App\Traits\SeoTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use App\User;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\Cache;
use Laravel\Scout\Searchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
// use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Course extends Model
{
    use TrashTrait, ImgTrait, PostMorphTrait;
    use JsonTrait, ExcerptTrait, UserTrait, SeoTrait;
    use DetailMorphTrait;
    use Sluggable;

//    use Searchable;

    protected $guarded = ['en_title', 'ar_title', 'en_excerpt', 'ar_excerpt'
    , 'en_accredited_notes', 'ar_accredited_notes', 'en_short_title', 'ar_short_title', 'en_disclaimer', 'ar_disclaimer'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ! empty($this->slug) ? 'slug' : 'trans_title',
            ]
        ];
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
//    public function searchableAs()
//    {
//        return 'products_index';
//    }
//
//    const SEARCHABLE_FIELDS = ['id', 'en_title', 'ar_title', 'en_short_excerpt', 'ar_short_excerpt', 'en_path', 'ar_path'
//    , 'model_name', 'order', 'algolia_order', 'price'];
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
//    public function toSearchableArray()
//    {
//        return $this->only(self::SEARCHABLE_FIELDS);
//        // $array = $this->toArray();
//        // $courses = self::all();
//        // $array = $courses->map(function($data){
//        //     return [
//        //         'en_title'=>$data['en_title'],
//        //         'ar_title'=>$data['ar_title'],
//        //     ];
//        // })->toArray();
//
//        // return $array;
//    }

    public function getEnPathAttribute(){
        return 'sessions/'.$this->slug;
        // return env('APP_URL').'sessions/'.$this->slug;
        // return route('education.courses.single', ['slug'=>$this->slug]);
    }

    public function getArPathAttribute(){
        return 'ar/sessions/'.$this->slug;
        // return env('APP_URL').'ar/sessions/'.$this->slug;

        // $route = route('education.courses.single', ['slug'=>$this->slug]);
        // return LaravelLocalization::getLocalizedURL('ar', $route, [], false);
    }

    public function getModelNameAttribute(){
        return 'Course';
    }

    //AccreditedNotes
    public function getTransAccreditedNotesAttribute(){
        $lang = app()->getLocale();
        return json_decode($this->accredited_notes)->$lang??$this->accredited_notes;
    }

    public function getEnAccreditedNotesAttribute(){
        return json_decode($this->accredited_notes)->en??null;
    }

    public function getArAccreditedNotesAttribute(){
        return json_decode($this->accredited_notes)->ar??null;
    }

    public function setAccreditedNotesAttribute()
    {
        $data = json_encode([
            'en'=>request()->en_accredited_notes,
            'ar'=>request()->ar_accredited_notes
        ], JSON_UNESCAPED_UNICODE);
        $this->attributes['accredited_notes'] = $data;
    }

    //ShortTitle
    public function getTransShortTitleAttribute(){
        $lang = app()->getLocale();
        return json_decode($this->short_title)->$lang??$this->short_title;
    }

    public function getEnShortTitleAttribute(){
        return json_decode($this->short_title)->en??null;
    }

    public function getArShortTitleAttribute(){
        return json_decode($this->short_title)->ar??null;
    }

    public function setShortTitleAttribute()
    {
        $data = json_encode([
            'en'=>request()->en_short_title,
            'ar'=>request()->ar_short_title
        ], JSON_UNESCAPED_UNICODE);
        $this->attributes['short_title'] = $data;
    }

    //Disclaimer
    public function getTransdisclaimerAttribute(){
		$lang = app()->getLocale();
		return json_decode($this->disclaimer)->$lang??$this->disclaimer;
	}

	public function getEndisclaimerAttribute(){
		return json_decode($this->disclaimer)->en??null;
	}

	public function getArdisclaimerAttribute(){
		return json_decode($this->disclaimer)->ar??null;
	}

	public function setdisclaimerAttribute()
	{
		$data = json_encode([
			'en'=>request()->en_disclaimer,
			'ar'=>request()->ar_disclaimer
		], JSON_UNESCAPED_UNICODE);

		$this->attributes['disclaimer'] = $data;
    }

//    public function trainingOption(){
//        return $this->hasOne(TrainingOption::class, 'course_id');//->latest()
//    }
//
//    public function trainingOptions(){
//        return $this->hasMany(TrainingOption::class, 'course_id');
//    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }

    public function discountDetails(){
        return $this->hasMany(DiscountDetail::class, 'course_id', 'id');
    }

    public function discountDetail(){
        return $this->hasOne(DiscountDetail::class, 'course_id', 'id');
    }

    public function getTake2PriceByCurrencyAttribute(){
        // $price = $this->take2_price;
        // if(Cache::has('coinID_'.request()->ip())){
        //     if(Cache::get('coinID_'.request()->ip())==335){
        //         $price = $this->take2_price_usd;
        //     }
        // }
        $price = $this->take2_price;
        if(GetCoinId()==335){
            $price = $this->take2_price_usd;
        }
        return !is_null($price)?$price:0;
    }

//    public function CourseList($query){
//        return $query->postMorph()->whereBetween('constant_id', [22,29])->get();
//    }

    //Moved to PostMorphTrait
    // public static function AddMorph($id, $parent_id=25)
    // {
    //     $course = self::find($id);
    //     $constants = Constant::where('parent_id', $parent_id)->get();
    //     foreach($constants as $constant){
    //         $course->postMorphs()->create([
    //             'created_by'=>auth()->user()->id,
    //             'updated_by'=>auth()->user()->id,
    //             'constant_id'=>$constant->id,
    //             'table_id'=>$parent_id,
    //         ]);
    //     }
    // }

    //remove it
    // public static function Bage($course){
    //     if($course->created_at->addDays(30) >= Carbon::now()){
    //         return '<div class="badge bg-info text-white">'.__("education.New").'</div>';
    //     }
    //     /*return '<div class="badge bg-success text-white">'.__('education.Best Seller').'</div>
    //                                     <div class="badge bg-danger text-white">'.__('education.Hot & New').'</div>';*/
    //     return '';
    // }

    public function partner(){
        return $this->belongsTo(Partner::class, 'partner_id', 'id');
    }

    // public function agreement(){
    //     return $this->belongsTo(Course::class, 'partner_id', 'partner_id');
    // }

    public function scopeGetAll($query){
        $all_courses = $query->has('trainingOptions.sessions')
            ->where('active',1)
            // ->whereNull('wp_migrate')
            ->with('trainingOptions.sessions')
            ->orderBy('order')
            ->get();
        return $all_courses;
    }

    // public function setSlugAttribute(){

    //     if(request()->has('slug')){
    //         $slug = request()->slug;
    //         if ((static::whereSlug($slug)->exists()) || is_null($slug) ) {
    //             if (static::whereSlug($slug = Str::slug(json_decode($this->title)->en??substr(md5(time()),0,10), '-'))->exists()) {
    //                 $slug = "{$slug}-{$this->id}";
    //             }
    //         }
    //         $this->attributes['slug'] = $slug;
    //     }
    // }

    public function getSales($courseId){
        if($courseId){
            $course_sales = Cart::where('course_id', $courseId)
                ->whereNull('trashed_status')
                ->whereIn('payment_status', [68, 376, 317]) //paid 317
                // ->where('coin_id', 335)
                ->select(DB::raw('(sum(total_after_vat)-sum(refund_value_after_vat)) as total_after_vat, coin_id, count(id) as trainees_no'))
                ->groupBy('course_id', 'coin_id')
                ->get()
                ->toArray();
            return $course_sales;
        }else{
            return null;
        }
    }


    ///////////// lms //////////////

    public function users(){
        return $this->belongsToMany(User::class,'courses_registration','course_id')->withPivot('user_id' ,'course_id','rate', 'progress','expire_date');
    }


    public function contents(){
        return $this->hasMany(Content::class,'course_id');
    }

    public function units(){
        return $this->hasMany(Unit::class,'course_id');
    }

    public function course_rate(){
        return $this->hasOne(CourseRegistration::class,'course_id');
    }

    public function training_option(){
        return $this->belongsTo(Constant::class,'training_option_id');
    }


}
