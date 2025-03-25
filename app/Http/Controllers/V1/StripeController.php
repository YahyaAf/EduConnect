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

class StripeController extends Controller
{
    protected $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    public function checkout($course_id){
        Stripe::setApiKey("sk_test_51R6UCjIo0Xgztj39OZysEUjIf2bwvKATBXjALqt8LX7NiVU9tTxzjTzg3wsb3voppYKsW1hNNyCkxWVtlXBoQsbc001lxLtUD2");
        $course = Course::findOrFail($course_id);
        $session = Session::create([
            'line_items'  => [
                [
                    'price_data' => [
                        'currency'     => 'mad',
                        'product_data' => [
                            'name' => $course->title,
                        ],
                        'unit_amount'  => $course->price * 100,
                    ],
                    'quantity'   => 1,
                ],
            ],
            'mode'        => 'payment',
            'success_url' => route('payment.success',$course),
            'cancel_url'  => route('payment.checkout',$course_id),
        ]);
        return response()->json([
            "message"=>'success',
            "url"=>$session->url
        ]);
    }

    public function success($course_id)
    {
        try {
            $user = Auth::user();

            $payment = Payment::where("user_id", $user->id)
                ->where("course_id", $course_id)
                ->where("payment_status", "pending")
                ->first();

            if (!$payment) {
                return response()->json([
                    "message" => 'Aucun paiement en attente trouvé !'
                ], 404);
            }

            $token_payment = session()->get("Session_token_payment");
           
            $payment->update([
                'payment_status' => 'payed'
            ]);

            $this->enrollmentService->enrollUser([
                'user_id' => $user->id,
                'course_id' => $course_id,
                'status' => 'accepted',
            ]);

            return response()->json([
                "message" => 'Paiement réussi et inscription confirmée !'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ]);
        }
    }

    public function history()
    {
        try {
            $user = Auth::user();
            $payments = Payment::where('user_id', $user->id)
                ->with('course') 
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'message' => 'Historique des paiements récupéré avec succès',
                'payments' => $payments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
