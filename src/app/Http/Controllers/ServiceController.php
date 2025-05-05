<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    // Show the services modal page
    public function showServices()
    {
        return view('services');
    }

    // Show recent orders on dashboard
    public function dashboardOrders()
    {
        $orders = Order::with(['customer', 'category']) // eager-load customer
                    ->orderBy('updated_at', 'desc')
                    ->limit(10)
                    ->get();

        return view('dashboard', compact('orders'));
    }

    // Update the order status
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Update picked_up_at if status changed to ready/completed
        if (in_array($request->status, ['ready for pickup', 'completed']) && $order->status !== $request->status) {
            $order->picked_up_at = now();
        }

        $order->status = $request->status;
        $order->save();

        // Trigger event (optional, if you have OrderStatusUpdated event)
        // event(new OrderStatusUpdated($order->fresh()));

        return response()->json([
            'success' => true,
            'order' => $order->fresh()
        ]);
    }

    // Store customer and orders in one submission
    public function store(Request $request)
    {
        // 1. Save customer
        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile_number' => $request->mobile_number,
            'email' => $request->email,
        ]);

    // 2. Loop through each order (which comes in as a JSON string)
    $orders = json_decode($request->orders[0], true); 

    foreach ($orders as $order) {
        Order::create([
            'customer_id'  => $customer->id,
            'service_type' => $order['service_type'] ?? null,
            'total_load'   => $order['total_load'] ?? 0,
            'detergent'    => $order['detergent'] ?? null,
            'softener'     => $order['softener'] ?? null,
        ]);
    }

    return redirect()->route('dashboard')->with('success', 'Order submitted successfully!');

    }

    public function storePayment(Request $request, Order $order)
    {
        // Validate the payment form data
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'amount' => 'required|numeric',
            'transaction_id' => 'nullable|string',
        ]);

        // Assume the payment is successful and update the order status
        $order->status = 'Paid';  // or any other status that you define, e.g., 'Processing'
        $order->save();

        // Optionally, you can create a Payment record if you're storing payments separately
        // Payment::create([
        //     'order_id' => $order->id,
        //     'payment_method' => $request->payment_method,
        //     'amount' => $request->amount,
        //     'transaction_id' => $request->transaction_id,
        // ]);

        // Redirect to a confirmation page or back to the dashboard
        return redirect()->route('dashboard')->with('success', 'Payment successfully received!');
    }

    public function showServiceOrder($orderId)
    {
        // Retrieve the order by its ID
        $order = Order::findOrFail($orderId); // Use findOrFail to ensure the order exists

        // Pass the order to the view
        return view('service', compact('order'));
    }


}