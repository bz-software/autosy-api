<?php

namespace App\Http\Controllers;

use App\DTOs\AppointmentDTO;
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
}
