<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email,' . $this->user->id . '|min:15|max:100',
            'password' => 'nullable|string|min:5|max:255', 
            'address' => 'nullable|string|max:150', 
            'phone_number' => 'nullable|string|max:9', 
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio',
            'name.min' => 'El nombre debe tener al menos 5 caracteres',
            'name.max' => 'El nombre no puede superar los 50 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'email.min' => 'El correo electrónico debe tener al menos 15 caracteres',
            'email.max' => 'El correo electrónico no puede superar los 100 caracteres',
            'password.min' => 'La contraseña debe tener al menos 5 caracteres',
            'password.max' => 'La contraseña no puede superar los 255 caracteres',
            'address.max' => 'La dirección no puede superar los 150 caracteres',
            'phone_number.max' => 'El número de teléfono no puede superar los 9 caracteres',
        ];
    }
}
