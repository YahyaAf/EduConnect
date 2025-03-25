<?php

namespace App\Http\Controllers\V1;

use Stripe\Stripe;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Services\EnrollmentService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class EnrollmentController extends BaseController
{
    protected $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;

        $this->middleware('can:enroll')->only(['enroll']);
        $this->middleware('can:listEnrollments')->only(['listEnrollments']);
        $this->middleware('can:updateStatus')->only(['updateStatus']);
        $this->middleware('can:delete-enroll')->only(['destroy']);
    }

    /**
     * Un élève s'inscrit à un cours.
     */
    public function enroll(Request $request, $course_id)
    {
        $userId = Auth::id();

        $paymentExists = Payment::where('user_id', $userId)
            ->where('course_id', $course_id)
            ->where('payment_status', 'payed')
            ->exists();

        if ($paymentExists) {
            return response()->json(['message' => 'Vous êtes déjà inscrit à ce cours'], 409);
        }

        Stripe::setApiKey(env('STRIPE_TEST_SK'));

        $course = Course::findOrFail($course_id);
        
        $session = Session::create([
            'line_items'  => [
                [
                    'price_data' => [
                        'currency'     => 'mad',
                        'product_data' => [
                            'name' => $course->name,
                        ],
                        'unit_amount'  => $course->price * 100,
                    ],
                    'quantity'   => 1,
                ],
            ],
            'mode'        => 'payment',
            'success_url' => route('payment.success', $course_id),
            'cancel_url'  => route('payment.checkout', $course_id),
        ]);

        Payment::create([
            'user_id' => $userId,
            'course_id' => $course_id,
            'amount' => $course->price,
            'payment_status' => "pending",
            'transaction_id' => $session->id,
            'payment_method'=>"stripe"
        ]);

        session()->put('Session_token_payment', $session->id);

        return response()->json([
            'message' => 'Redirection vers Stripe pour le paiement',
            'payment_url' => $session->url
        ]);
    }

    /**
     * Lister les inscriptions à un cours.
     */
    public function listEnrollments($course_id)
    {
        $course = Course::findOrFail($course_id);
        $enrollments = $this->enrollmentService->getEnrollmentsByCourse($course_id);

        return response()->json($enrollments);
    }

    /**
     * Mettre à jour le statut d'une inscription.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,refused'
        ]);

        $enrollment = $this->enrollmentService->updateEnrollmentStatus($id, $request->status);

        return response()->json([
            'message' => 'Statut mis à jour avec succès',
            'enrollment' => $enrollment
        ]);
    }

    /**
     * Supprimer une inscription.
     */
    public function destroy($id)
    {
        $this->enrollmentService->deleteEnrollment($id);

        return response()->json(['message' => 'Inscription supprimée avec succès']);
    }
}
