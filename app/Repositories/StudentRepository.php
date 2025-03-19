<?php 

namespace App\Repositories;

use App\Models\User;

class StudentRepository
{
    public function getCourses(User $student)
    {
        return $student->courses;
    }

    public function getProgress(User $student)
    {
        return $student->courses->map(function ($course) {
            return [
                'course_name' => $course->name,
                'progress' => $course->pivot->progress, 
            ];
        });
    }
}
