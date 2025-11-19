<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $paymentGateways = PaymentGateway::all();
        return view('admin.payment-gateway.index', compact('paymentGateways'));
    }

    public function toggle(Request $request, PaymentGateway $paymentGateway)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $paymentGateway->update([
            'is_active' => $request->is_active,
        ]);

        return back()->with('success', 'Status payment gateway berhasil diperbarui.');
    }
}
