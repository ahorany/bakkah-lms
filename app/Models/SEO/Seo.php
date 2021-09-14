<?php

namespace App\Models\SEO;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $guarded = [];

    public function getTransAuthorAttribute(){
        $lang = app()->getLocale();
        return json_decode($this->author)->$lang??null;
    }

    public function getEnAuthorAttribute(){
        return json_decode($this->author)->en??null;
    }

    public function getArAuthorAttribute(){
        return json_decode($this->author)->ar??null;
    }

    public function setAuthorAttribute()
    {
        $data = json_encode([
            'en'=>request()->en_seo_author,
            'ar'=>request()->ar_seo_author
        ], JSON_UNESCAPED_UNICODE);

        $this->attributes['author'] = $data;
    }

    public function getTransDescriptionAttribute(){
        $lang = app()->getLocale();
        return json_decode($this->description)->$lang??null;
    }

    public function getEnDescriptionAttribute(){
        return json_decode($this->description)->en??null;
    }

    public function getArDescriptionAttribute(){
        return json_decode($this->description)->ar??null;
    }

    public function setDescriptionAttribute()
    {
        $data = json_encode([
            'en'=>request()->en_seo_description,
            'ar'=>request()->ar_seo_description
        ], JSON_UNESCAPED_UNICODE);

        $this->attributes['description'] = $data;
    }

    public function seoable(){
    	return $this->morphTo();
    }

    public static function seo($post){

        $isExist = $post->seo()->count();

        $args = [
            'description'=>null,
            'author'=>null,
            'updated_by'=>auth()->user()->id,
        ];
        if(!$isExist) {
            $args = array_merge($args, [
                'created_by'=>auth()->user()->id,
            ]);
        }
        $post->seo()->updateOrCreate([], $args);

        self::Postkeyword($post);
        self::Postkeyword_ar($post);
    	/*$isExist = $post->seo()->count();

    	$args = [
            'description'=>request()->seo_description,
            'author'=>request()->seo_author,
            'updated_by'=>auth()->user()->id,
        ];
    	if(!$isExist) {
    		$args = array_merge($args, [
    			'created_by'=>auth()->user()->id,
    		]);
    	}
        $post->seo()->updateOrCreate([], $args);*/

//        self::Postkeyword($post);
    }

    private static function Postkeyword($post){

        if(!is_null(request()->seo_keywords) && is_array(request()->seo_keywords)){

            $seo_keywords__array = [];
            foreach(request()->seo_keywords as $key => $value){

                $seokeyword = Seokeyword::firstOrCreate([
                    'title'=>$value,
                ], [
                    'created_by'=>auth()->user()->id,
                    'updated_by'=>auth()->user()->id,
                ]);
                // dump($key.'====>'.$value);
                $post->postkeyword()->updateOrCreate([
                    'seokeywords_id'=>$seokeyword->id,
                    'locale'=>'en',
                ], [
                    'created_by'=>auth()->user()->id,
                    'updated_by'=>auth()->user()->id,
                ]);

                array_push($seo_keywords__array, $seokeyword->id);
            }
            $post->postkeyword()
                ->whereNotIn('seokeywords_id', $seo_keywords__array)
                ->where('locale', 'en')
                ->delete();
        }
    }

    private static function Postkeyword_ar($post){

        if(!is_null(request()->seo_keywords_ar) && is_array(request()->seo_keywords_ar)){

            $seo_keywords__array = [];
            foreach(request()->seo_keywords_ar as $key => $value){

                $seokeyword = Seokeyword::firstOrCreate([
                    'title'=>$value,
                ], [
                    'created_by'=>auth()->user()->id,
                    'updated_by'=>auth()->user()->id,
                ]);
                // dump($key.'====>'.$value);
                $post->postkeyword()->updateOrCreate([
                    'seokeywords_id'=>$seokeyword->id,
                    'locale'=>'ar',
                ], [
                    'created_by'=>auth()->user()->id,
                    'updated_by'=>auth()->user()->id,
                ]);

                array_push($seo_keywords__array, $seokeyword->id);
            }
            $post->postkeyword()
                ->whereNotIn('seokeywords_id', $seo_keywords__array)
                ->where('locale', 'ar')
                ->delete();
        }
    }
}
