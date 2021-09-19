<?php

namespace App\Models\SEO;

use Illuminate\Database\Eloquent\Model;

class Postkeyword extends Model
{
    protected $guarded = [];

    public function seokeywordable() {
    	return $this->morphTo();
    }

    public function seokeyword(){
    	return $this->belongsTo(Seokeyword::class, 'seokeywords_id');
    }

    public static function Related($post, $class='\App\Post'){
    	if(!isset($post))
    		return null;

	    $Postkeywords = $post->postkeyword()->get();

	    $Postkeywords__array = [];
	    foreach($Postkeywords as $value){
	    	array_push($Postkeywords__array, $value->seokeywords_id);
	    }

	    $Postkeywords1 = self::whereIn('seokeywords_id', $Postkeywords__array)
	    ->where('seokeywordable_id', '!=', $post->id)
	    ->distinct('seokeywordable_id')->get();
	    $Postkeywords11__array = [];
	    foreach($Postkeywords1 as $Postkeywords11){
	    	array_push($Postkeywords11__array, $Postkeywords11->seokeywordable_id);
	    }

	    $relatedlists = $class->whereIn('id', $Postkeywords11__array)->latest()
		->take(3)
		->get();

		return $relatedlists;
    }
}
