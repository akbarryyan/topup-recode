<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

class DuitkuSeeder extends Seeder
{
    public function run()
    {
        PaymentGateway::updateOrCreate(
            ['code' => 'duitku'],
            [
                'name' => 'Duitku',
                'image' => 'duitku.png',
                'is_active' => true,
                'merchant_code' => 'D12345', // Replace with actual sandbox merchant code if known
                'api_key' => 'sandbox-api-key', // Replace with actual sandbox API key
                'environment' => 'sandbox',
            ]
        );
    }
}
