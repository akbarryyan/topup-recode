@extends('admin.layouts.app')

@section('title', 'Transaksi Pulsa & PPOB')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Transaksi Pulsa & PPOB</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Transaksi Pulsa & PPOB</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Transaksi Pulsa & PPOB</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="transactionTable">
                                    <thead>
                                        <tr>
                                            <th>ID Transaksi</th>
                                            <th>User</th>
                                            <th>Layanan</th>
                                            <th>Nomor Tujuan</th>
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
                                                @if($transaction->user)
                                                <div class="d-flex align-items-center">
                                                    <figure class="avatar avatar-sm mr-2 bg-primary text-white d-flex align-items-center justify-content-center">
                                                        {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                                                    </figure>
                                                    <div>
                                                        <div>{{ $transaction->user->name }}</div>
                                                        <small class="text-muted">{{ $transaction->user->email }}</small>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="d-flex align-items-center">
                                                    <figure class="avatar avatar-sm mr-2 bg-secondary text-white d-flex align-items-center justify-content-center">
                                                        G
                                                    </figure>
                                                    <div>
                                                        <div><span class="badge badge-secondary">Guest</span></div>
                                                        <small class="text-muted">{{ $transaction->email ?? '-' }}</small>
                                                    </div>
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div><strong>{{ $transaction->service_name }}</strong></div>
                                                <small class="text-muted">{{ $transaction->service_code }}</small>
                                            </td>
                                            <td><code>{{ $transaction->data_no }}</code></td>
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
                                            <td colspan="8" class="text-center">Belum ada transaksi</td>
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
        "order": [[6, 'desc']], // Sort by date column
        "pageLength": 25,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>
@endpush
