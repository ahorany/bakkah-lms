<?php

namespace App\Models\Admin;

use App\Models\Training\Exam;
use App\Traits\ImgTrait;
use App\Traits\SeoTrait;
use App\Traits\JsonTrait;
use App\Traits\UserTrait;
use App\Traits\TrashTrait;
use App\Traits\PostMorphTrait;
use App\Traits\Json\DetailsTrait;
use App\Traits\Json\ExcerptTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use TrashTrait, ImgTrait;
    use JsonTrait, DetailsTrait, ExcerptTrait;
    use TrashTrait, ExcerptTrait, ImgTrait;
    use JsonTrait, DetailsTrait;
    use UserTrait;
    use SeoTrait;
    use PostMorphTrait;
    use Sluggable;


    protected $guarded = ['en_name', 'ar_name', 'en_details', 'ar_details', 'en_excerpt', 'ar_excerpt'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ! empty($this->slug) ? 'slug' : 'trans_name',
            ]
        ];
    }
    public static function GetPartners($post_type, $take=-1, $use_show_in_home=true, $in_education=1, $in_consulting=true){
        $partners = Partner::where('partners.post_type', $post_type)
        ->join('uploads', function($join){
            $join->on('uploads.uploadable_id', 'partners.id')
            ->where('uploads.uploadable_type', 'App\Models\Admin\Partner');
        })
        ->orderBy('partners.order', 'desc');

        if($take!=-1){
            $partners = $partners->take($take);
        }
        if($in_education==1){
            $partners = $partners->where('partners.in_education', $in_education);
        }
        if($in_consulting==1){
            $partners = $partners->where('partners.in_consulting', $in_consulting);
        }
        if($use_show_in_home){
            $partners = $partners->where('partners.show_in_home', 1);
        }
        $partners = $partners
        ->select('partners.id', 'partners.name', 'partners.excerpt', 'partners.slug', 'uploads.file', 'uploads.title as upload_title')
        ->get();

        return $partners;
        // $partners = Partner::where('post_type', $post_type)
        //     ->with('upload:uploadable_id,uploadable_type,file,title')
        //     ->orderBy('order', 'desc');
        // if($take!=-1){
        //     $partners = $partners->take($take);
        // }
        // if($in_education==1){
        //     $partners = $partners->where('in_education', $in_education);
        // }
        // if($in_consulting==1){
        //     $partners = $partners->where('in_consulting', $in_consulting);
        // }
        // if($use_show_in_home){
        //     $partners = $partners->where('show_in_home', 1);
        // }
        // $partners = $partners->get();
        // return $partners;
    }

    public function courses() {
        return $this->hasMany('App\Models\Training\Course');
    }

    public function exams(){
        return $this->belongsTo(Exam::class, 'partner_id');
    }

    public function agreement(){
        return $this->hasOne(Agreement::class, 'partner_id');
    }

}
