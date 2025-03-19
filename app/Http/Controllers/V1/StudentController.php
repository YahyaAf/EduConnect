<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    public function getCourses(): JsonResponse
    {
        $student = auth()->user();
        $courses = $student->courses;

        return response()->json([
            'courses' => $courses,
        ]);
    }


    public function getProgress($id): JsonResponse
    {
        $student = User::findOrFail($id);

        $progress = $student->courses->map(function ($course) {
            return [
                'course_name' => $course->name,
                'progress' => $course->pivot->progress, 
            ];
        });

        return response()->json([
            'progress' => $progress,
        ]);
    }

}

