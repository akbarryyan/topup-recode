<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $data['trxid'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e11d48;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #e11d48;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .invoice-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-info td {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .invoice-info td:first-child {
            width: 40%;
            color: #666;
        }
        .invoice-info td:last-child {
            font-weight: bold;
        }
        .total-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #333;
        }
        .total-section td {
            border: none;
            font-size: 18px;
        }
        .total-section .amount {
            color: #10b981;
            font-size: 24px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
        <p>{{ config('app.name', 'NVD STORE') }}</p>
        <p>Tanggal: {{ $data['created_at']->format('d F Y, H:i') }}</p>
    </div>

    <div class="invoice-info">
        <table>
            <tr>
                <td>ID Transaksi</td>
                <td>{{ $data['trxid'] }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $data['email'] }}</td>
            </tr>
            <tr>
                <td>Produk</td>
                <td>{{ $data['service_name'] }}</td>
            </tr>
            @if($data['type'] === 'game')
                <tr>
                    <td>User ID</td>
                    <td>{{ $data['data_no'] }}</td>
                </tr>
                @if($data['data_zone'])
                <tr>
                    <td>Zone ID</td>
                    <td>{{ $data['data_zone'] }}</td>
                </tr>
                @endif
            @else
                <tr>
                    <td>Nomor Tujuan</td>
                    <td>{{ $data['data_no'] }}</td>
                </tr>
            @endif
            <tr>
                <td>Metode Pembayaran</td>
                <td>{{ strtoupper($data['payment_method_code']) }}</td>
            </tr>
            <tr>
                <td>Status Pembayaran</td>
                <td>
                    @php
                        $statusClass = $data['payment_status'] === 'paid' ? 'status-paid' : 'status-pending';
                        $statusText = $data['payment_status'] === 'paid' ? 'DIBAYAR' : 'MENUNGGU';
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                </td>
            </tr>
        </table>

        <table class="total-section">
            <tr>
                <td>Harga Produk</td>
                <td style="text-align: right;">Rp {{ number_format($data['price'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Biaya Admin</td>
                <td style="text-align: right;">Rp {{ number_format($data['payment_fee'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>TOTAL PEMBAYARAN</strong></td>
                <td style="text-align: right;" class="amount"><strong>Rp {{ number_format($data['payment_amount'], 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Terima kasih atas pembelian Anda!</p>
        <p>Invoice ini dibuat secara otomatis dan sah tanpa tanda tangan.</p>
        <p>Untuk pertanyaan, hubungi customer service kami.</p>
    </div>
</body>
</html>
