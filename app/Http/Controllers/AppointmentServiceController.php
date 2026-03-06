<?php

namespace App\Http\Controllers;

use App\DTOs\AppointmentServiceDTO;
use App\Http\Requests\Appointment\StoreAppointmentServiceRequest;
use App\Http\Resources\AppointmentService\AppointmentServiceResource;
use App\Services\AppointmentServiceService;
use Illuminate\Http\Request;

class AppointmentServiceController extends Controller
{
    public function __construct(private AppointmentServiceService $service){}

    public function store(StoreAppointmentServiceRequest $request){
        return new AppointmentServiceResource(
            $this->service->store(
                $request->route('appointment'),
                $request->user()->workshop->id,
                AppointmentServiceDTO::fromRequest($request)
            )
        );
    }

    public function destroy(Request $request){
        return $this->service->destroy(
            $request->route('service'),
            $request->route('appointment'),
            $request->user()->workshop->id
        );
    }
}
