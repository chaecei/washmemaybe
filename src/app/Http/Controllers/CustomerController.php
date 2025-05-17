<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::whereHas('orders') // only customers with orders
            ->withCount('orders')                 // adds orders_count column
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->first_name . ' ' . $customer->last_name,
                    'mobile_number' => $customer->mobile_number,
                    'orders_count' => $customer->orders_count,
                ];
            });

        if (request()->wantsJson()) {
            return response()->json(['data' => $customers]);
        }

        return view('customers', compact('customers'));
    }

}
