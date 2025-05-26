<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'price' => 'required|numeric|min:0|max:9999.99',
            'description' => 'required|string|max:255',
            'category' => 'required|in:accesorios,calzado,deporte,formal,casual,invierno,verano,ropa interior,ropa de baño,abrigo,camisetas,pantalones,vestidos',
            'state' => 'required|string|max:100',
            'stock' => 'required|integer|min:0|max:999',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:20048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio',
            'name.string' => 'El nombre del producto debe ser una cadena de texto',
            'name.min' => 'El nombre del producto debe tener al menos 5 caracteres',
            'name.max' => 'El nombre del producto no debe tener más de 50 caracteres',
            'price.required' => 'El precio del producto es obligatorio',
            'price.numeric' => 'El precio del producto debe ser un número',
            'price.min' => 'El precio del producto debe ser mayor o igual a 0',
            'price.max' => 'El precio del producto no debe ser mayor a 9999.99',
            'description.required' => 'La descripción del producto es obligatoria',
            'description.string' => 'La descripción del producto debe ser una cadena de texto',
            'description.max' => 'La descripción del producto no debe tener más de 255 caracteres',
            'category.required' => 'La categoría del producto es obligatoria',
            'category.in' => 'La categoría del producto no es válida',
            'state.required' => 'El estado del producto es obligatorio',
            'state.string' => 'El estado del producto debe ser una cadena de texto',
            'state.max' => 'El estado del producto no debe tener más de 100 caracteres',
            'stock.required' => 'El stock del producto es obligatorio',
            'stock.integer' => 'El stock del producto debe ser un número entero',
            'stock.min' => 'El stock del producto debe ser mayor o igual a 0',
            'stock.max' => 'El stock del producto no debe ser mayor a 999',
            'images.required' => 'Las imágenes del producto son obligatorias',
            'images.array' => 'Las imágenes del producto deben ser un arreglo',
            'images.*.mimes' => 'Las imágenes del producto deben ser de tipo jpg, jpeg, png o webp',
            'images.*.max' => 'Las imágenes del producto no deben pesar más de 20 MB',
        ];
    }
}
