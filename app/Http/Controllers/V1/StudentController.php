<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function getCourses(): JsonResponse
    {
        $student = auth()->user();
        $courses = $this->studentService->getCourses($student);

        return response()->json([
            'courses' => $courses,
        ]);
    }

    public function getProgress(): JsonResponse
    {
        $student = auth()->user();
        $progress = $this->studentService->getProgress($student);

        return response()->json([
            'progress' => $progress,
        ]);
    }
}
