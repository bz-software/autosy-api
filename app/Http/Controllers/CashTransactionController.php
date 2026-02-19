<?php

namespace App\Http\Controllers;

use App\DTOs\CashTransactionDTO;
use App\Http\Requests\CashTransaction\StoreCashTransactionRequest;
use App\Http\Resources\CashTransaction\CashTransactionResource;
use App\Services\CashTransactionService;
use Illuminate\Http\Request;

// use Illuminate\Http\Request;

class CashTransactionController extends Controller
{
    public function __construct(private CashTransactionService $service) {}

    public function index(Request $request){
        return CashTransactionResource::collection(
            $this->service->list(
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
