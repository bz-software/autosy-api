<?php

namespace App\Http\Requests\CashTransaction;

use Illuminate\Foundation\Http\FormRequest;

class SearchCashTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => [
                'required',
                'date',
                'date_format:Y-m-d',
            ],
            'end_date' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.required' => 'Informe a data inicial.',
            'start_date.date' => 'A data inicial deve ser válida.',
            'start_date.date_format' => 'A data inicial deve estar no formato YYYY-MM-DD.',

            'end_date.required' => 'Informe a data final.',
            'end_date.date' => 'A data final deve ser válida.',
            'end_date.date_format' => 'A data final deve estar no formato YYYY-MM-DD.',
            'end_date.after_or_equal' => 'A data final não pode ser menor que a data inicial.',
        ];
    }
}
