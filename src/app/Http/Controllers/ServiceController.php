<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ServiceController extends Controller
{
    public function showServices()
    {
        return view('services');
    }

    public function updateStatus(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if (in_array($request->status, ['processing', 'ready for pickup', 'completed']) && $category->status !== $request->status) {
            $category->picked_up_at = now();
        }

        $category->status = $request->status;
        $category->save();

        return response()->json([
            'success' => true,
            'category' => $category->fresh()
        ]);
    }

    public function storePayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'amount' => 'required|numeric',
            'transaction_id' => 'nullable|string',
        ]);
            
        try {
            $order->status = 'Paid';
            $order->save();
            return redirect()->route('dashboard')->with('success', 'Payment successfully received!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error submitting payment: ' . $e->getMessage()]);
        }
    }

    public function storeOrder(Request $request)
    {
        $orders = $request->input('orders');

        if (is_string($orders)) {
            $orders = json_decode($orders, true);
        }

        $request->merge(['orders' => $orders]);

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

        $calculation = $this->calculateOrderPrices($validated['orders']);

        $grandTotal = 0;
        foreach ($validated['orders'] as &$orderItem) {
            switch ($orderItem['detergent']) {
                case 'Regular Detergent': $orderItem['detergent_price'] = 20; break;
                case 'Hypoallergenic': $orderItem['detergent_price'] = 30; break;
                case 'Customer Provided': $orderItem['detergent_price'] = 0; break;
                default: $orderItem['detergent_price'] = 0;
            }

            switch ($orderItem['softener']) {
                case 'Regular': $orderItem['softener_price'] = 10; break;
                case 'Floral': $orderItem['softener_price'] = 20; break;
                case 'Baby Powder': $orderItem['softener_price'] = 20; break;
                case 'Unscented': $orderItem['softener_price'] = 0; break;
                case 'No Softener': $orderItem['softener_price'] = 0; break;
                default: $orderItem['softener_price'] = 0;
            }

            $servicePrices = [
                'Wash and Dry' => 150,
                'Wash and Fold' => 120,
            ];

            $serviceType = $orderItem['service_type'];
            $pricePerLoad = $servicePrices[$serviceType] ?? 150;
            $orderItem['total_load_price'] = $orderItem['total_load'] * $pricePerLoad;

            $totalLoadPrice = $orderItem['total_load_price'];
            $detergentPrice = $orderItem['detergent_price'];
            $softenerPrice = $orderitem['softener_price'];
  
            $grandTotal += $totalLoadPrice + $detergentPrice + $softenerPrice;
        }

        $existingCustomer = Customer::where('mobile_number', $validated['mobile_number'])->first();

        if ($existingCustomer) {
            if (
                $existingCustomer->first_name !== $validated['first_name'] ||
                $existingCustomer->last_name !== $validated['last_name']
            ) {
                return back()->withErrors([
                    'mobile_number' => 'This mobile number already exists, but the name does not match.',
                ])->withInput();
            }
            $customer = $existingCustomer;
        } else {
            $customer = Customer::create($validated);
        }

        \DB::beginTransaction();
        try {
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error saving orders: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'grand_total' => $grandTotal,
            'order_items' => $validated['orders'],
        ]);
    }


    public function show($id)
    {
        $order = Order::with('items')->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ]);
        }

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'first_name' => $order->customer->first_name,
            'last_name' => $order->customer->last_name,
            'mobile_number' => $order->customer->mobile_number,
            'grand_total' => $order->grand_total,
            'items' => $order->items->map(function ($item) {
                return [
                    'total_load' => $item->total_load,
                    'detergent' => $item->detergent,
                    'softener' => $item->softener,
                    'service_type' => $item->service_type,
                    'total_load_price' => $item->total_load_price,
                    'detergent_price' => $item->detergent_price,
                    'softener_price' => $item->softener_price,
                ];
            }),
        ]);
    }

    public function orderHistory()
    {
        $orders = Order::with('customer', 'items', )->get();  

        return view('history')->with('orders', $orders);
    }

    public function showExpenses()
    {
        return view('expenses');
    }

    public function showPayment($orderId)
    {
        try {
            if (!is_numeric($orderId)) {
                return response()->json(['error' => 'Invalid order ID'], 400);
            }

            $order = Order::with(['customer', 'items'])
                ->findOrFail($orderId);

            if (!$order->customer) {
                return response()->json(['error' => 'Customer data missing'], 404);
            }

            if ($order->items->isEmpty()) {
                return response()->json(['error' => 'No order items found'], 404);
            }

            $orderData = [
                'order_id' => $order->id,
                'customer' => [
                    'first_name' => $order->customer->first_name,
                    'last_name' => $order->customer->last_name,
                    'mobile_number' => $order->customer->mobile_number,
                ],
                'items' => $order->items->map(function ($item) {
                    return [
                        'service_type' => $item->service_type,
                        'total_load' => $item->total_load,
                        'detergent' => $item->detergent,
                        'softener' => $item->softener,
                        'load_price' => $this->calculateLoadPrice($item),
                        'detergent_price' => $this->calculateDetergentPrice($item),
                        'softener_price' => $this->calculateSoftenerPrice($item),
                        'subtotal' => $this->calculateLoadPrice($item) 
                                    + $this->calculateDetergentPrice($item) 
                                    + $this->calculateSoftenerPrice($item),
                    ];
                }),
                'grand_total' => $order->grand_total,
                'payment_status' => $order->payment->status ?? 'Pending',
                'meta' => [
                    'currency' => 'PHP',
                    'last_updated' => $order->updated_at->toIso8601String()
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $orderData
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Order not found'], 404);
        } catch (\Exception $e) {
            \Log::error("Payment lookup failed: " . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    private function calculateLoadPrice($item): float
    {
        $servicePrices = [
            'Wash and Dry' => 150,
            'Wash and Fold' => 120,
        ];
        return ($servicePrices[$item->service_type] ?? 150) * $item->total_load;
    }

    private function calculateDetergentPrice($item): float
    {
        switch ($item->detergent) {
            case 'Regular Detergent': return 20;
            case 'Hypoallergenic': return 30;
            case 'Customer Provided': return 0;
            default: return 0;
        }
    }

    private function calculateSoftenerPrice($item): float
    {
        switch ($item->softener) {
            case 'Regular': return 10;
            case 'Floral': 
            case 'Baby Powder': return 20;
            case 'Unscented':
            case 'No Softener': return 0;
            default: return 0;
        }
    }
    public function calculatePreview(Request $request)
    {
        // Reuse your existing calculation logic
        $grandTotal = 0;
        $orders = json_decode($request->orders, true);
        
        foreach ($orders as &$orderItem) {
        }

        return response()->json([
            'success' => true,
            'grand_total' => $grandTotal,
            'order_items' => $orders
        ]);
    }

    public function calculatePrices(Request $request)
    {
        try {
            $calculation = $this->calculateOrderPrices($request->orders);
            
            return response()->json([
                'success' => true,
                'grand_total' => $calculation['grand_total'],
                'order_items' => $calculation['order_items']
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function calculateOrderPrices(array $orders): array
    {
        if (empty($orders)) {
            return [];
        }

        $grandTotal = 0;
        
        foreach ($orders as &$orderItem) {
            switch ($orderItem['detergent']) {
                case 'Regular Detergent': $orderItem['detergent_price'] = 20; break;
                case 'Hypoallergenic': $orderItem['detergent_price'] = 30; break;
                case 'Customer Provided': $orderItem['detergent_price'] = 0; break;
                default: $orderItem['detergent_price'] = 0;
            }

            switch ($orderItem['softener']) {
                case 'Regular': $orderItem['softener_price'] = 10; break;
                case 'Floral': $orderItem['softener_price'] = 20; break;
                case 'Baby Powder': $orderItem['softener_price'] = 20; break;
                case 'Unscented':
                case 'No Softener': $orderItem['softener_price'] = 0; break;
                default: $orderItem['softener_price'] = 0;
            }

            $servicePrices = [
                'Wash and Dry' => 150,
                'Wash and Fold' => 120,
            ];

            $serviceType = $orderItem['service_type'];
            $pricePerLoad = $servicePrices[$serviceType] ?? 150;
            $orderItem['total_load_price'] = $orderItem['total_load'] * $pricePerLoad;

            $grandTotal += $orderItem['total_load_price'] + $orderItem['detergent_price'] + $orderItem['softener_price'];
        }

        return [
            'grand_total' => $grandTotal,
            'order_items' => $orders
        ];
    }

    public function fetchOrdersByStatus($status)
    {
        $orders = Order::with('category')
            ->whereHas('category', fn($q) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(['data' => $orders->items(), 'pagination' => $orders->toArray()]);
    }

    public function store(Request $request)
    {
        Log::info('Received order data', $request->all());

        $data = $request->validate([
            'customer_info' => 'required|array',
            'orders' => 'required|array',
            'grand_total' => 'required|numeric'
        ]);

        $customerInfo = $data['customer_info'];
        $orders = $data['orders'];

        DB::beginTransaction();
        try {
            $customer = Customer::firstOrCreate(
                ['mobile_number' => $customerInfo['mobile_number']],
                [
                    'first_name' => $customerInfo['first_name'],
                    'last_name' => $customerInfo['last_name']
                ]
            );

            $order = Order::create([
                'customer_id' => $customer->id,
                'grand_total' => $data['grand_total']
            ]);

            Category::create([
                'order_id' => $order->id,
                'status' => 'Pending',
                'days_unclaimed' => 0,
            ]);

            Notification::create([
                'type' => 'order_created',
                'message' => "Order ID #{$order->id} was created.",
            ]);

            // Add each order item
            foreach ($data['orders'] as $orderItem) {
                 $order->items()->create([
                    'service_type' => $orderItem['service_type'],
                    'total_load' => $orderItem['total_load'],
                    'detergent' => $orderItem['detergent'],
                    'softener' => $orderItem['softener'],
                    'total_load_price' => $orderItem['total_load_price'] ?? 0,
                    'detergent_price' => $orderItem['detergent_price'] ?? 0,
                    'softener_price' => $orderItem['softener_price'] ?? 0,
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Order saved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order save failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error saving order.'], 500);
        }
    }

}