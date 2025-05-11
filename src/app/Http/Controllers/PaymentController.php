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

}
