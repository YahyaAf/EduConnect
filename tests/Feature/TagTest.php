<?php

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{getJson, postJson, putJson, deleteJson};

uses(RefreshDatabase::class);

test('can list all tags', function () {
    Tag::factory()->count(3)->create();
    
    getJson('/api/v1/tags')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

test('can create a tag', function () {
    $data = ['name' => 'New Tag'];
    
    postJson('/api/v1/tags', $data)
        ->assertCreated()
        ->assertJsonPath('data.name', 'New Tag');
});

test('can get a tag by id', function () {
    $tag = Tag::factory()->create();
    
    getJson("/api/v1/tags/{$tag->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $tag->id);
});

test('can update a tag', function () {
    $tag = Tag::factory()->create();
    $data = ['name' => 'Updated Tag'];
    
    putJson("/api/v1/tags/{$tag->id}", $data)
        ->assertOk()
        ->assertJsonPath('data.name', 'Updated Tag');
});

test('can delete a tag', function () {
    $tag = Tag::factory()->create();
    
    deleteJson("/api/v1/tags/{$tag->id}")
        ->assertNoContent();
    
    $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
});
