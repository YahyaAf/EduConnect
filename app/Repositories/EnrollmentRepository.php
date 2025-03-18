<?php

namespace App\Repositories;

use App\Models\Enrollment;

class EnrollmentRepository
{
    public function getEnrollmentsByCourseId($courseId)
    {
        return Enrollment::where('course_id', $courseId)->get();
    }

    public function createEnrollment($data)
    {
        return Enrollment::create($data);
    }

    public function getEnrollmentById($id)
    {
        return Enrollment::findOrFail($id);
    }

    public function updateEnrollmentStatus($id, $status)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->update(['status' => $status]);
        return $enrollment;
    }

    public function deleteEnrollment($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();
    }
}
