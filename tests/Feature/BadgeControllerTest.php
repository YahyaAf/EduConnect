<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Badge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BadgeControllerTest extends TestCase
{
    public function test_filter_students_by_badge()
    {
        $badge = Badge::create([
            'name' => 'Test Badge',
            'description' => 'A test badge',
            'type' => 'test',
            'condition_type' => 'test',
            'condition_value' => 1
        ]);

        $student1 = User::create([
            'name' => 'Student 1',
            'email' => 'student1@example.com',
            'password' => bcrypt('password')
        ]);
        
        $student2 = User::create([
            'name' => 'Student 2',
            'email' => 'student2@example.com',
            'password' => bcrypt('password')
        ]);

        $student1->badges()->attach($badge->id);

        $response = $this->getJson('/api/V3/students?badges=' . $badge->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Student 1']);
        $response->assertJsonMissing(['name' => 'Student 2']);
    }
}
