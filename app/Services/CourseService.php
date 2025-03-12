<?php

namespace App\Services;

use App\Models\Course;
use App\Interfaces\CourseRepositoryInterface;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getAllCourses()
    {
        return $this->courseRepository->getAll();
    }

    public function getCourseById($id)
    {
        return $this->courseRepository->getById($id);
    }

    public function createCourse(array $data)
    {
        return $this->courseRepository->create($data);
    }

    public function updateCourse(Course $course, array $data)
    {
        return $this->courseRepository->update($course, $data);
    }

    public function deleteCourse(Course $course)
    {
        return $this->courseRepository->delete($course);
    }
}
