<?php

namespace App\Http\Controllers;

use App\DTOs\AppointmentDTO;
use App\DTOs\ServiceDTO;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Resources\Service\ServiceResource;
use App\Services\ServiceService as Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(private Service $service){}

    public function index(Request $request){
        return ServiceResource::collection(
            $this->service->list(
                $request->user()->workshop->id,
            )
        );      
    }

    public function store(StoreServiceRequest $request){
        return new ServiceResource(
            $this->service->store(
                $request->user()->workshop->id,
                ServiceDTO::fromRequest($request)
            )
        );      
    }

    public function update(StoreServiceRequest $request){
        return new ServiceResource(
            $this->service->update(
                $request->user()->workshop->id,
                $request->route('id'),
                ServiceDTO::fromRequest($request)
            )
        );
    }

    public function destroy(Request $request){
        return $this->service->destroy(
            $request->user()->workshop->id,
            $request->route('id')
        );
    }
}
