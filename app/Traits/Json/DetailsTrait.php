<?php

namespace App\Traits\Json;

trait DetailsTrait
{
	public function getTransDetailsAttribute(){
		$lang = app()->getLocale();
		return json_decode($this->details)->$lang??null;
//		return json_decode($this->details)->$lang??$this->details;
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
}
