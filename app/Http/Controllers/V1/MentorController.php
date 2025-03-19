<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;

class MentorController extends Controller
{
    public function getCreatedCourses(): JsonResponse
    {
        $mentor = auth()->user(); 

        $courses = Course::where('user_id', $mentor->id)->get();

        return response()->json([
            'courses' => $courses,
        ]);
    }

    public function getEnrolledStudentsCount(): JsonResponse
    {
        $mentor = auth()->user();
        $studentsCount = \DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->where('courses.user_id', $mentor->id)
            ->count();

        return response()->json([
            'total_enrolled_students' => $studentsCount,
        ]);
    }

    public function getPerformanceStats(): JsonResponse
    {
        $mentor = auth()->user();

        $coursesStats = \DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->where('courses.user_id', $mentor->id)
            ->select('courses.name', \DB::raw('COUNT(enrollments.id) as students_enrolled'))
            ->groupBy('courses.id', 'courses.name')
            ->get();

        return response()->json([
            'performance_stats' => $coursesStats,
        ]);
    }

}
