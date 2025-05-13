<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getByStatus($status)
    {
        // Normalize the input status to match the expected format
        $normalizedStatus = ucwords(strtolower($status));

        // Define the valid statuses with consistent casing
        $validStatuses = ['Pending', 'Processing', 'Ready For Pickup', 'Unclaimed'];

        // Check if the provided status is valid
        if (!in_array($normalizedStatus, $validStatuses)) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        // Fetch categories with the specified status
        $categories = Category::where('status', $normalizedStatus)->get();

        // Return the categories as a JSON response
        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
    public function pending()
    {
        // Retrieve categories with 'pending' status
        $categories = Category::where('status', 'Pending')->get();

        return response()->json($categories);
    }

}
