<?php

namespace App\Interfaces;

use App\Models\Course;

interface CourseRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update(Course $course, array $data);
    public function delete(Course $course);
}
