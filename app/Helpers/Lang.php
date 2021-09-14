<?php

namespace App\Helpers;

class Lang {

    public static function TransTitle($title=null){
        $lang = app()->getLocale();
		return json_decode($title)->$lang??$title;
    }
}
