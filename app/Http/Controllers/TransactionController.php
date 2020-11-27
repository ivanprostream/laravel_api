<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Setting;
use App\Models\Wallet;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
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
        $transactions = Transaction::where('created_by', $this->user->id)->get();

        return response()->json([
            'message' => 'Transactions list by user',
            'transactions' => $transactions
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
        $validator = Validator::make($request->all(), [
            'amount'      => 'required|numeric',
            'wallet_from' => 'required|numeric',
            'wallet_to'   => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $requestData = $request->all();

        $walletFrom  = Wallet::where('id', $requestData['wallet_from'])->first(); 
        $walletTo    = Wallet::where('id', $requestData['wallet_to'])->first();

        $amount = $walletFrom->amount;

        if(!empty($walletFrom) && !empty($walletTo) && $requestData['amount'] <= calculateSummWithFee($amount)) {
            $this->createTransaction($walletFrom, $walletTo, $requestData['amount']);
        }else{
            return response()->json([
                'message' => 'Incorrect wallets or not enough money for transaction'
            ], 400);
        }

    }

    public function createTransaction($from, $to, $amount)
    {
        if($from->created_by == $this->user->id && $from->created_by == $to->created_by)
        {
            $this->createTransactionWithoutFee($from, $to, $amount);  

        }elseif($from->created_by == $this->user->id && $from->created_by != $to->created_by){

            $this->createTransactionWithFee($from, $to, $amount);
        }
    }

    public function createTransactionWithoutFee($from, $to, $amount)
    {
        $totalSumm = $amount;

        DB::beginTransaction();
        try {

            $walletFrom = Wallet::findOrFail($from->id);

            $summFrom = $walletFrom->amount;

            $walletFrom->amount = $summFrom - $amount;
            $walletFrom->save();

            $walletTo = Wallet::findOrFail($to->id);

            $summTo = $walletTo->amount;

            $walletTo->amount = $summTo + $amount;
            $walletTo->save();

            $transaction = new Transaction();
            $transaction->created_by = $from->created_by;
            $transaction->wallet_id = $to->id;
            $transaction->amount = $amount;
            $transaction->save();

            DB::commit();

            return response()->json([
                'message' => 'Transaction done',
                'transaction' => $transaction
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function createTransactionWithFee($from, $to, $amount)
    {
        $totalSumm = $amount;

        $summWithFee = calculateSummWithFee($amount);

        $fee = $summWithFee - $amount;

        DB::beginTransaction();
        try {

            $walletFrom = Wallet::findOrFail($from->id);

            $summFrom = $walletFrom->amount;

            $walletFrom->amount = $summFrom - $summWithFee;
            $walletFrom->save();

            $walletTo = Wallet::findOrFail($to->id);

            $summTo = $walletTo->amount;

            $walletTo->amount = $summTo + $totalSumm;
            $walletTo->save();

            $transaction = new Transaction();
            $transaction->created_by = $from->created_by;
            $transaction->wallet_id = $to->id;
            $transaction->amount = $totalSumm;
            $transaction->save();

            $fee = new Fee();
            $fee->transaction_id = $transaction->id;
            $fee->amount = getFeeFromSumm($amount);
            $fee->save();

            DB::commit();

            return response()->json([
                'message' => 'Transaction done',
                'transaction' => $transaction
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function guard()
    {
        return Auth::guard();
    }


}
