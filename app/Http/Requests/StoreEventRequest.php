<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'comments' => 'required|string',
            'days' => 'nullable|integer',
            'hours' => 'nullable|integer',
            'minutes' => 'nullable|integer',
            'type' => 'required|string|in:remote,on-site',
            'publicAs' => 'required|string|in:public,private',
        ];
    }

    public function messages(){
        return [
            'comments.required' => 'El campo comentarios es requerido',
            'comments.string' => 'El campo comentarios debe ser una cadena de texto',
            'days.integer' => 'El campo días debe ser un número entero',
            'hours.integer' => 'El campo horas debe ser un número entero',
            'minutes.integer' => 'El campo minutos debe ser un número entero',
            'type.required' => 'El campo tipo es requerido',
            'type.string' => 'El campo tipo debe ser una cadena de texto',
            'type.in' => 'El campo tipo debe ser remoto o en sitio',
            'publicAs.required' => 'El campo público como es requerido',
            'publicAs.string' => 'El campo público como debe ser una cadena de texto',
            'publicAs.in' => 'El campo público como debe ser público o privado',
        ];
    }
}
