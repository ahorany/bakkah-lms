<?php

define('ADMIN', 'admin');
define('FRONT', 'front');
define('PAGINATE', 15);
define('SUBHOUR', 2);//session will expire before 2 hours
define('FACEBOOK', 'https://www.facebook.com/pages/Bakkah-Inc/178267408890847');
define('TWITTER', 'https://twitter.com/BakkahInc');
define('INSTAGRAM', 'https://www.instagram.com/bakkahinc/');
define('LINKEDIN', 'https://www.linkedin.com/company/bakkah-inc-?trk=top_nav_home');
define('YEAR', date('Y'));
define('VAT', 15);
define('green_attendance_rate', 15);
define('USD_PRICE', 3.75);
define('ZOOM_COST', 86);


use App\User;
use Carbon\Carbon;
use App\Models\Training\Cart;
use App\Models\Training\CartMaster;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

function checkUserIsTrainee(){
    if(\auth()->user()->roles()->first()->id == 2 ){
        return true;
    }
    return false;
}



function CustomAsset($url){
    return asset(env('LIVE_ASSET').$url);
}

function DateTimeNow(){
    // $mytime = Carbon::now();
    // $DateNow = $mytime->toDateTimeString();
    return Carbon::now()->format('Y-m-d H:i:s');
}

function DateTimeNowAddHours(){
    return Carbon::now()->addHours(SUBHOUR)->format('Y-m-d H:i:s');
}

function NumberFormat($number){

    $number = str_replace( ',', '', $number );
    return round($number, 2);
//    return number_format($number, 2);
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

function GetCoinId(){
    // if(auth()->check()){
    //     if(auth()->user()->id==1){
    //         session()->put('coinID', 334);
    //     }
    // }
    // session()->put('coinID', 335);
    return session()->has('coinID')?session()->get('coinID'):334;
}

function SetSARForOurEmployee(){

}

function GetCoinPrice(){
    return session()->has('coinPrice')?session()->get('coinPrice'):1;
}

function GetcountryCode(){
    return session()->has('countryCode')?session()->get('countryCode'):'SA';
}

function GetcountryID(){
    return session()->has('countryID')?session()->get('countryID'):58;
}

function GetValueByLang($title=null){
	$lang = app()->getLocale();
	return json_decode($title)->$lang??$title;
}

function GetValueByLangNullable($title=null){
	$lang = app()->getLocale();
	return json_decode($title)->$lang??null;
}

function GetBasicId($title=null){
    return json_decode($title)->basic??$title;
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

function GetGroupSlug($default='learning'){
	return get('group_slug', $default);
}

function GetTrash(){
	return get('trash', null);
}

function GetCartTotalPriceAfterVat($cart_master_id) {
    $total = Cart::where('master_id', $cart_master_id)
        ->where('payment_status', '!=', 68)
        ->whereNull('trashed_status')
        ->where('coin_id', session('coinID'))
        ->sum('total_after_vat');

    // if(auth()->check()) {
    //     $user = User::where('email', auth()->user()->email)->first();
    //     $balance = $user->balance;

    //     if($total == $balance) {
    //         $retrieved_value = $total;
    //         $user_balance = 0;
    //     }elseif($total > $balance) {
    //         $retrieved_value = $balance;
    //         $user_balance = 0;
    //     }else {
    //         $retrieved_value = $total;
    //         $user_balance = $balance - $total;
    //     }

    //     CartMaster::where('id', $cart_master_id)->whereNull('retrieved_code')->update([
    //         'retrieved_value' => $retrieved_value,
    //         'retrieved_code' => $user->retrieved_code,
    //     ]);
    //     User::where('email', auth()->user()->email)->update([
    //         'balance' => $user_balance
    //     ]);

    //     // dd($balance);
    // }

    // $retrieved_value = CartMaster::where('id', $cart_master_id)
    //     ->where('payment_status', '!=', 68)
    //     ->whereNull('trashed_status')
    //     ->where('coin_id', session('coinID'))
    //     ->sum('retrieved_value');

        // dd($total - $retrieved_value);

    // return $total - $retrieved_value;
    return $total;
}
function array_without_empty($var){
    return ($var !== NULL && $var !== FALSE && $var !== "");
}
