<?php

namespace App\Http\Requests;

use App\Enums\WorkshopType;
use Illuminate\Validation\Rule;

class SignupRequest extends AbstractFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Usuário
            'name' => ['required', 'string'],
            'phoneNumber' => [
                'required',
                'string',
                'regex:/^[1-9]{2}9[0-9]{8}$/', // DDD + 9 + 8 dígitos
                'unique:users,phone_number',
            ],
            'password' => ['required', 'string', 'min:4'],

            // Workshop
            'workshop' => ['required', 'array'],
            'workshop.name' => ['required', 'string', 'max:150'],
            'workshop.type' => [
                'required',
                'integer',
                Rule::in(array_column(WorkshopType::cases(), 'value'))
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',

            'phoneNumber.required' => 'O telefone é obrigatório.',
            'phoneNumber.regex' => 'O telefone deve estar no formato DDD + 9 + número.',
            'phoneNumber.unique' => 'Este telefone já está cadastrado.',

            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo 4 caracteres.',
            'password.string' => 'Formato de senha inválido',

            'workshop.required' => 'Os dados da oficina são obrigatórios.',
            'workshop.array' => 'O workshop deve ser um objeto.',

            'workshop.name.required' => 'O nome da oficina é obrigatório.',
            'workshop.name.max' => 'O nome da oficina pode ter no máximo 150 caracteres.',

            'workshop.type.required' => 'O tipo da oficina é obrigatório.',
            'workshop.type.integer' => 'O tipo da oficina deve ser um número.',
            'workshop.type.in'       => 'Tipo de workshop inválido.'
        ];
    }
}
