<?php

namespace App\Http\Requests\Driver\Order;

use App\Helper\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateOrderStatusRequest extends FormRequest
{
    use APIResponse;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "status" => ["required", "in:on_the_way,delivered"]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->validationError("Status Errors", $validator->errors());
        throw new ValidationException($validator, $response);
    }
}
