<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_transactions' => 0,
            'total_revenue' => 0,
            'waiting' => 0,
            'processing' => 0,
            'success' => 0,
            'failed' => 0,
        ];

        $latestTransactions = collect();
        $mutations = collect();

        // Load active payment methods for top up
        $paymentMethods = PaymentMethod::with('paymentGateway')
            ->active()
            ->ordered()
            ->get()
            ->map(function($method) {
                return [
                    'id' => $method->id,
                    'code' => $method->code,
                    'name' => $method->name,
                    'description' => $method->description,
                    'image_url' => $method->image_url,
                    'fee_display' => $method->formatted_customer_fee,
                    'gateway_code' => $method->paymentGateway?->code,
                ];
            });

        return view('profile.index', compact('user', 'stats', 'latestTransactions', 'mutations', 'paymentMethods'));
    }
}
