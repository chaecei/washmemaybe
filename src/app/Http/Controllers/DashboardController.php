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
        // Fetch the orders and the related items and category (as you already have)
        $orders = Order::with(['items', 'category'])
                    ->latest()
                    ->take(10)
                    ->get();

        // Calculate the total of all orders
        $grandTotal = Order::sum('grand_total');  // This will calculate the sum of all 'grand_total'

        $expenses = Expense::all();

        // Pass the orders and grand total to the view
        return view('dashboard', compact('orders', 'grandTotal', 'expenses'))->with('mode', 'index');
    }


    public function getByStatus($status)
    {
        $orders = Order::with('category')
                    ->whereHas('category', function($query) use ($status) {
                        $query->where('status', $status);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json($orders);
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

    public function getWeeklyOrders()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $orders = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->created_at)->format('l'); // e.g., 'Monday'
            });

        $weekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $data = [];
        foreach ($weekDays as $day) {
            $data[] = isset($orders[$day]) ? count($orders[$day]) : 0;
        }

        return response()->json([
            'labels' => $weekDays,
            'data' => $data,
        ]);
    }




}

