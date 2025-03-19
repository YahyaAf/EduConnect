<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
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
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_path'  => 'required|file|mimes:mp4,avi,mkv|max:10240', 
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Le titre est requis',
            'video_path.required' => 'Aucun fichier vidéo trouvé',
            'video_path.mimes' => 'Le fichier doit être de type mp4, avi ou mkv',
            'video_path.max' => 'La taille du fichier ne doit pas dépasser 10 Mo',
        ];
    }
}
