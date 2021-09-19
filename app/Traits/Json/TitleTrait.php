<?php

namespace App\Traits\Json;

trait TitleTrait
{
	public function getTransTitleAttribute(){
		$lang = app()->getLocale();
		return json_decode($this->title)->$lang??$this->title;
	}

	public function getEnTitleAttribute(){
		return json_decode($this->title)->en??null;
	}

	public function getArTitleAttribute(){
		return json_decode($this->title)->ar??null;
	}

	public function setTitleAttribute()
	{
		$data = json_encode([
			'en'=>request()->en_title,
			'ar'=>request()->ar_title
		], JSON_UNESCAPED_UNICODE);

		$this->attributes['title'] = $data;
	}
}
