<?php

namespace App\Http\Controllers\V1;

use App\Models\Video;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\VideoRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateVideoRequest;

class VideoController extends Controller
{
    public function store(VideoRequest $request, $courseId): JsonResponse
    {
        try {
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

    public function update(UpdateVideoRequest $request, $id): JsonResponse
    {
        try {
            $video = Video::findOrFail($id);

            if ($request->hasFile('video_path')) {
                \Storage::disk('public')->delete($video->video_path);

                $videoPath = $request->file('video_path')->store('videos', 'public');
                $video->video_path = $videoPath;
            }

            $video->update($request->only(['title', 'description']));

            return response()->json(['message' => 'Vidéo mise à jour avec succès', 'video' => $video]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Vidéo introuvable'], 404);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $video = Video::findOrFail($id);
        
        \Storage::disk('public')->delete($video->video_path);

        $video->delete();

        return response()->json(['message' => 'Vidéo supprimée avec succès']);
    }
}
