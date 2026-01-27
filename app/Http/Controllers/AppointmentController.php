<?php

namespace App\Http\Controllers;

use App\DTOs\AppointmentDTO;
use App\Services\AppointmentService as Service;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\Appointment\AppointmentWithDetailsResource;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(private Service $service){}

    public function index(Request $request){
        return AppointmentWithDetailsResource::collection(
            $this->service->list(
                $request->user()->workshop->id,
            )
        );
    }

    public function store(StoreAppointmentRequest $request){
        return new AppointmentWithDetailsResource( 
            $this->service->store(
                $request->user()->workshop->id,
                AppointmentDTO::fromRequest($request)
            )
        );
    }
}
