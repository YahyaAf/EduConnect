<?php 

namespace App\Services;

use App\Repositories\VideoRepository;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoService
{
    protected $videoRepository;

    public function __construct(VideoRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    public function storeVideo(Course $course, Request $request)
    {
        $videoPath = $request->file('video_path')->store('videos', 'public');

        return $this->videoRepository->createVideo($course, [
            'title'       => $request->title,
            'description' => $request->description,
            'video_path'  => $videoPath,
        ]);
    }

    public function getVideos(Course $course)
    {
        return $this->videoRepository->getAllVideosByCourse($course);
    }

    public function getVideoById($id)
    {
        return $this->videoRepository->getVideoById($id);  
    }

    public function updateVideo($id, Request $request)
    {
        $video = $this->getVideoById($id);

        if ($request->hasFile('video_path')) {
            Storage::disk('public')->delete($video->video_path);

            $videoPath = $request->file('video_path')->store('videos', 'public');
            $video->video_path = $videoPath;
        }

        $video->update($request->only(['title', 'description']));

        return $video;
    }

    public function deleteVideo($id)
    {
        $video = $this->getVideoById($id);  

        return $this->videoRepository->deleteVideo($video);
    }
}
