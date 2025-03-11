<?php

namespace App\Http\Controllers\V1;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{

    public function index()
    {
        return response()->json(Course::with('category', 'subcategory', 'tags')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|string',
            'difficulty_level' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:ouvert,en_cours,terminé',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $course = Course::create($request->except('tags'));

        if ($request->has('tags')) {
            $course->tags()->attach($request->tags);
        }

        return response()->json($course->load('tags'), 201);
    }

    public function show($id)
    {
        return response()->json(Course::with('category', 'subcategory', 'tags')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'duration' => 'sometimes|string',
            'difficulty_level' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'status' => 'sometimes|in:ouvert,en_cours,terminé',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

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
