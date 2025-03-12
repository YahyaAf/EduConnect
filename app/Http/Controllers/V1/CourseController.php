<?php

namespace App\Http\Controllers\V1;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Http\Requests\UpdateCourseRequest;

class CourseController extends Controller
{

    public function index()
    {
        return response()->json(Course::with('category', 'subcategory', 'tags')->get());
    }

    public function store(CourseRequest $request)
    {
        try {
            $course = Course::create($request->except('tags'));

            if ($request->has('tags')) {
                $course->tags()->attach($request->tags);
            }

            return response()->json($course->load('tags'), 201);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du cours: ' . $e->getMessage());
            return response()->json(['success' => false], 400);
        }
    }

    public function show($id)
    {
        return response()->json(Course::with('category', 'subcategory', 'tags')->findOrFail($id));
    }

    public function update(UpdateCourseRequest $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->update($request->except('tags'));

        if ($request->has('tags')) {
            $course->tags()->sync($request->tags);
        }

        return response()->json($course->load('tags'));
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json(['message' => 'Course deleted successfully'], 204);
    }
}
