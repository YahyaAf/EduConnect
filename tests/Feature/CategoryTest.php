<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can retrieve all categories', function () {
    Category::query()->delete(); 

    Category::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/categories');

    $response->assertOk()->assertJsonCount(3, 'data');
});

test('can create a category', function () {
    Category::query()->delete(); 

    $data = [
        'name' => 'New Category'
    ];

    $response = $this->postJson('/api/v1/categories', $data);

    $response->assertCreated()->assertJsonPath('data.name', 'New Category');
});

test('can retrieve a specific category', function () {
    $category = Category::factory()->create();

    $response = $this->getJson("/api/v1/categories/{$category->id}");

    $response->assertOk()->assertJsonPath('data.id', $category->id);
});

test('returns 404 for a non-existent category', function () {
    $response = $this->getJson('/api/v1/categories/999');

    $response->assertNotFound();
});

test('can update a category', function () {
    Category::query()->delete(); 

    $category = Category::factory()->create(['name' => 'Old Name']);

    $updateData = [
        'name' => 'Updated Name'
    ];

    $response = $this->putJson("/api/v1/categories/{$category->id}", $updateData);

    $response->assertOk()->assertJsonPath('data.name', 'Updated Name');
});

test('can delete a category', function () {
    $category = Category::factory()->create();

    $response = $this->deleteJson("/api/v1/categories/{$category->id}");

    $response->assertNoContent(); 
    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});
