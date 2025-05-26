<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
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
            'subject' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'subject.required' => 'El campo asunto es obligatorio.',
            'subject.string' => 'El campo asunto debe ser una cadena de texto.',
            'subject.max' => 'El campo asunto no puede tener más de 255 caracteres.',
            'user_id.required' => 'El campo usuario es obligatorio.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
        ];
    }
}
