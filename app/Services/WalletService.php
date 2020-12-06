<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Http\Resources\WalletStoreResource;
use App\Http\Resources\RowNotFindResource;
use App\Http\Resources\WalletTransactionResource;

class WalletService
{

    public function walletList(): object
    {
        return Wallet::where('created_by', Auth::user()->id)->get();
    }

    public function walletStore($request)
    {
        if(Wallet::where('created_by', Auth::user()->id)->count() < \Config::get('constants.MAX_WALLET_COUNT')){
            $wallet = Wallet::create(
                $request->only('name'),
                ['amount' => \Config::get('constants.START_AMOUNT')]
            );
            return new WalletStoreResource($wallet);
        }
    }

    public function walletUpdate($request, $wallet): void
    {
        $wallet = Wallet::firstOrFail($wallet);
        $wallet->save($request->only('name'));
    }

    public function walletDestroy($wallet): void
    {
        if($wallet->created_by == Auth::user()->id){
            $wallet->delete();
        }
    }

    public function TransactionsByWallet($id): object
    {
        $transactions = Transaction::where('wallet_id', $id)->where('created_by', Auth::user()->id)->get();
        return new WalletTransactionResource($transactions);

    }


}