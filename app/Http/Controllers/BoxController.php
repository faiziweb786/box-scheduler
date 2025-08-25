<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BoxController extends Controller
{
    /**
     * Display the main box scheduler page
     */
    public function index()
    {
        $boxes = Box::all();
        return view('box-scheduler', compact('boxes'));
    }

    /**
     * Get all boxes as JSON for AJAX requests
     */
    public function getBoxes(): JsonResponse
    {
        $boxes = Box::all();
        return response()->json([
            'boxes' => $boxes,
            'count' => $boxes->count()
        ]);
    }

    /**
     * Create a single box (used by frontend)
     */
    public function createBox(Request $request): JsonResponse
    {
        $colors = ['red', 'yellow', 'green', 'blue', 'pink', 'grey'];

        $box = Box::create([
            'height' => $request->input('height', 40),
            'width' => $request->input('width', 100),
            'color' => $request->input('color', $colors[array_rand($colors)])
        ]);

        return response()->json([
            'success' => true,
            'box' => $box,
            'total_count' => Box::count()
        ]);
    }

    /**
     * Create boxes programmatically (used by scheduler)
     */
    public function createBoxes(int $count = 1): JsonResponse
    {
        $colors = ['red', 'yellow', 'green', 'blue', 'pink', 'grey'];
        $createdBoxes = [];

        for ($i = 0; $i < $count; $i++) {
            $box = Box::create([
                'height' => 40,
                'width' => 100,
                'color' => $colors[array_rand($colors)]
            ]);
            $createdBoxes[] = $box;
        }

        return response()->json([
            'success' => true,
            'boxes' => $createdBoxes,
            'total_count' => Box::count()
        ]);
    }

    /**
     * Reset all boxes (for testing purposes)
     */
    public function reset(): JsonResponse
    {
        Box::truncate();

        // Create the initial box
        $colors = ['red', 'yellow', 'green', 'blue', 'pink', 'grey'];
        Box::create([
            'height' => 40,
            'width' => 100,
            'color' => $colors[array_rand($colors)]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Boxes reset successfully',
            'total_count' => Box::count()
        ]);
    }
}
