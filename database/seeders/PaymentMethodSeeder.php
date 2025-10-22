<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'type' => 'Paystack',
                'account_number' => 'Online Payment Gateway',
                'account_name' => 'Paystack Payment',
            ],
            [
                'type' => 'Bank Transfer',
                'account_number' => '1234567890',
                'account_name' => 'Your Business Name',
            ],
            [
                'type' => 'Mobile Money',
                'account_number' => '0241234567',
                'account_name' => 'Your Business Name',
            ],
        ];

        foreach ($paymentMethods as $method) {
            Payment::firstOrCreate(
                ['type' => $method['type']],
                $method
            );
        }

        $this->command->info('✓ Payment methods created successfully!');
    }
}
