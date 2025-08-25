<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Box;
use Illuminate\Http\Request;

class BoxController extends Controller
{
    public function index()
    {
        $boxes = Box::all();
        return response()->json($boxes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'width' => 'required|integer|min:1',
            'height' => 'required|integer|min:1',
            'color' => 'required|string|max:7', // Assuming color is a hex code
        ]);

        $box = Box::create($validated);

        return response()->json($box, 201);
    }
}
