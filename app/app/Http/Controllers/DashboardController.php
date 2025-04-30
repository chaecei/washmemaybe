<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pending;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingServices = Pending::where('status', 'Pending')->get();
        $processingServices = Pending::where('status', 'Processing')->get();
        $readyServices = Pending::where('status', 'Ready')->get();
        $unclaimedServices = Pending::where('status', 'Unclaimed')->get();

        return view('dashboard', [
            'pendingServices' => $pendingServices,
            'processingServices' => $processingServices,
            'readyServices' => $readyServices,
            'unclaimedServices' => $unclaimedServices,
        ]);
    }
    public function showPending()   
    {
        $pendingServices = Pending::where('status', 'Pending')->get();
        return view('dashboard', compact('pendingServices'));
    }

    public function showProcessing()
    {
        $processingServices = Pending::where('status', 'Processing')->get();
        return view('dashboard', compact('processingServices'));
    }

    public function showReady ()
    {
        $readyServices = Pending::where('status', 'Ready')->get();
        return view('dashboard', compact('readyServices'));
    }

    public function showUnclaimed()
    {
        $unclaimedServices = Pending::where('status', 'Unclaimed')->get();
        return view('partials.unclaimed-table', compact('unclaimedServices'));
    }
    

    public function markAsProcessing($id)
    {
        $service = Pending::findOrFail($id);
        $service->status = 'Processing';
        $service->save();

        return redirect()->route('dashboard.processing');
    }

    public function markAsReady($id)
    {
        $service = Pending::findOrFail($id);
        $service->status = 'Ready';
        $service->save();

        return redirect()->route('dashboard.ready');
    }

    public function markAsUnclaimed($id)
    {
        $service = Pending::findOrFail($id);
        $service->status = 'Unclaimed';
        $service->save();

        return redirect()->route('dashboard.unclaimed');
    }


}
