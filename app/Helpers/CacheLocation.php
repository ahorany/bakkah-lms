<?php

namespace App\Helpers;
use App\Constant;

class CacheLocation {

    static function getLocationInfoByIp(){
        try
        {
            $ip = $_SERVER['REMOTE_ADDR'];
            $details = json_decode(file_get_contents("https://pro.ip-api.com/json/".$ip."?key=xXrFRE10FQhjWeT"));
            if($details === false) {
                return 'SA';
            }
            return $details->countryCode;
        }catch(\Exception $ee){
            return 'SA';
        }
    }

    static function Run(){

        $CURRENCY_PER_COUNTRY = env('CURRENCY_PER_COUNTRY');
        if(session()->has('change-currency')){
            if(session()->get('change-currency')==1){
                $CURRENCY_PER_COUNTRY = false;
            }
        }
        if($CURRENCY_PER_COUNTRY==false)
        {
            $deafult_countryCode = 'SA';
            session()->put('countryCode', $deafult_countryCode);
            session()->put('coinID', 334);
            session()->put('countryID', 58);
            session()->put('coinPrice', 1);
        }
        else
        {
            self::SetCoinID();
        }
    }

    private static function SetCoinID(){
        if(!session()->has('coinID'))
        {
            $deafult_countryCode = self::getLocationInfoByIp();
            $coin_price = ($deafult_countryCode!='SA') ? USD_PRICE : 1;

            session()->put('countryCode', $deafult_countryCode);

            self::SetCountryID($deafult_countryCode);

            if($deafult_countryCode=='SA')
            {
                session()->put('coinID', 334);
            }
            else
            {
                session()->put('coinID', 335);
            }
            session()->put('coinPrice', $coin_price);
        }
    }

    private static function SetCountryID($deafult_countryCode='SA'){
        $country = Constant::where('xero_code', $deafult_countryCode)->first();
        if(is_null($country)){
            session()->put('countryID', 58);
        }
        $country_id=$country->id??58;
        session()->put('countryID', $country_id);
    }
}
