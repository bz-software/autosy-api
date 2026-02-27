<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository extends AbstractRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getByPhone(string $phoneNumber, bool $withWorkshop = false)
    {
        return $this->model
            ->when($withWorkshop, function ($query) {
                $query->with('workshop');
            })
            ->where('phone_number', $phoneNumber)
            ->first();
    }

    public function findByStripeCustomerId($id){
        return $this->model
            ->where('id_customer_stripe', $id)
            ->first();
    }
}

