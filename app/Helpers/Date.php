<?php

namespace App\Helpers;

use Carbon\Carbon;

class Date {

    public static function IsoFormat($date){
        $date = Carbon::parse($date);
        return $date->isoFormat('D MMM Y');
    }


    public static function replace_month_ar($date)
    {
        $date=  str_replace('January','يناير',$date);
        $date=  str_replace('February','فبراير',$date);
        $date=  str_replace('March','مارس',$date);
        $date=  str_replace('April','أبريل',$date);
        $date=  str_replace('May','مايو',$date);
        $date=  str_replace('June','يونيو',$date);
        $date=  str_replace('July','يوليو',$date);
        $date=  str_replace('August','أغسطس',$date);
        $date=  str_replace('September','سبنمبر',$date);
        $date=  str_replace('October','أكتوبر',$date);
        $date=  str_replace('November','نوفمبر',$date);
        $date=  str_replace('December','ديسمبر',$date);
        return $date;
    }
}
