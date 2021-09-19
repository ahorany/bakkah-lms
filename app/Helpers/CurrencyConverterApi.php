<?php
namespace App\Helpers;

class CurrencyConverterApi {

    public function GetContents($q='USD_SAR')
    {
        // $file_get_contents = file_get_contents('https://free.currconv.com/api/v7/convert?q='.$q.'&compact=ultra&apiKey=e823bec134d10c3eabd0');
        // $file_get_contents = json_decode($file_get_contents);

        $coin_price = 3.8;
        return $coin_price;
        if(isset($file_get_contents->USD_SAR))
            $coin_price = round($file_get_contents->USD_SAR, 4);
        return $coin_price;
    }
}
