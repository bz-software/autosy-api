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
                'required'
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
