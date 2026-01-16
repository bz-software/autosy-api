<?php

namespace App\Http\Controllers;

use App\DTOs\AuthDTO;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Services\AuthService as Service;

class AuthController extends Controller
{
    public function __construct(private Service $service){}

    public function login(AuthRequest $request){
        return new AuthResource($this->service->login(AuthDTO::fromRequest($request)));
    }
}
