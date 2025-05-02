<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = collect([
            (object)['created_at' => now(), 'message' => 'Test notification 1'],
            (object)['created_at' => now(), 'message' => 'Test notification 2'],
        ]);
    
        return view('notification-panel', ['notifications' => $notifications]);

    }
    
}
