<?php

namespace App\Http\Controllers;

use App\Services\SubscribeWorkshopService as Service;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(private Service $service){}

    public function index(Request $request){
        return $this->service->execute(
            $request->user()->workshop->id,
            'plano_basico_teste',
            $request->input('email'),
            $request->input('cardToken')
        );
    }
}
