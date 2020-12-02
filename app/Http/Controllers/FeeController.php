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
    public function index()
    {
        $fee_list = Fee::all();

        return response()->json([
            'message' => 'Fee list',
            'fee_list' => $fee_list
        ], 200);
    }


    public function test_form(WalletStoreRequest $request)
    {
        //print_r($request->all());
        $validated = $request->validated();
        print_r($validated);
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|max:50',
        //     'message' => 'required|string|max:50'
        // ]);

        // if($validator->fails()){
        //     return response()->json([
        //         'status' => false,
        //         'errors' => $validator->errors()
        //     ], 400);
        // }

        // return response()->json([
        //     'message' => 'Test Form'
        // ], 200);
    }

    
}
