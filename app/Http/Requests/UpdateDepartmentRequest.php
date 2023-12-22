<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:departments,name,'.$this->department->id,
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'El campo nombre es requerido',
            'name.string' => 'El campo nombre debe ser una cadena de texto',
            'name.unique' => 'El campo nombre ya existe',
            'description.string' => 'El campo descripción debe ser una cadena de texto',
            'description.max' => 'El campo descripción no debe exceder los 255 caracteres',
        ];
    }
}
