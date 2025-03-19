<?php

namespace App\Services;

use App\Repositories\MentorRepository;

class MentorService
{
    protected $mentorRepository;

    public function __construct(MentorRepository $mentorRepository)
    {
        $this->mentorRepository = $mentorRepository;
    }

    public function getCreatedCourses($mentorId)
    {
        return $this->mentorRepository->getCreatedCourses($mentorId);
    }

    public function getEnrolledStudentsCount($mentorId)
    {
        return $this->mentorRepository->getEnrolledStudentsCount($mentorId);
    }

    public function getPerformanceStats($mentorId)
    {
        return $this->mentorRepository->getPerformanceStats($mentorId);
    }
}
