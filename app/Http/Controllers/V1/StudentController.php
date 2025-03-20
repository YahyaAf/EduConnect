<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class StudentController extends BaseController
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;

        $this->middleware('can:getCoursesStudent')->only(['getCourses']);
        $this->middleware('can:getProgress')->only(['getProgress']);
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
