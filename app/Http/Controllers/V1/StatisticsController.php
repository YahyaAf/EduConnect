<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Course;
use App\Models\Category;
use App\Models\Tag;

class StatisticsController extends Controller
{

    public function getCoursesStats(): JsonResponse
    {
        $totalCourses = Course::count();
        $inProgressCourses = Course::where('status', 'in_progress')->count();
        $completedCourses = Course::where('status', 'completed')->count();

        return response()->json([
            'total_courses' => $totalCourses,
            'in_progress_courses' => $inProgressCourses,
            'completed_courses' => $completedCourses,
        ]);
    }

    public function getCategoriesStats(): JsonResponse
    {
        $totalCategories = Category::count();
        $categoriesWithCourses = Category::has('courses')->count();
        $categoriesWithoutCourses = Category::doesntHave('courses')->count();

        return response()->json([
            'total_categories' => $totalCategories,
            'categories_with_courses' => $categoriesWithCourses,
            'categories_without_courses' => $categoriesWithoutCourses,
        ]);
    }

    public function getTagsStats(): JsonResponse
    {
        $totalTags = Tag::count();
        $tagsWithCourses = Tag::has('courses')->count();
        $tagsWithoutCourses = Tag::doesntHave('courses')->count();

        return response()->json([
            'total_tags' => $totalTags,
            'tags_with_courses' => $tagsWithCourses,
            'tags_without_courses' => $tagsWithoutCourses,
        ]);
    }
}
