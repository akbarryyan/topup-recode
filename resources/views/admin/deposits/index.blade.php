@extends('admin.layouts.app')

@section('title', 'Kelola Deposit')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kelola Deposit</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Kelola Deposit</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Deposit</h4>
                            </div>
                            <div class="card-body">
                                {{ $stats['total'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pending</h4>
                            </div>
                            <div class="card-body">
                                {{ $stats['pending'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Berhasil</h4>
                            </div>
                            <div class="card-body">
                                {{ $stats['paid'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Gagal</h4>
                            </div>
                            <div class="card-body">
                                {{ $stats['failed'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Total Deposit Berhasil</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <h2 class="text-success">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Deposit</h4>
                        </div>
                        <div class="card-body">
                            <!-- Filter Form -->
                            <form method="GET" action="{{ route('admin.deposits.index') }}" class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Cari</label>
                                            <input type="text" name="search" class="form-control" placeholder="Merchant Order ID, Nama User..." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="">Semua Status</option>
                                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Dari Tanggal</label>
                                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Sampai Tanggal</label>
                                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div>
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                                                <a href="{{ route('admin.deposits.index') }}" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Merchant Order ID</th>
                                            <th>User</th>
                                            <th>Payment Method</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($deposits as $deposit)
                                            <tr>
                                                <td>{{ $deposit->id }}</td>
                                                <td><code>{{ $deposit->merchant_order_id }}</code></td>
                                                <td>
                                                    <strong>{{ $deposit->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $deposit->user->email }}</small>
                                                </td>
                                                <td>{{ $deposit->paymentMethod->name ?? '-' }}</td>
                                                <td><strong>Rp {{ number_format($deposit->amount, 0, ',', '.') }}</strong></td>
                                                <td>
                                                    @if($deposit->status == 'pending')
                                                        <span class="badge badge-warning">Pending</span>
                                                    @elseif($deposit->status == 'paid')
                                                        <span class="badge badge-success">Paid</span>
                                                    @elseif($deposit->status == 'failed')
                                                        <span class="badge badge-danger">Failed</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ $deposit->status }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $deposit->created_at->format('d M Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.deposits.show', $deposit->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Tidak ada data deposit</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $deposits->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
