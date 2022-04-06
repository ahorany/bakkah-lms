<?php

define('ADMIN', 'admin');
define('FRONT', 'front');
define('PAGINATE', 15);
define('SUBHOUR', 2);//session will expire before 2 hours
define('YEAR', date('Y'));
define('green_attendance_rate', 15);
define('PAY_COURSE_BAKKAH_URL', 'https://bakkah.com/sessions/');
define('COMPLETED_PROGRESS', 100);


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

function is_super_admin(){
    if(session()->get('is_super_admin')){
        return true;
    }
    return false;
}


function getCurrentUserBranchData(){
    if (session()->has('user_branch')){
        return session('user_branch');
    }
    return  null;
}


function is_dynamic_certificate()
{
    return 1;//1 OR 0
}


function CustomAsset($url){
    return asset(env('LIVE_ASSET').$url);
}

function DateTimeNow(){
    return Carbon::now()->format('Y-m-d H:i:s');
}

function DateTimeNowAddHours(){
    return Carbon::now()->addHours(SUBHOUR)->format('Y-m-d H:i:s');
}

function NumberFormat($number){
    $number = str_replace( ',', '', $number );
    return round($number, 2);
}

function NumberFormatWithComma($value, $decimal=0) {
    if ( intval($value) == $value ) {
        $return = number_format($value, $decimal, ".", ",");
    }
    else {
        $decimal = $decimal!=0?$decimal:2;
        $return = number_format($value, $decimal, ".", ",");
        /*
        If you don't want to remove trailing zeros from decimals,
        eg. 19.90 to become: 19.9, remove the next line
        */
        $return = rtrim($return, 0);
        $return = rtrim($return, '.');
    }
    return $return;
}

function NumberFormatWithCommaWithMinus($value, $decimal=0){
    if($value<0)
        return '<span class="text-danger">('.NumberFormatWithComma(abs($value), $decimal).')</span>';
    return '<span class="text-dark">'.NumberFormatWithComma($value, $decimal).'</span>';
}

function LangsArray(){
    $trans = [
        'en'=>'ar',
        'ar'=>'en',
    ];
    return $trans;
}



function GetValueByLang($title=null){
	$lang = app()->getLocale();
	return json_decode($title)->$lang??$title;
}

function GetValueByLangNullable($title=null){
	$lang = app()->getLocale();
	return json_decode($title)->$lang??null;
}



function CustomRoute($route_name, $args=[]){

    $route = route($route_name, $args);
    return LaravelLocalization::getLocalizedURL(app()->getLocale(), $route, [], true);
}





function GetSiteLang()
{
	$trans = LangsArray();
	$locale = $trans[app()->getLocale()];
	return '<a title="'.__('app.translate', ['locale'=>$locale]).'" alt="'.$locale.'" rel="alternate" hreflang="{{ $locale }}" href="'.LaravelLocalization::getLocalizedURL($locale, null, [], true).'">
		<img src="'.CustomAsset('upload/lang/'.$locale.'.png').'" class="flag">
	</a>';
}




function Get($name, $default=null){
    if(isset($_GET[$name]) && !empty($_GET[$name])) {
        if(strpos($_GET[$name], "?")!=false)
            return substr($_GET[$name], 0, strpos($_GET[$name], "?"));
        return $_GET[$name];
    }
    return $default;
}




function GetPostType($default=null){
	return get('post_type', $default);
}




function GetTrash(){
	return get('trash', null);
}





function array_without_empty($var){
    return ($var !== NULL && $var !== FALSE && $var !== "");
}



function deviation_improve_y($y_value)
{
    return $y_value+($y_value*0.65);
}




function deviation_improve_x($x_value)
{
    if($x_value <= 160)
        return 0;
    else
        return ($x_value-160)*1.6;
}




function ScormId($content_id){

    $user_id = sprintf("%'.07d", auth()->user()->id);
    $content_id1 = sprintf("%'.07d", $content_id);
    $SCOInstanceID = (1).$user_id.(2).$content_id1;

    return $SCOInstanceID;
}
