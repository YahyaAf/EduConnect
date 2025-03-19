<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoRequest extends FormRequest
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
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'video_path'  => 'sometimes|file|mimes:mp4,avi,mkv|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'title.sometimes' => 'Le titre est facultatif mais doit être une chaîne de caractères valide.',
            'video_path.sometimes' => 'Le fichier vidéo doit être un fichier valide avec une taille ne dépassant pas 10 Mo.',
            'video_path.mimes' => 'Le fichier vidéo doit être de type mp4, avi, ou mkv.',
        ];
    }
}
