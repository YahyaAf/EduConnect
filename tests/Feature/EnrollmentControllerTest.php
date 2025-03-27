<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Tests\TestCase;

class EnrollmentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(); 
        Auth::login($this->user); 
    }

    public function user_can_enroll_in_a_course()
    {
        $course = Course::factory()->create(['price' => 1000]); 
        Stripe::shouldReceive('setApiKey')->once();
        Stripe::shouldReceive('Checkout\Session::create')->once()->andReturn((object) ['id' => 'session_id', 'url' => 'https://payment-url']);

        $payment = Payment::create([
            'user_id' => $this->user->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'payment_status' => 'pending',
            'transaction_id' => 'transaction_id',
            'payment_method' => 'stripe'
        ]);

        $response = $this->postJson(route('enrollment.enroll', ['course_id' => $course->id]));

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Redirection vers Stripe pour le paiement',
            'payment_url' => 'https://payment-url'
        ]);

        $this->assertDatabaseHas('payments', [
            'user_id' => $this->user->id,
            'course_id' => $course->id,
            'payment_status' => 'pending',
        ]);
    }

    public function user_cannot_enroll_in_the_same_course_twice()
    {
        $course = Course::factory()->create(['price' => 1000]);
        Payment::create([
            'user_id' => $this->user->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'payment_status' => 'payed',
            'transaction_id' => 'existing_transaction_id',
            'payment_method' => 'stripe'
        ]);

        $response = $this->postJson(route('enrollment.enroll', ['course_id' => $course->id]));

        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'Vous êtes déjà inscrit à ce cours'
        ]);
    }

    public function user_can_list_enrollments()
    {
        $course = Course::factory()->create();
        $this->user->courses()->attach($course);

        $response = $this->getJson(route('enrollment.listEnrollments', ['course_id' => $course->id]));

        $response->assertStatus(200);
        $response->assertJsonStructure([ 
            '*' => [
                'user_id',
                'course_id',
                'status',
            ]
        ]);
    }

    public function user_can_update_enrollment_status()
    {
        $course = Course::factory()->create();
        $enrollment = $this->user->courses()->attach($course, ['status' => 'pending']);

        $response = $this->putJson(route('enrollment.updateStatus', ['id' => $enrollment->id]), [
            'status' => 'accepted'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Statut mis à jour avec succès',
        ]);

        $this->assertDatabaseHas('enrollments', [
            'status' => 'accepted',
        ]);
    }

    public function user_can_delete_enrollment()
    {
        $course = Course::factory()->create();
        $enrollment = $this->user->courses()->attach($course, ['status' => 'accepted']);

        $response = $this->deleteJson(route('enrollment.destroy', ['id' => $enrollment->id]));

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Inscription supprimée avec succès',
        ]);

        $this->assertDatabaseMissing('enrollments', [
            'user_id' => $this->user->id,
            'course_id' => $course->id,
        ]);
    }
}
