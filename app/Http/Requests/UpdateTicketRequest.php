<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
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
            'status_id' => 'required|integer|in:1,2,3,4,5,6,7',
            'priority_id' => 'required|integer|in:1,2,3,4',
            'severity_id' => 'required|integer|in:1,2,3,4',
        ];
    }

    public function messages(){
        return [
            'status_id.required' => 'El status es requerido',
            'status_id.integer' => 'El status debe ser un número entero',
            'status_id.in' => 'El status seleccionado no es válido',
            'priority_id.required' => 'La prioridad es requerida',
            'priority_id.integer' => 'La prioridad debe ser un número entero',
            'priority_id.in' => 'La prioridad seleccionada no es válida',
            'severity_id.required' => 'La severidad es requerida',
            'severity_id.integer' => 'La severidad debe ser un número entero',
            'severity_id.in' => 'La severidad seleccionada no es válida',
        ];
    }
}
