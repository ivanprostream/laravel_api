<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\Transaction;

class WalletService
{

    public function walletList(): object
    {
        return Wallet::where('created_by', Auth::user()->id)->get();
    }

    public function walletStore($request): object
    {
        if(Wallet::where('created_by', Auth::user()->id)->count() < \Config::get('constants.MAX_WALLET_COUNT')){
            $wallet = Wallet::create(array(
                'name'       => $request->name,
                'created_by' => Auth::user()->id,
                'amount'     => \Config::get('constants.START_AMOUNT')
            ));
            return $wallet;
        }
    }

    public function TransactionsByWallet(int $id): object
    {
        return Transaction::where('wallet_id', $id)->where('created_by', Auth::user()->id)->get();
    }


}