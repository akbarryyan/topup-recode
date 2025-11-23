@extends('admin.layouts.app')

@section('title', 'Detail Deposit')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Deposit</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.deposits.index') }}">Kelola Deposit</a></div>
                <div class="breadcrumb-item">Detail</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informasi Deposit</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="200">Merchant Order ID</th>
                                    <td><code>{{ $deposit->merchant_order_id }}</code></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($deposit->status == 'pending')
                                            <span class="badge badge-warning badge-lg">Pending</span>
                                        @elseif($deposit->status == 'paid')
                                            <span class="badge badge-success badge-lg">Paid</span>
                                        @elseif($deposit->status == 'failed')
                                            <span class="badge badge-danger badge-lg">Failed</span>
                                        @else
                                            <span class="badge badge-secondary badge-lg">{{ $deposit->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td><strong class="text-success" style="font-size: 1.2em;">Rp {{ number_format($deposit->amount, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Payment Method</th>
                                    <td>{{ $deposit->paymentMethod->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Gateway</th>
                                    <td>{{ $deposit->paymentMethod->paymentGateway->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Reference</th>
                                    <td>{{ $deposit->reference ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td>{{ $deposit->created_at->format('d M Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Update</th>
                                    <td>{{ $deposit->updated_at->format('d M Y H:i:s') }}</td>
                                </tr>
                            </table>

                            @if($deposit->callback_data)
                                <hr>
                                <h6>Callback Data</h6>
                                <pre class="bg-light p-3 rounded">{{ json_encode(is_string($deposit->callback_data) ? json_decode($deposit->callback_data) : $deposit->callback_data, JSON_PRETTY_PRINT) }}</pre>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informasi User</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $deposit->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $deposit->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td><span class="badge badge-info">{{ ucfirst($deposit->user->role ?? 'member') }}</span></td>
                                </tr>
                                <tr>
                                    <th>Saldo Saat Ini</th>
                                    <td><strong>Rp {{ number_format($deposit->user->balance ?? 0, 0, ',', '.') }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <a href="{{ route('admin.deposits.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
