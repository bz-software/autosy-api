<?php

namespace App\Http\Controllers;

use App\DTOs\CustomerDTO;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Services\CustomerService as Service;

class CustomerController extends Controller
{
    public function __construct(private Service $service){}

    public function store(StoreCustomerRequest $request){
        return new CustomerResource($this->service->store(
            CustomerDTO::fromRequest($request),
            $request->user()->workshop->id
        ));
    }
}
