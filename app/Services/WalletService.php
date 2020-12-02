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
    public function walletStore($request, $user)
    {
        $wallet = new Wallet();
        $wallet->name   = $request->name;
        $wallet->amount = \Config::get('constants.START_AMOUNT');

        if($user->wallets()->save($wallet)){
            return new WalletStoreResource($wallet);
        }else{
            return response()->json([
                'message' => 'The wallet could not be saved'
            ], 400);
        }

    }

    public function walletUpdate($request, $wallet)
    {
        if($this->user->wallets()->save($request->all())){
            return new WalletStoreResource($wallet);
        }else{
            return new RowNotFindResource($wallet);
        }
    }

    public function walletDestroy($wallet)
    {
        if($wallet->delete()){
            return new WalletStoreResource($wallet);
        }else{
            return new RowNotFindResource($wallet);
        }
    }

    public function TransactionsByWallet($id)
    {
        $transactions = Transaction::where('wallet_id', $id)->where('created_by', Auth::user()->id)->get()->toArray();
        if(!empty($transactions)){
            return new WalletTransactionResource($transactions);
        }else{
            return new RowNotFindResource($transactions);
        }

    }


}