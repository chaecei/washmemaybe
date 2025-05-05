<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with('category')->get(); // Eager load the category relation
        return view('dashboard', compact('orders')); // Pass the orders to the view
    }



}

