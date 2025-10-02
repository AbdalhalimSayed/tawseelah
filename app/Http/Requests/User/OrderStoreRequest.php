<?php

namespace App\Http\Requests\User;

use App\Helper\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class OrderStoreRequest extends FormRequest
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
            "pickup_lat"    => ["required", "string", "max:255"],
            "pickup_lng"    => ["required", "string", "max:255"],
            "dropoff_lat"   => ["required", "string", "max:255"],
            "dropoff_lng"   => ["required", "string", "max:255"],
            "price"         => ["required", "numeric", "min:0"]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->validationError(__("auth.data"), [$validator->errors()->keys()[0] => $validator->errors()->first()]);
        throw new ValidationException($validator, $response);
    }
}
