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
use App\Models\Training\Category;
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

    public function getEnPathAttribute(){
        return 'sessions/'.$this->slug;
    }

    public function getArPathAttribute(){
        return 'ar/sessions/'.$this->slug;
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




    public function deliveryMethod(){
        return $this->belongsTo(Constant::class, 'training_option_id', 'id');
    }


    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }


    ///////////// lms //////////////

    public function users(){
        return $this->belongsToMany(User::class,'courses_registration','course_id')->withPivot('user_id' ,'course_id','role_id','rate', 'progress','expire_date');
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
