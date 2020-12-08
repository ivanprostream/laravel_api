<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResourceCollection;
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
    public function index(): TransactionResourceCollection
    {
        $transactions = $this->transactionService->transactionList();
        return (new TransactionResourceCollection($transactions))->additional(['message' => 'Transactions list!']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request): TransactionStoreResource
    {
        $transaction = $this->transactionService->transactionStore($request);
        return (new TransactionStoreResource($transaction))->additional(['message' => 'Transaction created!']);
    }

    public function guard()
    {
        return Auth::guard();
    }


}
