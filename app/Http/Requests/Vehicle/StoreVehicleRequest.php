<?php

namespace App\Http\Requests\Vehicle;

use App\Http\Requests\AbstractFormRequest;
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
            'brand' => ['required', 'string', 'min:2', 'max:100'],

            'model' => ['required', 'string', 'min:2', 'max:150'],

            'year' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . date('Y')
            ],

            'engine' => ['nullable', 'string', 'max:50'],

            'color' => ['nullable', 'string', 'max:50'],

            'licensePlate' => [
                'required',
                'string',
                'max:10',
                'alpha_num',
                Rule::unique('vehicles', 'license_plate')
            ],

            'idCustomer' => [
                'required',
                Rule::exists('customers', 'id')
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'brand.required' => 'A marca é obrigatória.',

            'model.required' => 'O modelo é obrigatório.',
            'model.min' => 'O modelo deve ter pelo menos 2 caracteres.',

            'year.integer' => 'O ano deve ser numérico.',
            'year.min' => 'Ano inválido.',
            'year.max' => 'O ano não pode ser no futuro.',

            'engine.max' => 'O motor deve ter no máximo 50 caracteres.',

            'color.max' => 'A cor deve ter no máximo 50 caracteres.',

            'licensePlate.required' => 'A placa é obrigatória.',
            'licensePlate.unique' => 'Placa já está cadastrada.',
            'licensePlate.alpha_num' => 'Formato de placa inválido, informe apenas números e letras',

            'idCustomer.exists' => 'Cliente não encontrado.'
        ];
    }
}