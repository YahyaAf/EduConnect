<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBadgeRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:student,mentor',
            'condition_type' => 'required|string',
            'condition_value' => 'required|integer',
        ];
    }

    /**
     * Personnaliser les messages de validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Le nom du badge est requis.',
            'description.required' => 'La description du badge est requise.',
            'type.required' => 'Le type de badge (étudiant ou mentor) est requis.',
            'condition_type.required' => 'Le type de condition est requis.',
            'condition_value.required' => 'La valeur de la condition est requise.',
            'condition_value.integer' => 'La valeur de la condition doit être un entier.',
        ];
    }
}
