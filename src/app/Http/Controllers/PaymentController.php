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
            'payment_method' => 'required|in:cash,card',
            'amount' => 'required|numeric|min:0.01',
            'transaction_id' => 'nullable|string|max:255',
        ]);
    
        // Example: Save the payment to the database
        Payment::create([
            'order_id' => $orderId,
            'payment_method' => $validated['payment_method'],
            'amount' => $validated['amount'],
            'transaction_id' => $validated['transaction_id'],
        ]);
    
        return redirect()->route('orders.show', $orderId)->with('success', 'Payment recorded successfully.');
    }

}
