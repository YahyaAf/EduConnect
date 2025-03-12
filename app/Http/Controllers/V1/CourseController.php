<?php

namespace App\Http\Controllers\V1;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Services\CourseService;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index()
    {
        $courses = $this->courseService->getAllCourses();
        return CourseResource::collection($courses);
    }

    public function store(CourseRequest $request)
    {
        try {
            $course = $this->courseService->createCourse($request->validated());
            return new CourseResource($course);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du cours: ' . $e->getMessage());
            return response()->json(['success' => false], 400);
        }
    }

    public function show($id)
    {
        $course = $this->courseService->getCourseById($id);
        return new CourseResource($course);
    }

    public function update(UpdateCourseRequest $request, $id)
    {
        $course = Course::findOrFail($id);
        $updatedCourse = $this->courseService->updateCourse($course, $request->validated());

        return new CourseResource($updatedCourse);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $this->courseService->deleteCourse($course);

        return response()->json(['message' => 'Course deleted successfully'], 204);
    }
}
