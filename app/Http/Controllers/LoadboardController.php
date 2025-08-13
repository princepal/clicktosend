<?php

namespace App\Http\Controllers;

use App\Models\Loadboard;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LoadboardController extends Controller
{
    /**
     * Get all available loadboards
     */
    public function index(): JsonResponse
    {
        $loadboards = Loadboard::all();
        return response()->json($loadboards);
    }

    /**
     * Get user's loadboards
     */
    public function getUserLoadboards(): JsonResponse
    {
        $user = auth()->user();
        $userLoadboards = $user->loadboards()->wherePivot('is_active', true)->get();
        return response()->json($userLoadboards);
    }

    /**
     * Add loadboard to user
     */
    public function attachLoadboard(Request $request): JsonResponse
    {
        $request->validate([
            'loadboard_id' => 'required|exists:loadboards,id'
        ]);

        $user = auth()->user();
        $loadboardId = $request->loadboard_id;

        // Check if already attached
        if ($user->loadboards()->where('loadboard_id', $loadboardId)->exists()) {
            return response()->json(['message' => 'Loadboard already added'], 400);
        }

        // Attach the loadboard
        $user->loadboards()->attach($loadboardId, ['is_active' => true]);

        return response()->json(['message' => 'Loadboard added successfully']);
    }

    /**
     * Remove loadboard from user
     */
    public function detachLoadboard(Request $request): JsonResponse
    {
        $request->validate([
            'loadboard_id' => 'required|exists:loadboards,id'
        ]);

        $user = auth()->user();
        $loadboardId = $request->loadboard_id;

        $user->loadboards()->detach($loadboardId);

        return response()->json(['message' => 'Loadboard removed successfully']);
    }
}
