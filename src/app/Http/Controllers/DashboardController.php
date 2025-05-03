<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    // Fetch and show 'Pending' categories
    // public function pendingTable()
    // {
    //     $pendingCategories = Category::where('status', 'Pending')->get();
    //     return view('dashboard', compact('pendingCategories'));
    // }
}

