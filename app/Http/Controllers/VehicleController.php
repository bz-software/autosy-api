<?php

namespace App\Http\Controllers;

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
}
