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

    public function store(Request $request){
        return new AppointmentWithDetailsResource( 
            $this->service->store(
                $request->user()->workshop->id,
                $request
            )
        );
    }

    public function startDiagnosis(Request $request){
        return new AppointmentWithDetailsResource( 
            $this->service->startDiagnosis(
                $request->route('id'),
                $request->user()->workshop->id
            )
        );
    }

    public function requestApproval(Request $request){
        return new AppointmentWithDetailsResource( 
            $this->service->requestApproval(
                $request->route('id'),
                $request->user()->workshop->id
            )
        );
    }

    public function approveDiagnosis(Request $request){
        return new AppointmentWithDetailsResource( 
            $this->service->approveDiagnosis(
                $request->route('id'),
                $request->user()->workshop->id
            )
        );
    }

    public function approval(Request $request){
        return new AppointmentWithDetailsResource( 
            $this->service->approval(
                $request->route('id')
            )
        );
    }

    public function pdfDiagnosis(Request $request){
        return $this->service->approvalPdf(
            $request->route('id')
        );
    }

    public function finalize(Request $request){
        return new AppointmentWithDetailsResource( 
            $this->service->finalize(
                $request->route('id'),
                $request->user()->workshop->id
            )
        );
    }
}
