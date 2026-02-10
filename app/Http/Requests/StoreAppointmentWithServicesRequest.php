<?php
namespace App\Http\Requests;

use App\Enums\WorkshopType;
use Illuminate\Validation\Rule;

class StoreAppointmentWithServicesRequest extends AbstractFormRequest
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
                'nullable'
            ],

            // services
            'services' => ['required', 'array', 'min:1'],
            'services.*.idService' => [
                'required',
                'integer'
            ],
            'services.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ],
            'services.*.unitPrice' => [
                'required',
                'numeric',
                'min:0'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'notes.required' => "Observação é obrigatória",
            'idVehicle.required' => "Veículo é obrigatório",
            'idCustomer.required' => "Cliente é obrigatório",

            'services.required' => 'É necessário informar ao menos um serviço',
            'services.array' => 'Serviços devem ser uma lista',
            'services.min' => 'Informe ao menos um serviço',

            'services.*.idService.required' => 'Serviço é obrigatório',
            'services.*.idService.exists' => 'Serviço informado não existe',

            'services.*.quantity.required' => 'Quantidade é obrigatória',
            'services.*.quantity.min' => 'Quantidade mínima é 1',

            'services.*.unitPrice.required' => 'Valor do serviço é obrigatório',
            'services.*.unitPrice.min' => 'Valor do serviço não pode ser negativo',

            'date.required' => 'Data do agendamento é obrigatória',
        ];
    }
}