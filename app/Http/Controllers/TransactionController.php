<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransactionStoreRequest;
use App\Http\Resources\TransactionResource;

class TransactionController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
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
    // public function store(TransactionStoreRequest $request)
    // {

    //     $transactionStore =  $this->transactionService->transactionStore($request, $this->user->id);
    //     return $transactionStore;
    // }

    public function guard()
    {
        return Auth::guard();
    }


}
