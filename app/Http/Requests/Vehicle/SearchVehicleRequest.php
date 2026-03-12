<?php
namespace App\Http\Requests\Vehicle;

use App\Http\Requests\AbstractFormRequest;

class SearchVehicleRequest extends AbstractFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'licensePlate' => ['nullable', 'string', 'min:3'],
        ];
    }
}
