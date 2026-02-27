<?php

namespace App\Http\Controllers;

use App\Services\SubscribeWorkshopService as Service;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(private Service $service){}

    public function createCheckoutSession(Request $request){
        return $this->service->createCheckoutSession(
            $request->user()->id,
            $request->user()->workshop->id
        );
    }

    public function getCheckoutSession(Request $request) {
        return $this->service->getCheckoutSession(
            $request->user()->id,
            $request->user()->workshop->id,
            $request->route('id')
        );
    }

    public function stripeWebhook(Request $request){
        $this->service->stripeWebhook($request);
    }
}
