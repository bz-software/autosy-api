<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreVehicleRequest extends AbstractFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'model' => ['required', 'string', 'min:5', 'max:150'],
            'licensePlate' => [
                'required',
                'string',
                'max:10',
                Rule::unique('vehicles', 'license_plate')
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'model.required' => 'O modelo é obrigatório.',
            'model.min' => 'O modelo deve ter pelo menos 5 caracteres.',
            'licensePlate.required' => 'A placa é obrigatório.',
            'licensePlate.unique' => 'Placa já está cadastrada .',
        ];
    }
}
