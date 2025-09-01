<?php

namespace App\Http\Requests\User;

use App\Helper\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ImageRequest extends FormRequest
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
            'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $reaponse=$this->validationError(__("auth.data"), $validator->errors());
        throw new ValidationException($validator, $reaponse);
    }
}
