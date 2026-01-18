<?php
namespace App\Services;

use App\DTOs\AuthDTO;
use App\DTOs\SignupDTO;
use App\Exceptions\ServiceException;
use App\Repositories\UserRepository;
use App\Repositories\WorkshopRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService {
    public function __construct(
        private UserRepository $repository,
        private WorkshopRepository $workshopRepository
    ) {}

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

    public function signup(SignupDTO $dto){
        return DB::transaction(function () use ($dto) {
            $user = $this->repository->create([
                'name' => strtoupper($dto->name),
                'phone_number' => $dto->phone_number,
                'password' => Hash::make($dto->password),
            ]);

            if (! $user || ! $user->id) {
                throw new ServiceException([], 500, 'Erro ao criar usuário');
            }

            $workshop = $this->workshopRepository->create([
                'name' => strtoupper($dto->workshop_name),
                'id_user' => $user->id,
                'type' => $dto->workshop_type,
            ]);

            if (! $workshop || ! $workshop->id) {
                throw new ServiceException([], 500, 'Erro ao criar workshop');
            }

            $authDTO = new AuthDTO(
                $dto->phone_number,
                $dto->password
            );

            return $this->login($authDTO);
        });
    }
}

