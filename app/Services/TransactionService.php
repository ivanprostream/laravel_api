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
    public function transactionStore($request, $user)
    {

        DB::beginTransaction();

        try {
            $typeTransfer = 0;
            $fromQuery = Wallet::where('id', $request['wallet_from'])->lockForUpdate()->first();
            $toQuery = Wallet::where('id', $request['wallet_to'])->lockForUpdate()->first();

            if($fromQuery->created_by == $toQuery->created_by){
                $typeTransfer = 1;
                $amount = $request['summ'];
            }else{
                $typeTransfer = 2;
                $amount = $this->calculateSummWithFee($request['summ']);
            }

            if($amount > $fromQuery->amount){
                throw new InsufficientBalanceException();
            }

            $toAccount = $toQuery;
            $toAccount->amount += $amount;
            $toAccount->save();

            $fromAccount = $fromQuery;
            $fromAccount->amount -= $amount;
            $fromAccount->save();

            $transaction = new Transaction();
            $transaction->created_by = $fromQuery->created_by;
            $transaction->wallet_id = $toQuery->id;
            $transaction->amount = $amount;
            $transaction->save();

            if($typeTransfer == 2){
                $fee = new Fee();
                $fee->transaction_id = $transaction->id;
                $fee->amount = $this->getFeeFromSumm($amount);
                $fee->save();
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }

    }

    public function calculateSummWithFee($amount)
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

}