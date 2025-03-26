<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search courses by title or description.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCourses(Request $request)
    {
        $query = $request->input('search');
        if (!$query) {
            return response()->json(['message' => 'Search query is required'], 400);
        }
        $courses = Course::where('name', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%')
                        ->get();

        if ($courses->isEmpty()) {
            return response()->json(['message' => 'No courses found matching your search criteria.'], 404);
        }
        return response()->json([
            'courses' => $courses
        ]);
    }

    public function filterCourses(Request $request)
    {
        $query = Course::query();

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('difficulty_level') && $request->difficulty) {
            $query->where('difficulty_level', $request->difficulty);
        }

        $courses = $query->get();

        return response()->json($courses);
    }


}

