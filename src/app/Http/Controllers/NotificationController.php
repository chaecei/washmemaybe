<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        return view('notification-panel'); // Make sure this view exists in your resources/views
    }

    public function fetch()
    {
        // Fetch all notifications ordered by latest (most recent first)
        $notifications = \App\Models\Notification::latest()->get();

        // Return notifications in JSON format
        return response()->json($notifications);
    }
}

