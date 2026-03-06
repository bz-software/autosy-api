<?php
namespace App\Http\Requests\Customer;

use App\Http\Requests\AbstractFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class SearchCustomerRequest extends AbstractFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phoneNumber' => ['nullable', 'string', 'min:3'],
        ];
    }
}
