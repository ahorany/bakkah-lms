<?php

namespace App\Helpers;

use Carbon\Carbon;

class Date {

    public static function IsoFormat($date){
        $date = Carbon::parse($date);
        return $date->isoFormat('D MMM Y');
    }
}
