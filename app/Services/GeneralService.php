<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Setting;


class GeneralService
{
    /**
     * Convert amount 
     * CRON and save in Setting->conversion
     *
     * @param $amount
     * @param $currency
     * @return string
     */

    function convertTo($amount, $currency): string
    {
        $setting = Setting::first();
        $convertTo = $amount * $setting->conversion / 100000000;
        return $amount.' Satoshi = '.$convertTo.' '.$currency;
    }

    function convertToBtc($amount, $currency): string
    {
        $convertToBtc = $amount / 100000000;
        return $amount.' Satoshi = '.$convertToBtc.' Bitcoins';
    }
    
}