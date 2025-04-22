<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard'); // Make sure resources/views/dashboard.blade.php exists
    }

    public function showPending()
    {
        return view('pending');
    }

    public function showProcessing ()
    {
        return view('dashboard');
    }

    public function showReady ()
    {
        return view('dashboard');
    }

    public function showUnclaimed ()
    {
        return view('dashboard');
    }

}
