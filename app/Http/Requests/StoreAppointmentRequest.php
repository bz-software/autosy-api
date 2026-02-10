<?php

namespace App\Http\Requests;

use App\Enums\WorkshopType;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends AbstractFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idCustomer' => [
                'required'
            ],
            'idVehicle' => [
                'required'
            ],
            'licensePlate' => [
                'required'
            ],  
            'notes' => [
                Rule::requiredIf(function() {
                    if($this->user()->workshop->type == WorkshopType::MECHANIC->value){
                        return true;
                    }

                    return false;
                })
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'notes.required' => "Observação é obrigatória"
        ];
    }
}
