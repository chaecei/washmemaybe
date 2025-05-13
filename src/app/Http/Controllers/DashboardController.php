<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'category'])
                    ->orderBy('updated_at', 'desc')
                    ->limit(10)
                    ->get();

        return view('dashboard', compact('orders'));
    }




}

