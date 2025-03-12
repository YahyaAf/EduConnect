<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|string',
            'difficulty_level' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'status' => 'required',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du cours est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'duration.required' => 'La durée est requise.',
            'difficulty_level.required' => 'Le niveau de difficulté est requis.',
            'category_id.required' => 'La catégorie est obligatoire.',
            'category_id.exists' => 'La catégorie sélectionnée est invalide.',
            'status.required' => 'Le statut du cours est obligatoire.',
            'tags.*.exists' => 'Certains tags sélectionnés sont invalides.',
        ];
    }
}
