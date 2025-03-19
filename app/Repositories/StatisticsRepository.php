<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Category;
use App\Models\Tag;

class StatisticsRepository
{
    public function getCoursesStats(): array
    {
        return [
            'total_courses' => Course::count(),
            'in_progress_courses' => Course::where('status', 'in_progress')->count(),
            'completed_courses' => Course::where('status', 'completed')->count(),
        ];
    }

    public function getCategoriesStats(): array
    {
        return [
            'total_categories' => Category::count(),
            'categories_with_courses' => Category::has('courses')->count(),
            'categories_without_courses' => Category::doesntHave('courses')->count(),
        ];
    }

    public function getTagsStats(): array
    {
        return [
            'total_tags' => Tag::count(),
            'tags_with_courses' => Tag::has('courses')->count(),
            'tags_without_courses' => Tag::doesntHave('courses')->count(),
        ];
    }
}
