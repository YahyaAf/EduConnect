<?php

use App\Models\Course;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can retrieve all courses', function () {
    Course::query()->delete();

    Course::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/courses');

    $response->assertOk()->assertJsonCount(3, 'data');
});

test('can create a course with tags', function () {
    Course::query()->delete();

    $category = Category::factory()->create();
    $tags = Tag::factory()->count(2)->create(); 

    $data = [
        'name' => 'New Course',
        'description' => 'Course Description',
        'duration' => '10',
        'difficulty_level' => 'beginner',
        'category_id' => $category->id,
        "subcategory_id" => $category->id,
        'status' => 'open',
        'tags' => $tags->pluck('id')->toArray() 
    ];

    $response = $this->postJson('/api/v1/courses', $data);

    $response->assertCreated()->assertJsonPath('data.name', 'New Course');
    $this->assertDatabaseHas('courses', ['name' => 'New Course']);

    $course = Course::where('name', 'New Course')->first();
    expect($course->tags)->toHaveCount(2);
});

test('can retrieve a specific course', function () {
    $course = Course::factory()->create();

    $response = $this->getJson("/api/v1/courses/{$course->id}");

    $response->assertOk()->assertJsonPath('data.id', $course->id);
});

test('returns 404 for a non-existent course', function () {
    $response = $this->getJson('/api/v1/courses/999');

    $response->assertNotFound();
});

test('can update a course with new tags', function () {
    Course::query()->delete();

    $course = Course::factory()->create(['name' => 'Old Course']);
    $tags = Tag::factory()->count(3)->create(); 

    $updateData = [
        'name' => 'Updated Course',
        'description' => 'Updated Description',
        'duration' => '15',
        'difficulty_level' => 'intermediate',
        'category_id' => $course->category_id,
        "subcategory_id" => $course->category_id,
        'status' => 'in_progress',
        'tags' => $tags->pluck('id')->toArray()
    ];

    $response = $this->putJson("/api/v1/courses/{$course->id}", $updateData);

    $response->assertOk()->assertJsonPath('data.name', 'Updated Course');

    expect($course->fresh()->tags)->toHaveCount(3);
});

test('can delete a course', function () {
    $course = Course::factory()->create();

    $response = $this->deleteJson("/api/v1/courses/{$course->id}");

    $response->assertNoContent();
    $this->assertDatabaseMissing('courses', ['id' => $course->id]);
});
