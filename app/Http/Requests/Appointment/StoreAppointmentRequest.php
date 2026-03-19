<?php

namespace App\Http\Requests\Appointment;

use App\Http\Requests\AbstractFormRequest;
use App\Enums\WorkshopType;
use App\Models\Appointment;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends AbstractFormRequest
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
                'required'
            ],
            'odometer' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $idVehicle = request()->input('idVehicle');

                    $lastOdometer = Appointment::where('id_vehicle', $idVehicle)
                        ->max('odometer');

                    if ($lastOdometer && $value < $lastOdometer) {
                        $fail("A quilometragem não pode ser menor que a última registrada ({$lastOdometer} km).");
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'notes.required' => "Observação é obrigatória",
            'idVehicle.required' => "Veículo é obrigatório",
            'idCustomer.required' => "Cliente é obrigatório"
        ];
    }
}
