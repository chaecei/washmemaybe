<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Order;
use App\Models\Expense;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch the orders and the related items and category
        $orders = Order::with(['items', 'category'])
                    ->latest()
                    ->take(10)
                    ->get();

        // Calculate the total of orders only for the current week
        $grandTotal = Order::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->sum('grand_total');

        $expenses = Expense::all();

        // Pass the data to the view
        return view('dashboard', compact('orders', 'grandTotal', 'expenses'))->with('mode', 'index');
    }

    public function getByStatus($status)
    {
        $orders = Order::whereHas('category', function($query) use ($status) {
                            $query->where('status', $status);
                        })
                        ->with(['category:id,order_id,status,updated_at']) // ensure updated_at is loaded
                        ->get()
                        ->sortByDesc(fn($order) => $order->category->updated_at)
                        ->values(); // reset keys

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
        // Validate the incoming status
        $validated = $request->validate([
            'status' => 'required|in:Pending,Processing,Ready for Pickup,Completed,Unclaimed',
        ]);

        try {
            // Check if category exists in the database
            $category = Category::where('order_id', $order_id)->firstOrFail();  // Look for category by ID

            // Check if the status value is different, then update
            if ($category->status !== $validated['status']) {
                $category->status = $validated['status'];
                $category->save();  // Save updated category

                return response()->json([
                    'success' => true,
                    'category' => $category,  // Return updated category
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Status is already set to this value.',
                ], 400);  // If the status is the same, return a 400 Bad Request
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ], 500);  // Catch any exceptions and return a 500 error with the message
        }
    }

    public function getGrandTotal()
    {
        // Sum the grand_total from all orders
        $total = Order::sum('grand_total');

        return response()->json([
            'total' => $total
        ]);
    }

    public function getWeeklyOrders(Request $request)
    {
        // Parse the week input (format: "2025-W21")
        if ($request->has('week')) {
            $week = $request->input('week'); // e.g., 2025-W21
            [$year, $weekNumber] = explode('-W', $week);

            // Get the start of the week
            $startOfWeek = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();
        } else {
            $startOfWeek = Carbon::now()->startOfWeek();
        }

        $endOfWeek = (clone $startOfWeek)->endOfWeek();

        // Group orders by day of the week
        $orders = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->created_at)->format('l'); // 'Monday', 'Tuesday', etc.
            });

        $weekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $data = [];

        foreach ($weekDays as $day) {
            $data[] = isset($orders[$day]) ? count($orders[$day]) : 0;
        }

        // Human-readable label, e.g., "May 4–11"
        $weekLabel = $startOfWeek->format('M j') . ' – ' . $endOfWeek->format('j');

        return response()->json([
            'labels' => $weekDays,
            'data' => $data,
            'week_label' => $weekLabel
        ]);
    }





}

