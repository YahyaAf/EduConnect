<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $studentCount = $user->createdCourses()->withCount('enrollments')->get()->sum('enrollments_count');

        $courseCreatorBadge = Badge::where('name', 'course-creator')->first();
        $topMentorBadge = Badge::where('name', 'top-mentor')->first();
        $activeMentorBadge = Badge::where('name', 'active-mentor')->first();

        $assignedBadges = [];

        if ($courseCount >= $courseCreatorBadge->condition_value) {
            $assignedBadges[] = $courseCreatorBadge->id; 
        }

        if ($studentCount >= $topMentorBadge->condition_value) {
            $assignedBadges[] = $topMentorBadge->id; 
        }

        if (abs(now()->diffInMonths($user->created_at)) >= $activeMentorBadge->condition_value) {
            $assignedBadges[] = $activeMentorBadge->id;
        }

        if (!empty($assignedBadges)) {
            $user->badges()->syncWithoutDetaching($assignedBadges); 
            return response()->json([
                'message' => 'You have received the badge(s): ' . implode(', ', Badge::find($assignedBadges)->pluck('name')->toArray())
            ]);
        }

        return response()->json(['message' => 'You didn\'t get the badge: course-creator, top-mentor, or active-mentor']);
    }

    public function checkStudentBadge(Request $request)
    {
        $user = Auth::user();  

        if (!$user->hasRole('student')) {
            return response()->json(['message' => 'User is not a student'], 400);
        }

        $completedCourseBadge = Badge::where('name', 'course-completed')->first(); 
        $multiCourseBadge = Badge::where('name', 'course-follower')->first(); 
        $fiveCourseBadge = Badge::where('name', 'course-completer')->first(); 
        $activeStudentBadge = Badge::where('name', 'active-student')->first(); 
        $sameMentorBadge = Badge::where('name', 'mentor-follower')->first(); 

        $completedCourses = $user->courses()->wherePivot('progress', 'done')->get();

        $uniqueCoursesCount = $user->courses()->distinct()->count();

        $completedCoursesCount = $completedCourses->count();

        $monthsActive = abs(now()->diffInMonths($user->created_at));

        $mentorCounts = DB::table('courses')
            ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->where('enrollments.user_id', $user->id)
            ->whereNotNull('courses.user_id') 
            ->select('courses.user_id as mentor_id', DB::raw('COUNT(*) as course_count'))
            ->groupBy('courses.user_id')
            ->get();


        $assignedBadges = [];

        if ($completedCoursesCount > 0) {
            $assignedBadges[] = $completedCourseBadge->id;
        }

        if ($uniqueCoursesCount >= $multiCourseBadge->condition_value) {
            $assignedBadges[] = $multiCourseBadge->id;
        }

        if ($completedCoursesCount >= $fiveCourseBadge->condition_value) {
            $assignedBadges[] = $fiveCourseBadge->id;
        }

        if ($monthsActive >= $activeStudentBadge->condition_value) {
            $assignedBadges[] = $activeStudentBadge->id;
        }

        if ($mentorCounts->first()->course_count > $sameMentorBadge->condition_value) {
            $assignedBadges[] = $sameMentorBadge->id;
        }

        if (!empty($assignedBadges)) {
            $user->badges()->syncWithoutDetaching($assignedBadges);
            return response()->json([
                'message' => 'You have received the badge(s): ' . implode(', ', Badge::find($assignedBadges)->pluck('name')->toArray())
            ]);
        }

        return response()->json(['message' => 'You didn\'t get any badges.']);
    }

    public function getUserBadges(Request $request)
    {
        $user = Auth::user();

        $badges = $user->badges;

        if ($badges->isEmpty()) {
            return response()->json(['message' => 'You don\'t have any badges yet.']);
        }

        return response()->json([
            'badges' => $badges->pluck('name'), 
        ]);
    }


}
