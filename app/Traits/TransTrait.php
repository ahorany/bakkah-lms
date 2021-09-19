<?php

namespace App\Traits;

trait TransTrait
{
	public function scopeLang($query, $prefix='')
	{
	    return $query->where($prefix.'locale', app()->getLocale());
	}

	public static function SetTrans($post, $origin_id=null, $locale='en'){

	    $this_id = $post->id;
	    $from_locale = app()->getLocale();
		///////////////////////////////
	    if(isset($_POST['origin_id']) && !empty($_POST['origin_id'])){
//	    if(isset($_GET['origin_id']) && !empty($_GET['origin_id'])){
//	     if(!is_null($origin_id)){
//	    	 $from_id = $origin_id;//1 : from_id
//	    	 $to_locale = $locale;//2 : to_locale
	    	$from_id = $_POST['origin_id'];//1 : from_id
	    	$to_locale = $_POST['locale'];//2 : to_locale

	    	$post->locale = $to_locale;
            $post->basic_id = $from_id;
	    	$post->locale_id = json_encode([
	    		$from_locale=>$from_id,
	    		'basic'=>$from_id,
	    	]);
			$post->save();

			//
	    	$basic_post = self::find($from_id);
	    	if(isset($basic_post)){
		    	$basic_post->locale_id = json_encode([
		    		$to_locale=>$this_id,
		    		'basic'=>$from_id,
		    	]);
			    $basic_post->save();
			}
	    }
	    else {
	    	$post->locale = $from_locale;
	    	$post->basic_id = $post->id;
	    	$post->locale_id = json_encode([
	    		'basic'=>$post->id,
	    	]);
			$post->save();
	    }
	}

	public function getTransAttribute()
	{
		$trans = LangsArray();
		if(is_null($this->locale_id))
		{
			return $this->CreatePostLang($trans);
		}
		else
		{
			return $this->EditPostLang($trans);
		}
    }

    private function GetSiteLang()//ahorany
    {
        $trans = LangsArray();
        $locale = $trans[app()->getLocale()];
        return '<a title="'.__('app.translate', ['locale'=>$locale]).'" alt="'.$locale.'" rel="alternate" hreflang="{{ $locale }}" href="'.LaravelLocalization::getLocalizedURL($locale, null, [], true).'">
            <img src="'.CustomAsset('upload/lang/'.$locale.'.png').'" class="flag">
        </a>';
    }

	private function CreatePostLang($trans=array())
	{
		$locale = $trans[app()->getLocale()];
		$route = route('admin.posts.create', ['post_type'=>$this->post_type, 'origin_id'=>$this->id, 'locale'=>$locale]);
		$href = \LaravelLocalization::getLocalizedURL($locale, $route, [], true);
		return '<a target="'.$locale.'" href="'.$href.'" title="'.__('app.trans').' '.__("app.to_ar").'">
		<i class="fas fa-plus"></i>
		</a>';
		/*$locale = $trans[app()->getLocale()];
		$route = route('admin.posts.create', ['post_type'=>$this->post_type, 'origin_id'=>$this->id, 'locale'=>$locale]);
		return '<a target="'.$locale.'" href="'.$route.'" title="'.__('app.trans').' '.__("app.to_ar").'">
		<i class="fas fa-plus"></i>
		</a>';*/
	}

	private function EditPostLang($trans=array())
	{
		foreach(json_decode($this->locale_id) as $key => $val):
		    if(in_array($key, $trans) && !is_null($val)){
			    return '<a href="'.route('admin.posts.edit', ['post'=>$val, 'post_type'=>$this->post_type]).'" title="'.__('app.edit').' '.$key.'">
			      <img src="'.CustomAsset('upload/lang/'.$key.'.png').'" class="flag">
			    </a>';
		    }
		    else {
		    	return $this->CreatePostLang($trans);
		    }
		endforeach;
    }

    public static function StoreData($validated, $origin_id=null, $locale='en')
    {
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;
    	$post = self::create($validated);
        self::SetTrans($post, $origin_id, $locale);
        self::SetMorph($post->id);
        \App\Models\SEO\Seo::seo($post);
        return $post;
    }

    public static function UpdateData($post, $validated)
    {
        $validated['updated_by'] = auth()->user()->id;
        self::where('id', $post->id)->update($validated);
        self::SetMorph($post->id);
        self::UploadFile($post, ['method'=>'update']);
        \App\Models\SEO\Seo::seo($post);
        return $post;
    }

    public static function SoftTrash($post)
    {
        self::where('id', $post->id)->SoftTrash();
        self::LocaleTrash($post->locale_id);
    }

    public static function RestoreFromTrash($post)
    {
        self::where('id', $post)->RestoreFromTrash();
        $post = self::where('id', $post)->first();
        self::RestoreFromLocaleTrash($post->locale_id);
        return $post;
    }
}
