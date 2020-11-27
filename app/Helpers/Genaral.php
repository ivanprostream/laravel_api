<?php

use App\Models\Setting;

/**
 * Convert amount 
 * CRON and save in Setting->conversion
 *
 * @param $amount
 * @param $currency
 * @return string
 */

function convertTo($amount, $currency)
{
    $setting = Setting::first();
    $convertTo = $amount * $setting->conversion / 100000000;
    return $amount.' Satoshi = '.$convertTo.' '.$currency;
}

function convertToBtc($amount, $currency)
{
    $convertToBtc = $amount / 100000000;
    return $amount.' Satoshi = '.$convertToBtc.' Bitcoins';
}

/**
 * Calculate Summ with fee 
 *
 * @param $amount
 * @return string
 */

function calculateSummWithFee($amount)
{
    $fee = Setting::first();
    $FeeFromSumm = $amount * $fee->fee / 100;

    return $amount + $FeeFromSumm;
}

/**
 * get Fee from summ
 *
 * @param $amount
 * @return string
 */

function getFeeFromSumm($amount)
{
    $fee = Setting::first();
    $FeeFromSumm = $amount * $fee->fee / 100;

    return $FeeFromSumm;
}




