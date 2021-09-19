<?php

namespace App;

use App\Models\Admin\Detail;
use App\Models\Admin\PostMorph;
use App\Models\Training\Discount\Discount;
use App\Models\Training\Session;
use App\Models\Training\XeroAccount;
use App\Traits\Json\ExcerptTrait;
use App\Traits\JsonTrait;
use App\Traits\SeoTrait;
use App\Traits\TransTrait;
use Laravel\Scout\Searchable;

class Constant extends Eloquent
{
    use JsonTrait, SeoTrait, ExcerptTrait;
    use Searchable;
    use TransTrait;

	protected $guarded = [
	    'en_name', 'ar_name',
    ];

    public function searchableAs()
    {
        return 'constants_index';
    }

    const SEARCHABLE_FIELDS = ['id', 'name'];

    public function toSearchableArray()
    {
        return $this->only(self::SEARCHABLE_FIELDS);
    }

    public function postMorphs(){
        return $this->hasMany(PostMorph::class, 'constant_id');
    }

	public function postMorph(){
	    return $this->hasMany(PostMorph::class, 'constant_id');
    }

    public function details(){
        return $this->hasMany(Detail::class, 'constant_id');
    }

    public function xeroAccount(){
    	return $this->morphOne(XeroAccount::class, 'xeroAccountable');
    }

    public function discounts(){
        return $this->belongsToMany(Discount::class, 'discount_countries', 'country_id', 'discount_id');
    }

    public function scopeCountries($query){
        return $query->where('post_type','countries')
        ->whereNotNull('order')
        ->orderBy('order', 'asc')
        ->get();
    }

    public function scopeCategories($query){
        return $query->where('post_type', 'course')
        ->where('id', '!=', 378)
        ->orderBy('order')->get();
    }

    public function session_attendant(){
        return $this->hasOne(Session::class,'attendants_status_id');
    }

}
