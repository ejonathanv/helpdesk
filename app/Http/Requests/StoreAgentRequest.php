<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgentRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'department' => 'required|integer|exists:departments,id',
            'phone' => 'nullable|string',
            'mobile' => 'nullable|string',
            'job_title' => 'nullable|string',
            'permissions' => 'required|exists:permissions,id',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'El campo nombre es requerido',
            'name.string' => 'El campo nombre debe ser una cadena de texto',
            'email.required' => 'El campo correo electrónico es requerido',
            'email.email' => 'El campo correo electrónico debe ser un correo electrónico válido',
            'email.unique' => 'El campo correo electrónico ya está en uso',
            'department.required' => 'El campo departamento es requerido',
            'department.integer' => 'El campo departamento debe ser un número entero',
            'department.exists' => 'El campo departamento debe ser un departamento existente',
            'phone.required' => 'El campo teléfono es requerido',
            'phone.string' => 'El campo teléfono debe ser una cadena de texto',
            'mobile.required' => 'El campo móvil es requerido',
            'mobile.string' => 'El campo móvil debe ser una cadena de texto',
            'job_title.required' => 'El campo puesto es requerido',
            'job_title.string' => 'El campo puesto debe ser una cadena de texto',
            'permissions.required' => 'El campo permisos es requerido',
            'permissions.exists' => 'El campo permisos debe ser un permiso existente',
            'password.required' => 'El campo contraseña es requerido',
            'password.string' => 'El campo contraseña debe ser una cadena de texto',
            'password.min' => 'El campo contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'El campo contraseña debe ser igual al campo confirmar contraseña',
            'password_confirmation.required' => 'El campo confirmar contraseña es requerido',
            'password_confirmation.string' => 'El campo confirmar contraseña debe ser una cadena de texto',
            'password_confirmation.min' => 'El campo confirmar contraseña debe tener al menos 8 caracteres',
        ];
    }
}
