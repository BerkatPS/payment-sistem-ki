<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSystemSeeder extends Seeder
{
    public function run(): void
    {
        // Create Payment Methods
        $paymentMethods = [
            ['name' => 'BCA Transfer', 'type' => 'bank_transfer'],
            ['name' => 'Mandiri Transfer', 'type' => 'bank_transfer'],
            ['name' => 'Credit Card Visa', 'type' => 'credit_card'],
            ['name' => 'GoPay', 'type' => 'e_wallet'],
            ['name' => 'OVO', 'type' => 'e_wallet'],
            ['name' => 'Cash', 'type' => 'cash'],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }

        // Create Customers
        $customers = [
            ['name' => 'John Doe', 'email' => 'john@example.com', 'phone' => '081234567890'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'phone' => '081234567891'],
            ['name' => 'Bob Johnson', 'email' => 'bob@example.com', 'phone' => '081234567892'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // Create Sample Payments
        $payments = [
            [
                'customer_id' => 1,
                'payment_method_id' => 1,
                'amount' => 150000,
                'status' => 'completed',
                'description' => 'Pembayaran produk A',
                'payment_date' => now()->subDays(5),
            ],
            [
                'customer_id' => 2,
                'payment_method_id' => 4,
                'amount' => 250000,
                'status' => 'completed',
                'description' => 'Pembayaran layanan B',
                'payment_date' => now()->subDays(3),
            ],
            [
                'customer_id' => 1,
                'payment_method_id' => 2,
                'amount' => 75000,
                'status' => 'pending',
                'description' => 'Pembayaran produk C',
                'payment_date' => now(),
            ],
        ];

        foreach ($payments as $payment) {
            Payment::create($payment);
        }
    }
}
