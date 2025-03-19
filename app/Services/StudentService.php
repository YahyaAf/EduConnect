<?php 

namespace App\Services;

use App\Models\User;
use App\Repositories\StudentRepository;

class StudentService
{
    protected $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function getCourses(User $student)
    {
        return $this->studentRepository->getCourses($student);
    }

    public function getProgress(User $student)
    {
        return $this->studentRepository->getProgress($student);
    }
}
