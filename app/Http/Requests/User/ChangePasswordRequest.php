<?php

namespace App\Http\Requests\User;

use App\Helper\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ChangePasswordRequest extends FormRequest
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
            "old_password" => ["required"],
            "password"     =>["required", "min:8", "alpha_num", "confirmed"],
        ];
    }

    public function attributes()
    {
        return [
            "old_password" => __("auth.old_password"),
        ];
    }

    protected function  failedValidation(Validator $validator)
    {
        $response = $this->validationError(__("auth.data"), $validator->errors());

        throw new ValidationException($validator, $response);
    }
}
