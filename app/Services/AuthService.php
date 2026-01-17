<?php
namespace App\Services;

use App\DTOs\AuthDTO;
use App\Exceptions\ServiceException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService {
    public function __construct(private UserRepository $repository) {}

    public function login(AuthDTO $dto){
        $user = $this->repository->getByPhone($dto->phone_number);

        if(empty($user)){
            throw new ServiceException(['phoneNumber' => 'Telefone inválido'], 400);
        }

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            throw new ServiceException(['password' => 'Senha inválida'], 400);
        }

        $token = $user->createToken('api-token')->plainTextToken;
        
        return (object) [
            'token' => $token,
            'user' => $user
        ];
    }
}

