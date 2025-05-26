<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|min:5|max:50',
            'email' => 'required|email|unique:users|min:15|max:100',
            'password' => 'required|string|min:5|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio',
            'name.min' => 'El campo nombre debe tener al menos 5 caracteres',
            'name.max' => 'El campo nombre no puede tener más de 50 caracteres',
            'email.required' => 'El campo email es obligatorio',
            'email.min' => 'El campo email debe tener al menos 15 caracteres',
            'email.max' => 'El campo email no puede tener más de 100 caracteres',
            'email.email' => 'El campo email debe ser un email',
            'email.unique' => 'El email ya existe',
            'password.required' => 'El campo password es obligatorio',
            'password.min' => 'El campo password debe tener al menos 5 caracteres',
            'password.max' => 'El campo password no puede tener más de 255 caracteres',
        ];
    }
}
