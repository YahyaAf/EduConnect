<?php

namespace App\Repositories;

use App\Models\Course;
use Illuminate\Support\Facades\DB;

class MentorRepository
{
    public function getCreatedCourses($mentorId)
    {
        return Course::where('user_id', $mentorId)->get();
    }

    public function getEnrolledStudentsCount($mentorId)
    {
        return DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->where('courses.user_id', $mentorId)
            ->count();
    }

    public function getPerformanceStats($mentorId)
    {
        return DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->where('courses.user_id', $mentorId)
            ->select('courses.name', DB::raw('COUNT(enrollments.id) as students_enrolled'))
            ->groupBy('courses.id', 'courses.name')
            ->get();
    }
}
