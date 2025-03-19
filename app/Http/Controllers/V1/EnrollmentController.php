<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\EnrollmentService;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
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
        $existingEnrollment = $this->enrollmentService->getEnrollmentsByCourse($course_id)->where('user_id', Auth::id())->first();

        if ($existingEnrollment) {
            return response()->json(['message' => 'Vous êtes déjà inscrit à ce cours'], 409);
        }

        // Création de l'inscription
        $enrollment = $this->enrollmentService->enrollUser([
            'user_id' => Auth::id(),
            'course_id' => $course_id,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Inscription réussie, en attente de validation',
            'enrollment' => $enrollment
        ], 201);
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
