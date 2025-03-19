<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\MentorService;
use Illuminate\Http\JsonResponse;

class MentorController extends Controller
{
    protected $mentorService;

    public function __construct(MentorService $mentorService)
    {
        $this->mentorService = $mentorService;
    }

    public function getCreatedCourses(): JsonResponse
    {
        $mentor = auth()->user(); 
        $courses = $this->mentorService->getCreatedCourses($mentor->id);

        return response()->json([
            'courses' => $courses,
        ]);
    }

    public function getEnrolledStudentsCount(): JsonResponse
    {
        $mentor = auth()->user();
        $studentsCount = $this->mentorService->getEnrolledStudentsCount($mentor->id);

        return response()->json([
            'total_enrolled_students' => $studentsCount,
        ]);
    }

    public function getPerformanceStats(): JsonResponse
    {
        $mentor = auth()->user();
        $coursesStats = $this->mentorService->getPerformanceStats($mentor->id);

        return response()->json([
            'performance_stats' => $coursesStats,
        ]);
    }
}
