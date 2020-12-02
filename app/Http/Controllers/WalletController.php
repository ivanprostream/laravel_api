<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\WalletFormRequest;
use App\Http\Resources\WalletListResource;
use App\Http\Resources\WalletShowResource;
use App\Services\WalletService;

class WalletController extends Controller
{
    protected $user;
    private $walletService;


    public function __construct( walletService $walletService ) 
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();

        $this->walletService = $walletService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wallets = $this->user->wallets()->get(['id', 'name', 'amount']);
        return new WalletListResource();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Requests\WalletRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WalletFormRequest  $request)
    {   
        $walletStore =  $this->walletService->walletStore($request, $this->user);
        return $walletStore;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
        return new WalletShowResource($wallet);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(WalletItemRequest $request, Wallet $wallet)
    {
        $walletUpdate = $this->walletService->walletUpdate($request, $wallet);
        return $walletUpdate;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        $walletDestroy = $this->walletService->walletDestroy($wallet->delete());
        return $walletDestroy;
    }


    public function transactions($id)
    {
        $walletTransactions = $this->walletService->TransactionsByWallet($id);
        return $walletTransactions;
    }

    public function guard()
    {
        return Auth::guard();
    }


}
