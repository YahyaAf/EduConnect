<?php

namespace App\Http\Controllers\V1;

use App\Models\Badge;
use Illuminate\Http\Request;
use App\Http\Requests\BadgeRequest;
use App\Http\Controllers\Controller;
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
}
