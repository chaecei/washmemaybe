<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        return view('notification-panel');
    }

    public function fetch()
    {
        $notifications = \App\Models\Notification::latest()->get();
        
        return response()->json($notifications);
    }
}

