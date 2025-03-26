<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Models\Badge;
use Illuminate\Http\Request;
use App\Http\Requests\BadgeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateBadgeRequest;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::all();
        return response()->json($badges);
    }

    public function store(BadgeRequest $request)
    {
        $badge = Badge::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'condition_type' => $request->condition_type,
            'condition_value' => $request->condition_value,
        ]);

        return response()->json([
            'message' => 'Badge created successfully!',
            'badge' => $badge,
        ], 201);
    }

    public function show($id)
    {
        $badge = Badge::findOrFail($id);
        return response()->json($badge);
    }

    public function update(UpdateBadgeRequest $request, $id)
    {
        $badge = Badge::findOrFail($id);

        $badge->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'condition_type' => $request->condition_type,
            'condition_value' => $request->condition_value,
        ]);

        return response()->json([
            'message' => 'Badge updated successfully!',
            'badge' => $badge,
        ]);
    }

    public function destroy($id)
    {
        $badge = Badge::findOrFail($id);
        $badge->delete();

        return response()->json([
            'message' => 'Badge deleted successfully!',
        ]);
    }

    public function checkMentorBadge(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('mentor')) {
            return response()->json(['message' => 'User is not a mentor'], 400);
        }

        $courseCount = $user->createdCourses()->count();
        

        $badge = Badge::where('name', 'course-creator')->first();
        if ($courseCount >= $badge->condition_value) {
            $user->badges()->syncWithoutDetaching([$badge->id]); 
            return response()->json(['message' => 'You have received the badge: ' . $badge->name]);
        }

        return response()->json(['message' => 'You didn\'t get the badge: ' . $badge->name]);
    }


}
