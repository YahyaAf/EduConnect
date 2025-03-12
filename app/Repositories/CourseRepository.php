<?php

namespace App\Repositories;

use App\Models\Course;
use App\Interfaces\CourseRepositoryInterface;

class CourseRepository implements CourseRepositoryInterface
{
    public function getAll()
    {
        return Course::with('category', 'subcategory', 'tags')->get();
    }

    public function getById($id)
    {
        return Course::with('category', 'subcategory', 'tags')->findOrFail($id);
    }

    public function create(array $data)
    {
        $course = Course::create(collect($data)->except('tags')->toArray());

        if (isset($data['tags'])) {
            $course->tags()->attach($data['tags']);
        }

        return $course->load('tags');
    }

    public function update(Course $course, array $data)
    {
        $course->update(collect($data)->except('tags')->toArray());

        if (isset($data['tags'])) {
            $course->tags()->sync($data['tags']);
        }

        return $course->load('tags');
    }

    public function delete(Course $course)
    {
        return $course->delete();
    }
}
