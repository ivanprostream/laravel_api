<?php

namespace App\Http\Controllers;

use App\Models\Fee;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\WalletStoreRequest;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : object
    {
        $fee_list = Fee::all();

        return response()->json([
            'message' => 'Fee list',
            'fee_list' => $fee_list
        ], 200);
    }
    
}
