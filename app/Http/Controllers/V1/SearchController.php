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
        // Get the search query from the request
        $query = $request->input('search');

        // Validate that the query exists
        if (!$query) {
            return response()->json(['message' => 'Search query is required'], 400);
        }

        // Search for courses by title or description
        $courses = Course::where('name', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%')
                        ->get();

        // Check if any courses were found
        if ($courses->isEmpty()) {
            return response()->json(['message' => 'No courses found matching your search criteria.'], 404);
        }

        // Return the search results as a JSON response
        return response()->json([
            'courses' => $courses
        ]);
    }

}

