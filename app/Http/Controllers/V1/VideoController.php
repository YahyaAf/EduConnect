<?php

namespace App\Http\Controllers\V1;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Services\VideoService;
use Illuminate\Routing\Controller as BaseController;

class VideoController extends BaseController
{
    protected $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;

        $this->middleware('can:view-video')->only(['index', 'show']);
        $this->middleware('can:create-video')->only(['store']);
        $this->middleware('can:update-video')->only(['update']);
        $this->middleware('can:delete-video')->only(['destroy']);
    }

    public function store(VideoRequest $request, $courseId): JsonResponse
    {
        try {
            $course = Course::findOrFail($courseId);

            $video = $this->videoService->storeVideo($course, $request);

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
        try {
            $course = Course::findOrFail($courseId);

            $videos = $this->videoService->getVideos($course);

            return response()->json(['videos' => $videos]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue', 'details' => $e->getMessage()], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $video = $this->videoService->getVideoById($id);

            return response()->json(['video' => $video]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Vidéo introuvable'], 404);
        }
    }

    public function update(UpdateVideoRequest $request, $id): JsonResponse
    {
        try {
            $video = $this->videoService->updateVideo($id, $request);

            return response()->json(['message' => 'Vidéo mise à jour avec succès', 'video' => $video]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->videoService->deleteVideo($id);

            return response()->json(['message' => 'Vidéo supprimée avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue', 'details' => $e->getMessage()], 500);
        }
    }
}
