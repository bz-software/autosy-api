<?php

namespace App\Http\Requests;

class StoreAppointmentServiceRequest extends AbstractFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idAppointment' => [
                'required',
                'integer',
                'exists:appointments,id',
            ],
            'serviceName' => [
                'required',
                'string',
                'max:255',
            ],
            'unitPrice' => [
                'required',
                'numeric',
                'min:0',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'idAppointment.required' => 'O agendamento é obrigatório.',
            'idAppointment.exists' => 'O agendamento informado não existe.',

            'serviceMame.required' => 'O nome do serviço é obrigatório.',
            'serviceMame.max' => 'O nome do serviço pode ter no máximo 255 caracteres.',

            'unitPrice.required' => 'O valor unitário é obrigatório.',
            'unitPrice.numeric' => 'O valor unitário deve ser um número.',
            'unitPrice.min' => 'O valor unitário não pode ser negativo.',

            'quantity.required' => 'A quantidade é obrigatória.',
            'quantity.integer' => 'A quantidade deve ser um número inteiro.',
            'quantity.min' => 'A quantidade deve ser no mínimo 1.',
        ];
    }
}
