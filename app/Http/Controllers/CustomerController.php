<?php

namespace App\Http\Controllers;

use App\DTOs\CustomerDTO;
use App\Http\Requests\SearchCustomerRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Services\CustomerService as Service;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class CustomerController extends Controller
{
    public function __construct(private Service $service){}

    public function store(StoreCustomerRequest $request){
        return new CustomerResource($this->service->store(
            CustomerDTO::fromRequest($request),
            $request->user()->workshop->id
        ));
    }

    public function search(SearchCustomerRequest $request){
        return CustomerResource::collection($this->service->search(
            CustomerDTO::fromRequest($request),
            $request->user()->workshop->id
        ));
    }

    public function update(UpdateCustomerRequest $request) {
        return new CustomerResource(
            $this->service->update(
                $request->route('id'),
                $request->user()->workshop->id,
                CustomerDTO::fromRequest($request)
            )
        );
    }   
}
