<?php

namespace App\Repositories;

use App\Models\Video;
use App\Models\Course;

class VideoRepository
{
    public function createVideo(Course $course, array $data)
    {
        return $course->videos()->create($data);
    }

    public function getAllVideosByCourse(Course $course)
    {
        return $course->videos;
    }

    public function getVideoById($id)
    {
        return Video::findOrFail($id);
    }

    public function updateVideo(Video $video, array $data)
    {
        return $video->update($data);
    }

    public function deleteVideo(Video $video)
    {
        \Storage::disk('public')->delete($video->video_path);
        return $video->delete();
    }
}
