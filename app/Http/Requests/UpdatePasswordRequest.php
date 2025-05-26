<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
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
            'actual_password' => 'required',
            'new_password' => 'required|min:5',
            'repeat_password' => 'required|same:new_password',
        ];
    }

    public function messages(): array
    {
        return [
            'actual_password.required' => 'La contraseña actual es requerida',
            'new_password.required' => 'La contraseña nueva es requerida',
            'new_password.min' => 'La contraseña nueva debe tener al menos 5 caracteres',
            'repeat_password.required' => 'La confirmación de la contraseña nueva es requerida',
            'repeat_password.same' => 'La contraseña no coincide con la contraseña nueva',
        ];
    }
}
