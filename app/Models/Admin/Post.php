<?php

namespace App\Models\Admin;

use App\Constant;
use App\Models\Admin\Service\Service;
use App\Models\Admin\Service\ServiceArchive;
use App\Models\SEO\Postkeyword;
use App\Models\SEO\Seo;
use App\Traits\DateTrait;
use App\Traits\ImgTrait;
use App\Traits\Post\SliderTrait;
use App\Traits\SeoTrait;
use App\Traits\TransTrait;
use App\Traits\PostMorphTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use TrashTrait, ImgTrait, TransTrait, PostMorphTrait;
    use UserTrait;
//    use SeoTrait;
    use DateTrait;
    use SliderTrait;
    //use Searchable;
    use Sluggable;

    protected $guarded = [];

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'learning_posts_index';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'  => ! empty($this->slug) ? 'slug' : 'title',
                'unique'  => false,
            ]
        ];
    }

    const SEARCHABLE_FIELDS = ['id', 'en_title', 'ar_title', 'en_short_excerpt', 'ar_short_excerpt', 'en_path', 'ar_path', 'model_name', 'locale'];
    // const SEARCHABLE_FIELDS = ['id', 'en_title', 'en_title', 'locale', 'basic_id', 'en_short_excerpt', 'ar_short_excerpt', 'path'];

    public function toSearchableArray()
    {
        return $this->only(self::SEARCHABLE_FIELDS);
    }

    public function shouldBeSearchable()
    {
        return $this->where('locale', app()->getLocale());
    }

    public function getEnTitleAttribute(){
		return $this->title;
	}

	public function getArTitleAttribute(){
		return $this->title;
    }

    public function getEnShortExcerptAttribute(){
        $str = $this->excerpt;
        $substr = substr($str, 0, strpos(wordwrap($str, 130), "\n"));
        if(str_word_count($substr) < str_word_count($str)){
            $substr .= '...';
        }
        // $_count = str_word_count($str);
        // if($_count>130){
        //     $substr = mb_substr($str, 0, 130, "UTF-8");
        //     return $substr;
        // }
        return $substr;
    }

    public function getArShortExcerptAttribute(){
        $str = $this->excerpt;
        $substr = substr($str, 0, strpos(wordwrap($str, 130), "\n"));
        if(str_word_count($substr) < str_word_count($str)){
            $substr .= '...';
        }
        return $substr;
    }

    public function getEnPathAttribute(){
        return 'knowledge-center/'.$this->slug;
        // return env('APP_URL').'knowledge-center/'.$this->slug;
    }

    public function getArPathAttribute(){
        return 'ar/knowledge-center/'.$this->slug;
        // return env('APP_URL').'ar/knowledge-center/'.$this->slug;
    }
    // public function getPathAttribute(){
    //     if($this->locale=='ar'){
    //         return env('APP_URL').'ar/knowledge-center/'.$this->slug;
    //     }
    //     return env('APP_URL').'knowledge-center/'.$this->slug;
    //     // $route = route('education.static.knowledge-center.single', ['slug'=>$this->slug]);

    //     // $lang = $this->locale=='ar'?'ar':'en';
    //     // return LaravelLocalization::getLocalizedURL($lang, $route, [], true);
    // }
    public function getModelNameAttribute(){
        return 'Post';
    }

    public function seo(){
        return $this->morphOne(Seo::class, 'seoable', 'seoable_type', 'seoable_id'
        , 'basic_id');
    }

    //I Change postkeyword To postkeywords
    public function postkeyword(){
        return $this->morphMany(Postkeyword::class, 'seokeywordable', 'seokeywordable_type'
        , 'seokeywordable_id', 'basic_id');
    }

    public function postkeywords(){
        return $this->morphMany(Postkeyword::class, 'seokeywordable', 'seokeywordable_type'
            , 'seokeywordable_id', 'basic_id');
    }

    public function postMorph()
    {
        return $this->morphOne(PostMorph::class, 'postable', 'postable_type', 'postable_id', 'basic_id');
    }

    public function postMorphs()
    {
        return $this->morphMany(PostMorph::class, 'postable', 'postable_type', 'postable_id', 'basic_id');
    }

    public function country(){
        return $this->belongsto(Constant::class, 'country_id', 'id');
    }

    public function services(){
        return $this->hasMany(Service::class, 'master_id', 'id')->orderBy('order', 'asc');
    }

    public function serviceArchives(){
        return $this->hasMany(ServiceArchive::class, 'master_id', 'id')->orderBy('order', 'asc');
    }

    public static function GetPost($post_type, $take, $order_field='post_date', $order='desc'){

        $posts = self::where('post_type', $post_type)
        ->with('upload:uploadable_id,uploadable_type,file,title')
        ->lang()
        ->take($take)
        ->orderBy($order_field, $order)
        ->where('show_in_website',1)
        ->select('id', 'title', 'excerpt', 'url');
        $posts = $posts->get();
        return $posts;
    }
}
