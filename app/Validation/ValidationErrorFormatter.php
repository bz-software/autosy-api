<?php
namespace App\Validation;

use Illuminate\Contracts\Validation\Validator;

class ValidationErrorFormatter
{
    public static function format(Validator $validator): array
    {
        $errors = $validator->errors()->toArray();
        $formatted = [];

        foreach ($errors as $field => $messages) {
            data_set($formatted, $field, $messages[0]);
        }

        return $formatted;
    }
}
