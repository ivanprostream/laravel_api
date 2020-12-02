<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Setting;
use App\Models\Wallet;
use App\Models\Fee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Services\GeneralService;


class TransactionService
{
    public function transactionStore($request, $user)
    {
        $walletFrom  = Wallet::where('id', $request['wallet_from'])->where('created_by', $user)->firstOrFail();
        $walletTo    = Wallet::where('id', $request['wallet_to'])->firstOrFail();

        $amount = $walletFrom->amount;
        $calculateSummWithFee = $this->walletService->calculateSummWithFee($amount);

        $totalSumm = $amount;

        DB::beginTransaction();
        try {

            if($request['amount'] <= $calculateSummWithFee) {

                $transaction = new Transaction();
                $transaction->created_by = $walletFrom->created_by;
                $transaction->wallet_id = $walletTo->id;
                $transaction->amount = $amount;
                $transaction->save();

                if($walletFrom->id == $walletTo->id){
                    $walletFrom = Wallet::findOrFail($walletFrom->id);

                    $summFrom = $walletFrom->amount;

                    $walletFrom->amount = $summFrom - $amount;
                    $walletFrom->save();

                    $walletTo = Wallet::findOrFail($walletTo->id);

                    $summTo = $walletTo->amount;

                    $walletTo->amount = $summTo + $amount;
                    $walletTo->save();
                }else{
                    $walletFrom = Wallet::findOrFail($walletFrom->id);

                    $summFrom = $walletFrom->amount;

                    $walletFrom->amount = $summFrom - $summWithFee;
                    $walletFrom->save();

                    $walletTo = Wallet::findOrFail($to->id);

                    $summTo = $walletTo->amount;

                    $walletTo->amount = $summTo + $totalSumm;
                    $walletTo->save();

                    $fee = new Fee();
                    $fee->transaction_id = $transaction->id;
                    $fee->amount = getFeeFromSumm($amount);
                    $fee->save();
                }

                return response()->json([
                    'message' => 'Transaction done',
                    'transaction' => $transaction
                ], 200);

                DB::commit();

            }

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }    

}