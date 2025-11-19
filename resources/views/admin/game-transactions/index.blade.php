@extends('admin.layouts.app')

@section('title', 'Transaksi Game')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Transaksi Game</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Transaksi Game</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Transaksi Game</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="transactionTable">
                                    <thead>
                                        <tr>
                                            <th>ID Transaksi</th>
                                            <th>User</th>
                                            <th>Layanan</th>
                                            <th>ID Tujuan</th>
                                            <th>Zone</th>
                                            <th>Harga</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $transaction)
                                        <tr>
                                            <td><span class="badge badge-primary">{{ $transaction->trxid }}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm mr-2">
                                                        <span class="avatar-title rounded-circle bg-primary">
                                                            {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div>{{ $transaction->user->name }}</div>
                                                        <small class="text-muted">{{ $transaction->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div><strong>{{ $transaction->service_name }}</strong></div>
                                                <small class="text-muted">{{ $transaction->service_code }}</small>
                                            </td>
                                            <td><code>{{ $transaction->data_no }}</code></td>
                                            <td>
                                                @if($transaction->data_zone)
                                                    <code>{{ $transaction->data_zone }}</code>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ $transaction->formatted_price }}</strong></td>
                                            <td>
                                                <span class="badge badge-{{ $transaction->status_badge }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>{{ $transaction->created_at->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                @if($transaction->note)
                                                    <small class="text-muted">{{ $transaction->note }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Belum ada transaksi</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#transactionTable').DataTable({
        "order": [[7, 'desc']], // Sort by date column
        "pageLength": 25,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>
@endpush
