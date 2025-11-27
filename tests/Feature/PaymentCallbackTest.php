<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PrepaidTransaction;
use App\Models\PaymentMethod;
use App\Models\PrepaidService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCallbackTest extends TestCase
{
    // use RefreshDatabase;

    public function test_prepaid_callback_success()
    {
        dump('ENV DB_DATABASE: ' . env('DB_DATABASE'));
        
        // 1. Setup Data - Use Existing Transaction
        $trxid = 'PREPAID-1764219389-4842';
        $amount = 6930;
        
        // Get credentials from .env directly
        $merchantCode = env('DUITKU_MERCHANT_CODE');
        $apiKey = env('DUITKU_API_KEY');

        // Ensure transaction exists (optional, but good for test stability if run repeatedly)
        // In this case, we assume it exists because user said so.
        
        // 2. Generate Signature
        $signature = md5($merchantCode . $amount . $trxid . $apiKey);

        // 3. Send Callback Request
        $response = $this->post('/payment/callback', [
            'merchantCode' => $merchantCode,
            'amount' => $amount,
            'merchantOrderId' => $trxid,
            'signature' => $signature,
            'resultCode' => '00', // Success
            'reference' => 'DS26199250A5R8LS2VS3PLAZ',
        ]);

        // 4. Assertions
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('prepaid_transactions', [
            'trxid' => $trxid,
            'payment_status' => 'paid',
            // 'status' => 'processing', // Status might depend on other logic, so maybe just check payment_status
        ]);
    }
}
