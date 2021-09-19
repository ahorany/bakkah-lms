<?php

namespace App\Models\Training;

use App\Constant;
use App\Traits\ImgTrait;
use App\Traits\Json\DetailsTrait;
use App\Traits\Json\ExcerptTrait;
use App\Traits\JsonTrait;
use App\Traits\PostMorphTrait;
use App\Traits\SeoTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Laravel\Scout\Searchable;
// use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Webinar extends Model
{
    use TrashTrait, ImgTrait, PostMorphTrait;
    use JsonTrait, ExcerptTrait, UserTrait, SeoTrait;
    use DetailsTrait;
    //use Searchable;
    use Sluggable;

    protected $guarded = ['en_title', 'ar_title', 'en_excerpt', 'ar_excerpt' , 'en_details', 'ar_details'];

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
    public function searchableAs()
    {
        return 'products_index';
    }

    const SEARCHABLE_FIELDS = ['id', 'en_title', 'ar_title', 'en_short_excerpt', 'ar_short_excerpt', 'en_path', 'ar_path'];
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->only(self::SEARCHABLE_FIELDS);
    }

    public function getCertificateFromAttribute(){
        $date = Carbon::parse($this->session_start_time);
        return $date->isoFormat('MMMM D, Y');
    }

    //Details
    public function getTransDetailsAttribute(){
        $lang = app()->getLocale();
        return json_decode($this->details)->$lang??$this->details;
    }

    public function getEnDetailsAttribute(){
        return json_decode($this->details)->en??null;
    }

    public function getArDetailsAttribute(){
        return json_decode($this->details)->ar??null;
    }

    public function setDetailsAttribute()
    {
        $data = json_encode([
            'en'=>request()->en_details,
            'ar'=>request()->ar_details
        ], JSON_UNESCAPED_UNICODE);
        $this->attributes['details'] = $data;
    }

    public function getSessionFromMonthAttribute(){
        $date = Carbon::parse($this->session_start_time);
        return $date->isoFormat('MMM');
    }

    public function getSessionStartAttribute(){
        $date = Carbon::parse($this->session_start_time);
        return $date->isoFormat('MMM D, Y');
    }

    public function getSessionEndAttribute(){
        $date = Carbon::parse($this->session_end_time);
        return $date->isoFormat('MMM D, Y');
    }
}
