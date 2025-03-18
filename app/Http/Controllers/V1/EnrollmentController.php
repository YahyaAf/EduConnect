<?php

namespace App\Http\Controllers\V1;

use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Un élève s'inscrit à un cours.
     */
    public function enroll(Request $request, $course_id)
    {
        $existingEnrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course_id)
            ->first();

        if ($existingEnrollment) {
            return response()->json(['message' => 'Vous êtes déjà inscrit à ce cours'], 409);
        }

        $enrollment = Enrollment::create([
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
        $enrollments = $course->enrollments()->with('user')->get();

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

        $enrollment = Enrollment::findOrFail($id);
        $enrollment->update(['status' => $request->status]);

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
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        return response()->json(['message' => 'Inscription supprimée avec succès']);
    }
}
