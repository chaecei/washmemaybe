<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use App\Models\Payment;
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
        $orders = Order::with(['customer', 'category', 'payment']) // now also loads payment
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

    public function storePayment(Request $request, Order $order)
    {
        // Validate the payment form data
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'amount' => 'required|numeric',
            'transaction_id' => 'nullable|string',
        ]);
    
        // Assume the payment is successful and update the order status
        $order->status = 'Paid'; // or any other status you want
        $order->save();
    
        // Optionally, you can create a Payment record if you're storing payments separately
        // Payment::create([
        //     'order_id' => $order->id,
        //     'payment_method' => $request->payment_method,
        //     'amount' => $request->amount,
        //     'transaction_id' => $request->transaction_id,
        // ]);
    
        return redirect()->route('dashboard')->with('success', 'Payment successfully received!');
    }

    public function showServiceOrder($orderId)
    {
        // Retrieve the order by its ID and eager load the customer data
        $order = Order::with('customer') // Eager load customer data (first_name, last_name)
                      ->findOrFail($orderId); // Use findOrFail to ensure the order exists
    
        // Pass the order to the view
        return view('orders.store')->with('order', $order);
    }

    public function storeOrder(Request $request)
    {
        $orders = json_decode($request->input('orders'), true);

        $request->merge(['orders' => $orders]);

        // Validate incoming data
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'mobile_number' => 'required|string',
            'orders' => 'required|array',
            'orders.*.service_type' => 'required|string',
            'orders.*.total_load' => 'required|integer|min:1',
            'orders.*.detergent' => 'required|string',
            'orders.*.softener' => 'required|string',
        ]);

        // Save customer details
        $customer = Customer::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'mobile_number' => $validated['mobile_number'],
        ]);

        $lastOrder = null;

        // Loop through each order and save it
        foreach ($validated['orders'] as $orderData) {
            $lastOrder = Order::create([
                'customer_id' => $customer->id,
                'service_type' => $orderData['service_type'],
                'total_load' => $orderData['total_load'],
                'detergent' => $orderData['detergent'],
                'softener' => $orderData['softener'],
            ]);
        }

        Notification::create([
            'type' => 'order_created',
            'message' => 'A new order was added.'
        ]);

        // Return success response with the last created order's ID
        return response()->json([
            'success' => true,
            'order_id' => $lastOrder->id,
        ]);
    }

    public function show($id)
    {
        // Retrieve the order by its ID
        $order = Order::findOrFail($id);

        // Pass the order data to the view
        return view('services.show', compact('order'));
    }

    public function orderHistory()
    {
        // Get all orders (you can modify this to fetch only the logged-in user's orders or use any filter)
        $orders = Order::with('customer')->get();  

        // Pass the orders to the view
        return view('history')->with('orders', $orders);
    }

    public function showExpenses()
    {
        return view('expenses');
    }

    public function showPayment($orderId)
    {
        // Fetch the order and ensure it exists
        $order = Order::with('category')->findOrFail($orderId);

        // Fetch payment info if it exists
        $payment = Payment::where('order_id', $orderId)->first();

        // Pass the data to the view
        return view('payment.show', compact('order', 'payment'));
    }

}