<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wallets = $this->user->wallets()->get(['id', 'name', 'amount', 'created_by']);

        return response()->json([
            'wallets' => $wallets->toArray(),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        if($this->user->wallets()->count() < 10 ){
            $validator = Validator::make($request->all(), [
                'name' => 'required|string'
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 400);
            }

            $wallet = new Wallet();
            $wallet->name   = $request->name;
            $wallet->amount = 100000000;

            if($this->user->wallets()->save($wallet)){
                return response()->json([
                    'status' => true,
                    'wallet' => $wallet
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'The wallet could not be saved'
                ], 400);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'You may save only 10 wallets'
            ], 400);
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
         return response()->json([
            'wallet' => $wallet->toArray(),
            'convertToUSD' => convertTo($wallet->amount, "USD"),
            'convertToBTC' => convertToBtc($wallet->amount, "USD")
        ], 200);   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wallet $wallet)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $wallet->name   = $request->title;
        $wallet->amount = $request->amount;

        if($this->user->wallets()->save($wallet)){
            return response()->json([
                'status' => true,
                'wallet' => $wallet
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'The wallet could not be updated'
            ], 400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        if($wallet->delete()){
            return response()->json([
                'status' => true,
                'wallet' => $wallet
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'The wallet could not be deletedx'
            ], 400);
        }
    }


    public function transactions($id)
    {
        $transactions = Transaction::where('wallet_id', $id);
        return response()->json([
            'message' => 'Transactions by wallet',
            'transactions' => $transactions
        ]);
    }

    public function guard()
    {
        return Auth::guard();
    }


}
