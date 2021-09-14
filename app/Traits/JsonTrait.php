<?php

namespace App\Traits;

trait JsonTrait
{
	public function getTransNameAttribute(){
		$lang = app()->getLocale();
		return json_decode($this->name)->$lang??null;
//		return json_decode($this->name)->$lang??$this->name;
	}

	public function getEnNameAttribute(){
		return json_decode($this->name)->en??null;
//		return json_decode($this->name)->en??$this->name;
	}

	public function getArNameAttribute(){
		return json_decode($this->name)->ar??null;
	}

	public function setNameAttribute()
	{
		$data = json_encode([
			'en'=>request()->en_name,
			'ar'=>request()->ar_name??request()->en_name
		], JSON_UNESCAPED_UNICODE);

		$this->attributes['name'] = $data;
	}

	//moved to traits\json
	public function getTransTitleAttribute(){
		$lang = app()->getLocale();
		return json_decode($this->title)->$lang??$this->title;
	}

	public function getEnTitleAttribute(){
		return json_decode($this->title)->en??$this->title;
	}

	public function getArTitleAttribute(){
		return json_decode($this->title)->ar??$this->title;
	}

	public function setTitleAttribute()
	{
		$data = json_encode([
			'en'=>request()->en_title,
			'ar'=>request()->ar_title??request()->en_title,
		], JSON_UNESCAPED_UNICODE);

		$this->attributes['title'] = $data;
	}

    public function getTransQuestionAttribute(){
		$lang = app()->getLocale();
		return json_decode($this->question)->$lang??$this->question;
	}

    public function getEnQuestionAttribute(){
		return json_decode($this->question)->en??$this->question;
	}

	public function getArQuestionAttribute(){
		return json_decode($this->question)->ar??$this->question;
	}

}
