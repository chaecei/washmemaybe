<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile_number' => '09123456789',
            'email' => 'john@example.com'
        ]);

        Order::create([
            'customer_id' => $customer->id,
            'service_type' => 'wash_fold',
            'total_load' => 5.5,
            'detergent' => 'Regular',
            'softener' => 'Floral'
        ]);
    }
}
