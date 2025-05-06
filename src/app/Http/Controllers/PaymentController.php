<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Order;

class PaymentController extends Controller
{
    public function showPayment($id)
    {
        $order = Order::findOrFail($id);

    return view('payment.form', compact('order'));
    }


}
