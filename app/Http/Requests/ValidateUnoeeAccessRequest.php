<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ValidateUnoeeAccessRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'usuario_unoee' => trim((string) $this->input('usuario_unoee')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'usuario_unoee' => [
            'required',
            'string',
            'max:100',
            ],
            'clave_unoee' => [
            'required',
            'string',
            'max:255',
            ],
        ];
    }

    public function messages(): array
        {
            return [
            'usuario_unoee.required' => 'El usuario de UNOEE es obligatorio.',
            'usuario_unoee.max' => 'El usuario de UNOEE no puede superar los 100 caracteres.',
            'clave_unoee.required' => 'La clave de UNOEE es obligatoria.',
            'clave_unoee.max' => 'La clave de UNOEE no puede superar los 255 caracteres.',
        ];
    }

}
