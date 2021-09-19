<?php

namespace App\Traits\Json;

trait ExcerptTrait
{
	public function getTransExcerptAttribute(){
		$lang = app()->getLocale();
		return json_decode($this->excerpt)->$lang??$this->excerpt;
	}

	public function getEnExcerptAttribute(){
		return json_decode($this->excerpt)->en??null;
	}

	public function getArExcerptAttribute(){
		return json_decode($this->excerpt)->ar??null;
    }

    public function getEnShortExcerptAttribute(){
        $str = json_decode($this->excerpt)->en??null;
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
        $str = json_decode($this->excerpt)->ar??null;
        $substr = substr($str, 0, strpos(wordwrap($str, 130), "\n"));
        if(str_word_count($substr) < str_word_count($str)){
            $substr .= '...';
        }
        return $substr;
	}

	public function setExcerptAttribute()
	{
		$data = json_encode([
			'en'=>request()->en_excerpt??'',
			'ar'=>request()->ar_excerpt??''
		], JSON_UNESCAPED_UNICODE);

		$this->attributes['excerpt'] = $data;
	}
}
