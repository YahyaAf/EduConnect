<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
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

    public function searchMentors(Request $request)
    {
        $request->validate([
            'search' => 'required|string|max:255',
        ]);

        $searchQuery = $request->input('search');

        $mentors = User::role('mentor')
                        ->where('name', 'like', '%' . $searchQuery . '%')
                        ->get();

        return response()->json($mentors);
    }

    public function filterStudentsByBadge(Request $request)
    {
        $request->validate([
            'badges' => 'required|exists:badges,id',
        ]);

        $badgeId = $request->input('badges');

        $students = User::role('student')
                        ->whereHas('badges', function($query) use ($badgeId) {
                            $query->where('badges.id', $badgeId);
                        })
                        ->get();
        
        return response()->json($students);
    }




}

