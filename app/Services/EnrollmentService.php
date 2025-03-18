<?php

namespace App\Services;

use App\Repositories\EnrollmentRepository;

class EnrollmentService
{
    protected $enrollmentRepository;

    public function __construct(EnrollmentRepository $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
    }

    public function enrollUser($data)
    {
        return $this->enrollmentRepository->createEnrollment($data);
    }

    public function getEnrollmentsByCourse($courseId)
    {
        return $this->enrollmentRepository->getEnrollmentsByCourseId($courseId);
    }

    public function updateEnrollmentStatus($id, $status)
    {
        return $this->enrollmentRepository->updateEnrollmentStatus($id, $status);
    }

    public function deleteEnrollment($id)
    {
        return $this->enrollmentRepository->deleteEnrollment($id);
    }
}
