<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getByStatus($status)
    {

        $normalizedStatus = ucwords(strtolower($status));

        $validStatuses = ['pending', 'processing', 'ready for pickup', 'unclaimed'];

        if(!in_array(strtolower($normalizedStatus), $validStatuses)) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        $categories = Category::where('status', $normalizedStatus)->get();

        return response()->json($categories);
    }

    public function pending()
    {
        return response()->json(
            Category::where('status', 'pending')->get()
        );
    }

}
