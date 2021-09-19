<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Cache;
use App\Helpers\CurrencyConverterApi;
// use Stevebauman\Location\Facades\Location;
use App\Constant;

class CacheLocation {

    // static function getLocationInfoByIp(){
    //     $ip = $_SERVER['REMOTE_ADDR'];
    //     $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
    //     // echo $details->city; // -> "Mountain View"
    //     // dump($details);
    //     if($details === false) {
    //         return 'SA';
    //     }
    //     return $details->country;
    // }
    static function getLocationInfoByIp(){

        try{
            $ip = $_SERVER['REMOTE_ADDR'];
            // return 'SA';
            // $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
            // $details = json_decode(file_get_contents("http://ip-api.com/json/".$ip));
            $details = json_decode(file_get_contents("https://pro.ip-api.com/json/".$ip."?key=xXrFRE10FQhjWeT"));
            // echo $details->city; // -> "Mountain View"
            // dump($details);
            if($details === false) {
                return 'SA';
            }
            return $details->countryCode;
        }catch(\Exception $ee){
            return 'SA';
        }
    }

    static function Run(){

        // $url = "http://www.geoplugin.net/json.gp?ip='".request()->ip()."'";
        // $position = Location::get(request()->ip());
        // dd(request()->ip());
        // dd(file_get_contents("http://www.geoplugin.net/json.gp?ip=".request()->ip()));
        // $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".request()->ip()));
        // dd($ip_data);

        // $deafult_countryCode = 'SA';
        // dd($position);
        // $deafult_countryCode = 'SA';
        $cashPeriod = 3600;
        if(env('CURRENCY_PER_COUNTRY')==false){
            $deafult_countryCode = 'SA';
            Cache::put('countryCode_'.request()->ip(), $deafult_countryCode, $cashPeriod);
            Cache::put('coinID_'.request()->ip(), 334, $cashPeriod);
            Cache::put('countryID_'.request()->ip(), 58, $cashPeriod);
            Cache::put('coinPrice', 1, $cashPeriod);
            Cache::put('coinPrice_'.request()->ip(), 1, $cashPeriod);
        }else{
            // Cache::forget(request()->ip());
            // Cache::forget('countryCode_'.request()->ip());
            // Cache::forget('coinID_'.request()->ip());
            // Cache::forget('countryID_'.request()->ip());
            // Cache::forget('coinPrice');
            // Cache::forget('coinPrice_'.request()->ip());
            if(!Cache::has(request()->ip())){

                // $position = Location::get(request()->ip());
                $deafult_countryCode = self::getLocationInfoByIp();
                $coin_price = ($deafult_countryCode!='SA') ? USD_PRICE : 1;
                // $countryCode=$position->countryCode??$deafult_countryCode;
                // $countryCode = 'PS';
                Cache::put('countryCode_'.request()->ip(), $deafult_countryCode, $cashPeriod);
                if($deafult_countryCode=='SA'){
                    Cache::put('coinID_'.request()->ip(), 334, $cashPeriod);
                }
                else{
                    Cache::put('coinID_'.request()->ip(), 335, $cashPeriod);
                }
                Cache::put(request()->ip(), request()->ip(), $cashPeriod);
            }
            if(!Cache::has('countryID_'.request()->ip())){
                $country = Constant::where('xero_code', Cache::get('countryCode_'.request()->ip()))->first();
                if(is_null($country)){
                    Cache::put('countryID_'.request()->ip(), 58, $cashPeriod);
                }
                $country_id=$country->id??58;
                Cache::put('countryID_'.request()->ip(), $country_id, $cashPeriod);
            }
            if(Cache::has('coinID_'.request()->ip())){

                $deafult_countryCode = Cache::get('countryCode_'.request()->ip());
                $coin_price = ($deafult_countryCode!='SA') ? USD_PRICE : 1;
                if(!Cache::has('coinPrice')){

                    // $CurrencyConverterApi = new CurrencyConverterApi();
                    // $coin_price = $CurrencyConverterApi->GetContents('USD_SAR');
                    Cache::put('coinPrice', $coin_price, 3600); // 60 Minutes
                }

                if(Cache::get('coinID_'.request()->ip())==334){
                    Cache::put('coinPrice_'.request()->ip(), 1, $cashPeriod);
                }
                else{
                    Cache::put('coinPrice_'.request()->ip(), Cache::get('coinPrice'), $cashPeriod);
                }
            }
        }
    }
}
