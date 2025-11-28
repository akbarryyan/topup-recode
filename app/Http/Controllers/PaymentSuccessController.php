<?php

namespace App\Http\Controllers;

use App\Models\GameTransaction;
use App\Models\PrepaidTransaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PaymentSuccessController extends Controller
{
    /**
     * Display payment success page
     */
    public function show($trxid)
    {
        // Try to find transaction in game_transactions
        $transaction = GameTransaction::where('trxid', $trxid)->first();
        $type = 'game';

        // If not found, try prepaid_transactions
        if (!$transaction) {
            $transaction = PrepaidTransaction::where('trxid', $trxid)->first();
            $type = 'prepaid';
        }

        // If still not found, return 404
        if (!$transaction) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        // Prepare transaction data
        $data = [
            'trxid' => $transaction->trxid,
            'type' => $type,
            'service_name' => $transaction->service_name,
            'payment_amount' => $transaction->payment_amount,
            'payment_status' => $transaction->payment_status,
            'status' => $transaction->status,
            'created_at' => $transaction->created_at,
            'payment_method_code' => $transaction->payment_method_code,
            'email' => $transaction->email,
        ];

        // Add type-specific data
        if ($type === 'game') {
            $data['data_no'] = $transaction->data_no;
            $data['data_zone'] = $transaction->data_zone;
        } else {
            $data['data_no'] = $transaction->data_no;
        }

        return view('payment-success', compact('data'));
    }

    /**
     * Generate and download PDF invoice
     */
    public function downloadPdf($trxid)
    {
        // Try to find transaction in game_transactions
        $transaction = GameTransaction::where('trxid', $trxid)->first();
        $type = 'game';

        // If not found, try prepaid_transactions
        if (!$transaction) {
            $transaction = PrepaidTransaction::where('trxid', $trxid)->first();
            $type = 'prepaid';
        }

        // If still not found, return 404
        if (!$transaction) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        // Prepare transaction data
        $data = [
            'trxid' => $transaction->trxid,
            'type' => $type,
            'service_name' => $transaction->service_name,
            'payment_amount' => $transaction->payment_amount,
            'payment_status' => $transaction->payment_status,
            'status' => $transaction->status,
            'created_at' => $transaction->created_at,
            'payment_method_code' => $transaction->payment_method_code,
            'email' => $transaction->email,
            'price' => $transaction->price,
            'payment_fee' => $transaction->payment_fee,
        ];

        // Add type-specific data
        if ($type === 'game') {
            $data['data_no'] = $transaction->data_no;
            $data['data_zone'] = $transaction->data_zone;
        } else {
            $data['data_no'] = $transaction->data_no;
        }

        // Generate PDF
        $pdf = PDF::loadView('invoice-pdf', compact('data'));
        
        return $pdf->download('invoice-' . $trxid . '.pdf');
    }
}
