<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
    [
        'middleware' => 'api',
        'namespace'  => 'App\Http\Controllers',
        'prefix'     => 'auth',
    ],
    function ($router) {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::get('profile', 'AuthController@profile');
        Route::post('refresh', 'AuthController@refresh');
    }
);

Route::group(
    [
        'middleware' => 'api',
        'namespace'  => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::get('transactions', 'TransactionController@index');
        Route::post('transactions', 'TransactionController@store');
        Route::resource('wallets', 'WalletController');
        Route::get('wallets/{wallet}/transactions', 'WalletController@transactions');
        Route::get('fee_list', 'FeeController@index');
        Route::post('save_btc_rate', 'ConvertionController@saveBtcRate');
        Route::post('test_form', 'FeeController@test_form');
        Route::post('users', 'UserController@store');


    }
);