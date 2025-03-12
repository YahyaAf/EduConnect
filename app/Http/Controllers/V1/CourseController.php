<?php

namespace App\Http\Controllers\V1;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Services\CourseService;
use OpenAPI\Annotations as OA;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/courses",
     *     summary="Get a list of all courses",
     *     tags={"Course"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Course Name"),
     *                 @OA\Property(property="description", type="string", example="This is a description of the course."),
     *                 @OA\Property(property="category", type="string", example="Science"),
     *                 @OA\Property(property="subcategory", type="string", example="Physics"),
     *                 @OA\Property(property="tags", type="array", @OA\Items(type="string", example="Tag 1"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     )
     * )
     */
    public function index()
    {
        $courses = $this->courseService->getAllCourses();
        return CourseResource::collection($courses);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/courses",
     *     summary="Create a new course",
     *     tags={"Course"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="New Course"),
     *             @OA\Property(property="description", type="string", example="This is a new course description"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="subcategory_id", type="integer", example=1),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Course successfully created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="New Course"),
     *             @OA\Property(property="description", type="string", example="This is a new course description"),
     *             @OA\Property(property="category", type="string", example="Science"),
     *             @OA\Property(property="subcategory", type="string", example="Physics"),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="string", example="Tag 1"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/v1/courses/{id}",
     *     summary="Get a specific course by ID",
     *     tags={"Course"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved the course",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Course Name"),
     *             @OA\Property(property="description", type="string", example="Course Description"),
     *             @OA\Property(property="category", type="string", example="Science"),
     *             @OA\Property(property="subcategory", type="string", example="Physics"),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="string", example="Tag 1"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     )
     * )
     */
    public function show($id)
    {
        $course = $this->courseService->getCourseById($id);
        return new CourseResource($course);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/courses/{id}",
     *     summary="Update a specific course by ID",
     *     tags={"Course"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Updated Course Name"),
     *             @OA\Property(property="description", type="string", example="Updated course description"),
     *             @OA\Property(property="category_id", type="integer", example=2),
     *             @OA\Property(property="subcategory_id", type="integer", example=3),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully updated the course",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Updated Course Name"),
     *             @OA\Property(property="description", type="string", example="Updated course description"),
     *             @OA\Property(property="category", type="string", example="Science"),
     *             @OA\Property(property="subcategory", type="string", example="Physics"),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="string", example="Updated Tag"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     )
     * )
     */
    public function update(UpdateCourseRequest $request, $id)
    {
        $course = Course::findOrFail($id);
        $updatedCourse = $this->courseService->updateCourse($course, $request->validated());

        return new CourseResource($updatedCourse);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/courses/{id}",
     *     summary="Delete a specific course by ID",
     *     tags={"Course"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Successfully deleted the course"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $this->courseService->deleteCourse($course);

        return response()->json(['message' => 'Course deleted successfully'], 204);
    }
}
