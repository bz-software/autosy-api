<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function __construct(private User $model) {}

    public function getByPhone(string $phoneNumber, bool $withWorkshop = false)
    {
        return $this->model
            ->when($withWorkshop, function ($query) {
                $query->with('workshop');
            })
            ->where('phone_number', $phoneNumber)
            ->first();
    }

    public function create($user){
        return $this->model->create($user);
    }
}

