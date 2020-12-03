<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\TransactionStoreResource;
use App\Services\TransactionService;

class TransactionController extends Controller
{

    private $transactionService;

    public function __construct( transactionService $transactionService )
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();

        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = $this->user->transactions()->get(['id', 'created_by', 'wallet_id', 'amount', 'created_by'])->toArray();;
        if(!empty($transactions)){
            return new TransactionResource($transactions);
        }else{
            return new RowNotFindResource($transactions);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request)
    {
        $transactionStore =  $this->transactionService->transactionStore($request, $this->user->id);
        return new TransactionStoreResource($transactionStore);
    }

    public function guard()
    {
        return Auth::guard();
    }


}
