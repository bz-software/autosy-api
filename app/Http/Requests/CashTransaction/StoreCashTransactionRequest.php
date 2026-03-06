<?php

namespace App\Http\Requests\CashTransaction;

use App\Enums\CashTransactionCategory;
use App\Enums\CashTransactionSourceType;
use App\Enums\CashTransactionType;
use App\Enums\PaymentMethod;
use App\Http\Requests\AbstractFormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreCashTransactionRequest extends AbstractFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'integer',
                new Enum(CashTransactionType::class),
            ],
            'category' => [
                'required',
                'integer',
                new Enum(CashTransactionCategory::class),
            ],
            'sourceType' => [
                'required',
                'integer',
                new Enum(CashTransactionSourceType::class),
            ],
            'idAppointment' => [
                'nullable',
                'integer'
            ],
            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'regex:/^\d+(\.\d{1,2})?$/',
                'max:99999999.99'
            ],
            'paymentMethod' => [
                'required',
                'integer',
                new Enum(PaymentMethod::class),
            ],
            'transactionDate' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'before_or_equal:today'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500',
                'not_regex:/^\s+$/'
            ],
        ];
    }

    public function messages():array {
        return [
            'type.enum' => 'Tipo inválido',
            'type.required' => self::REQUIRED_MSG,
            'type.integer' => 'Tipo precisa ser um número inteiro',

            'category.enum' => 'Categoria inválida',
            'category.required' => self::REQUIRED_MSG,
            'category.integer' => 'Categoria precisa ser um número inteiro',

            'sourceType.enum' => 'Origem inválida',
            'sourceType.required' => self::REQUIRED_MSG,
            'sourceType.integer' => 'Origem precisa ser um número inteiro',

            'idAppointment.integer' => 'Origem precisa ser um número inteiro',

            'amount.required' => 'O valor é obrigatório.',
            'amount.numeric' => 'O valor deve ser um número.',
            'amount.gt' => 'O valor deve ser maior que zero.',
            'amount.regex' => 'O valor deve ter no máximo 2 casas decimais.',
            'amount.max' => 'O valor não pode ultrapassar R$ 99.999.999,99.',

            'paymentMethod.enum' => 'Forma de pagamento inválida',
            'paymentMethod.required' => self::REQUIRED_MSG,
            'paymentMethod.integer' => 'Forma de pagamento precisa ser um número inteiro',

            'transactionDate.required' => 'Informe a data da movimentação.',
            'transactionDate.date' => 'Informe uma data válida.',
            'transactionDate.date_format' => 'A data deve estar no formato YYYY-MM-DD.',
            'transactionDate.before_or_equal' => 'A data não pode ser futura.',

            'notes.string' => 'Digite um texto válido na observação.',
            'notes.max' => 'A observação ficou muito longa. (max: 500 caracteres)',
            'note.not_regex' => 'A observação não pode conter apenas espaços.'
        ];
    }
}
