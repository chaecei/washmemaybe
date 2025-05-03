<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; // Assuming you have an Order model

class ServiceController extends Controller
{
    // Show the services modal page
    public function showServices()
    {
        return view('services'); // Make sure to use the correct Blade view name
    }

    // Handle the form submission to save customer information
    public function storeCustomer(Request $request)
    {
        // Validate the user form
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile_number' => 'required|regex:/^09\d{9}$/', // Example for mobile number validation
            'email' => 'required|email|unique:users,email', // Ensure email is unique in the users table
        ]);

        // Create the new customer
        $order = Order::create([
            'full_name' => $request->full_name,
            'mobile_number' => $request->mobile_number,
            'email' => $request->email,
        ]);

        // Redirect to the services page with success message
        return redirect()->route('services')->with('success', 'Customer added successfully!');
    }

    // Handle the order form submission
    public function storeOrder(Request $request)
    {
        // Validate the order data
        $request->validate([
            'service_type' => 'required|string',
            'total_load' => 'nullable|numeric|min:1', // Optional but should be a number greater than 0
            'detergent' => 'nullable|string',
            'softener' => 'nullable|string',
        ]);

        // Loop through the orders and store them
        foreach ($request->input('orders') as $orderData) {
            Order::create([
                'service_type' => $orderData['service_type'],
                'total_load' => $orderData['total_load'] ?? null,
                'detergent' => $orderData['detergent'] ?? null,
                'softener' => $orderData['softener'] ?? null,
            ]);
        }

        // Redirect with success message after storing the order
        return redirect()->route('services')->with('success', 'Order submitted successfully!');
    }
}
