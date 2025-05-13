<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function showPayment($id)
    {
        $order = Order::findOrFail($id);  // Make sure the model name is "Orders" (capital "O")
        return view('show')->with('order', $order);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:1',
        ]);

        // Save the payment
        Payment::create($validated);

        return response()->json(['success' => true]);
    }

    public function getOrderDetails($orderId)
    {
        $order = Order::with('customer') // Assuming the customer is related to the order
            ->find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.'
            ]);
        }

        // Format the order data as needed
        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'first_name' => $order->customer->first_name,
            'last_name' => $order->customer->last_name,
            'mobile_number' => $order->customer->mobile_number,
            'total_load' => $order->total_load,
            'detergent' => $order->detergent,
            'softener' => $order->softener,
            'grand_total' => $order->grand_total // Assuming this is calculated or retrieved as a field
        ]);
    }


}
