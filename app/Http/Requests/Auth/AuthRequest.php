<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\AbstractFormRequest;

class AuthRequest extends AbstractFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phoneNumber' => [
                'required',
                'regex:/^[1-9]{2}9[0-9]{8}$/'
            ],
            'password' => 'required'
        ];
    }

    public function messages():array {
        return [
            'phoneNumber' => [
                'required' => self::REQUIRED_MSG,
            ],

            'password' => [
                'required' => self::REQUIRED_MSG,
            ],
        ];
    }
}
