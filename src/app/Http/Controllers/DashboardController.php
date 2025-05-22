<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Notification;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items', 'category'])
                    ->latest()
                    ->take(10)
                    ->get();

        $grandTotal = Order::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->sum('grand_total');

        $expenses = Expense::all();

        return view('dashboard', compact('orders', 'grandTotal', 'expenses'))->with('mode', 'index');
    }

    public function getByStatus($status)
    {
        $orders = Order::whereHas('category', function($query) use ($status) {
                            $query->where('status', $status);
                        })
                        ->with(['category:id,order_id,status,updated_at'])
                        ->get()
                        ->sortByDesc(fn($order) => $order->category->updated_at)
                        ->values();

        return response()->json($orders);
    }

    public function getDashboardData()
    {
        $orderItems = OrderItem::all();

        $grandTotal = 0;

        foreach ($orderItems as $item) {
            $totalLoadPrice = floatval($item->total_load_price ?? 0);
            $detergentPrice = floatval($item->detergent_price ?? 0);
            $softenerPrice = floatval($item->softener_price ?? 0);

            $grandTotal += $totalLoadPrice + $detergentPrice + $softenerPrice;
        }

        return response()->json([
            'data' => $orderItems,
            'grand_total' => number_format($grandTotal, 2),
        ]);
    }

    public function updateStatus(Request $request, $order_id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Processing,Ready for Pickup,Completed,Unclaimed',
        ]);

        try {
            $category = Category::where('order_id', $order_id)->firstOrFail();

            if ($category->status !== $validated['status']) {
                $category->status = $validated['status'];
                $category->save();

                Notification::create([
                    'type' => 'status_update',
                    'message' => "Order ID #{$order_id} is marked {$category->status}.",
                ]);

                return response()->json([
                    'success' => true,
                    'category' => $category,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Status is already set to this value.',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ], 500);
        }

    }

    public function getGrandTotal()
    {
        $total = Order::sum('grand_total');

        return response()->json([
            'total' => $total
        ]);
    }

    public function getWeeklyOrders(Request $request)
    {
        if ($request->has('week')) {
            $week = $request->input('week');
            [$year, $weekNumber] = explode('-W', $week);

            $startOfWeek = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();
        } else {
            $startOfWeek = Carbon::now()->startOfWeek();
        }

        $endOfWeek = (clone $startOfWeek)->endOfWeek();

        $orders = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->created_at)->format('l');
            });

        $weekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $data = [];

        foreach ($weekDays as $day) {
            $data[] = isset($orders[$day]) ? count($orders[$day]) : 0;
        }

        $weekLabel = $startOfWeek->format('M j') . ' - ' . $endOfWeek->format('j');

        return response()->json([
            'labels' => $weekDays,
            'data' => $data,
            'week_label' => $weekLabel
        ]);
    }

}

