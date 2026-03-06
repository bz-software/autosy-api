<?php

namespace App\Http\Controllers;

use App\DTOs\VehicleDTO;
use App\Http\Requests\Vehicle\StoreVehicleRequest;
use App\Http\Requests\Vehicle\UpdateVehicleRequest;
use App\Http\Resources\Vehicle\VehicleResource;
use App\Services\VehicleService as Service;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function __construct(private Service $service){}

    public function byCustomer(Request $request){
        return VehicleResource::collection(
            $this->service->getByCustomer(
                $request->route('customer'),    
                $request->user()->workshop->id
            )
        );
    }

    public function store(StoreVehicleRequest $request){
        return new VehicleResource($this->service->store(
            VehicleDTO::fromRequest($request), 
            $request->user()->workshop->id)
        );
    }

    public function update(UpdateVehicleRequest $request){
        return new VehicleResource($this->service->update(
            $request->route('id'),
            VehicleDTO::fromRequest($request)
        ));
    }
}
