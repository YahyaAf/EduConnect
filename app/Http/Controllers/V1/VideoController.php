<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function store(Request $request, $courseId): JsonResponse
    {
        try {
            $request->validate([
                'title'       => 'required|string|max:255',
                'description' => 'nullable|string',
                'video_path'       => 'required|file|mimes:mp4,avi,mkv|max:10240', 
            ]);

            $course = Course::findOrFail($courseId);

            if (!$request->hasFile('video_path')) {
                return response()->json(['error' => 'Aucun fichier vidéo trouvé'], 400);
            }

            $videoPath = $request->file('video_path')->store('videos', 'public');

            $video = $course->videos()->create([
                'title'       => $request->title,
                'description' => $request->description,
                'video_path'  => $videoPath,
            ]);

            return response()->json([
                'message' => 'Vidéo ajoutée avec succès', 
                'video'   => $video
            ], 201);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Le cours spécifié est introuvable'], 404);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation échouée', 'details' => $e->errors()], 422);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue', 'details' => $e->getMessage()], 500);
        }
    }

    public function index($courseId): JsonResponse
    {
        $course = Course::findOrFail($courseId);
        $videos = $course->videos;

        return response()->json(['videos' => $videos]);
    }

    public function show($id): JsonResponse
    {
        $video = Video::findOrFail($id);
        return response()->json(['video' => $video]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $video = Video::findOrFail($id);

        $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'video'       => 'sometimes|file|mimes:mp4,avi,mkv|max:10240',
        ]);

        if ($request->hasFile('video')) {
            \Storage::disk('public')->delete($video->video_path);

            $videoPath = $request->file('video')->store('videos', 'public');
            $video->video_path = $videoPath;
        }

        $video->update($request->only(['title', 'description']));

        return response()->json(['message' => 'Vidéo mise à jour avec succès', 'video' => $video]);
    }

    public function destroy($id): JsonResponse
    {
        $video = Video::findOrFail($id);
        
        \Storage::disk('public')->delete($video->video_path);

        $video->delete();

        return response()->json(['message' => 'Vidéo supprimée avec succès']);
    }
}
