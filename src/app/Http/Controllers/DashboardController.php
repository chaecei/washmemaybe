<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaundryOrder;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
}
