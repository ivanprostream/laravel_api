<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\WalletFormRequest;
use App\Http\Resources\WalletListResourceCollection;
use App\Http\Resources\WalletShowResource;
use App\Http\Resources\WalletStoreResource;
use App\Http\Resources\TransactionResourceCollection;
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
    public function index(): WalletListResourceCollection
    {
        $wallets = $this->walletService->walletList();
        return new WalletListResourceCollection($wallets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Requests\WalletRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WalletFormRequest $request) : WalletStoreResource
    {   
        $wallet = $this->walletService->walletStore($request);
        return new WalletStoreResource($wallet);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet): WalletShowResource
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
    public function update(WalletItemRequest $request, Wallet $wallet): void
    {
        $this->walletService->walletUpdate($request, $wallet);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet): void
    {
        $this->walletService->walletDestroy($wallet);
    }


    public function transactions(int $id): TransactionResourceCollection
    {
        $transactions = $this->walletService->TransactionsByWallet($id);
        return new TransactionResourceCollection($transactions);
    }

    public function guard()
    {
        return Auth::guard();
    }


}
