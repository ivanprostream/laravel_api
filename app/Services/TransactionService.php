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

    public function transactionList(): object
    {
        return Transaction::orderBy('created_at','desc')->paginate(\Config::get('constants.PAGINATION_PER_PAGE'));
    }

    public function transactionStore($request): object
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
            
            $fromAccount = $fromQuery;
            $fromAccount->amount -= $amount;
            $fromAccount->save();

            $transaction = Transaction::create([
                'created_by' => Auth::user()->id,
                'wallet_id'  => $request['wallet_to'],
                'amount'     => $amount,
            ]);

            if($transferFee == true){
                Fee::create([
                    'transaction_id' => $transaction->id,
                    'amount' => $amount - $request['summ'],
                ]);
            }

            DB::commit();

            return $transaction;

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }

    }

    public function calculateSummWithFee(int $amount) : int
    {
        $fee = Setting::first();
        $FeeFromSumm = $amount * $fee->fee / 100;

        return $amount + $FeeFromSumm;
    }

}