<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::factory(10)->create()->each(function ($course) {
            $tags = Tag::inRandomOrder()->limit(rand(1, 3))->pluck('id');
            $course->tags()->attach($tags);
        });
    }
}
