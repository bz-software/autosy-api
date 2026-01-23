<?php

namespace App\Http\Requests;

class StoreCustomerRequest extends AbstractFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'phoneNumber' => [
                'required',
                'string',
                'max:20',
                'unique:customers,phone_number'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
            'phoneNumber.required' => 'O telefone é obrigatório.',
            'phoneNumber.unique' => 'Este telefone já está cadastrado.',
        ];
    }
}
