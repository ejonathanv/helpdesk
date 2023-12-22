<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'El nombre del departamento es requerido',
            'name.string' => 'El nombre del departamento debe ser una cadena de texto',
            'name.max' => 'El nombre del departamento no debe exceder los 255 caracteres',
            'name.unique' => 'El nombre del departamento ya existe',
            'description.string' => 'La descripción del departamento debe ser una cadena de texto',
            'description.max' => 'La descripción del departamento no debe exceder los 255 caracteres',
        ];
    }
}
