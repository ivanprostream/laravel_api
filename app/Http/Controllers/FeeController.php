<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fee_list = Fee::all();

        return response()->json([
            'message' => 'Fee list',
            'fee_list' => $fee_list
        ], 200);
    }

    
}
