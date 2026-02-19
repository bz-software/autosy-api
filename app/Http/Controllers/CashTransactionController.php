<?php

namespace App\Http\Controllers;

use App\DTOs\CashTransactionDTO;
use App\DTOs\CashTransaction\SearchCashTransactionDTO;
use App\Http\Requests\CashTransaction\StoreCashTransactionRequest;
use App\Http\Requests\CashTransaction\SearchCashTransactionRequest;
use App\Http\Resources\CashTransaction\CashTransactionResource;
use App\Services\CashTransactionService;
use Illuminate\Http\Request;

// use Illuminate\Http\Request;

class CashTransactionController extends Controller
{
    public function __construct(private CashTransactionService $service) {}

    public function index(SearchCashTransactionRequest $request){
        return CashTransactionResource::collection(
            $this->service->list(
                SearchCashTransactionDTO::fromRequest($request),
                $request->user()->workshop->id
            )
        );
    }

    public function store(StoreCashTransactionRequest $request) {
        return new CashTransactionResource(
            $this->service->store(
                CashTransactionDTO::fromRequest($request),
                $request->user()->id,
                $request->user()->workshop->id
            )
        );
    }
}
