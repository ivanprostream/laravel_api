<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Setting;
use App\Models\Wallet;
use App\Models\Fee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\InsufficientBalanceException;

class TransactionService
{
    public function transactionStore($request, $user): void
    {

        DB::beginTransaction();

        try {
            $transferFee = false;
            $fromQuery = Wallet::where('id', $request['wallet_from'])->where('created_by', Auth::user()->id)->lockForUpdate()->firstOrFail();
            $toQuery = Wallet::where('id', $request['wallet_to'])->first();

            if($fromQuery->created_by == $toQuery->created_by){
                $transferFee = false;
                $amount = $request['summ'];
            }else{
                $transferFee = true;
                $amount = $this->calculateSummWithFee($request['summ']);
            }

            if($amount > $fromQuery->amount){
                throw new InsufficientBalanceException();
            }

            $toQuery->increment('amount', $amount);
            $fromQuery->decrement('amount' , $amount); 

            $transaction = new Transaction();
            $transaction->created_by = $fromQuery->created_by;
            $transaction->wallet_id = $toQuery->id;
            $transaction->amount = $amount;
            $transaction->save();

            if($transferFee == true){
                Fee::create(
                    array_merge(
                        $request->only('transaction_id'),
                        ['amount' => $this->getFeeFromSumm($amount)]
                    )
                );
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }

    }

    public function calculateSummWithFee($amount) : int
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

    function getFeeFromSumm($amount) : int
    {
        $fee = Setting::first();
        $FeeFromSumm = $amount * $fee->fee / 100;

        return $FeeFromSumm;
    }

}