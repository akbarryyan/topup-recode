@extends('admin.layouts.app')

@section('title', 'Kelola Mutasi')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kelola Mutasi Saldo</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Kelola Mutasi</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Mutasi</h4>
                            </div>
                            <div class="card-body">
                                {{ $stats['total'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Credit (Masuk)</h4>
                            </div>
                            <div class="card-body">
                                {{ $stats['credit'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Debit (Keluar)</h4>
                            </div>
                            <div class="card-body">
                                {{ $stats['debit'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Selisih</h4>
                            </div>
                            <div class="card-body">
                                Rp {{ number_format($stats['total_credit_amount'] - $stats['total_debit_amount'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Total Credit (Masuk)</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <h2 class="text-success">Rp {{ number_format($stats['total_credit_amount'], 0, ',', '.') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Total Debit (Keluar)</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <h2 class="text-danger">Rp {{ number_format($stats['total_debit_amount'], 0, ',', '.') }}</h2>
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
                            <h4>Daftar Mutasi</h4>
                        </div>
                        <div class="card-body">
                            <!-- Filter Form -->
                            <form method="GET" action="{{ route('admin.mutations.index') }}" class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Cari</label>
                                            <input type="text" name="search" class="form-control" placeholder="Deskripsi, User..." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Tipe</label>
                                            <select name="type" class="form-control">
                                                <option value="">Semua Tipe</option>
                                                <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit (Masuk)</option>
                                                <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit (Keluar)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>User</label>
                                            <select name="user_id" class="form-control">
                                                <option value="">Semua User</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Dari Tanggal</label>
                                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Sampai Tanggal</label>
                                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                                        <a href="{{ route('admin.mutations.index') }}" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                                    </div>
                                </div>
                            </form>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Tipe</th>
                                            <th>Deskripsi</th>
                                            <th>Amount</th>
                                            <th>Saldo Sebelum</th>
                                            <th>Saldo Setelah</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($mutations as $mutation)
                                            <tr>
                                                <td>{{ $mutation->id }}</td>
                                                <td>
                                                    <strong>{{ $mutation->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $mutation->user->email }}</small>
                                                </td>
                                                <td>
                                                    @if($mutation->type == 'credit')
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-arrow-down"></i> Credit
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-arrow-up"></i> Debit
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $mutation->description }}</strong>
                                                    @if($mutation->notes)
                                                        <br><small class="text-muted">{{ $mutation->notes }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong class="{{ $mutation->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                                        {{ $mutation->formatted_amount }}
                                                    </strong>
                                                </td>
                                                <td>Rp {{ number_format($mutation->balance_before, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($mutation->balance_after, 0, ',', '.') }}</td>
                                                <td>{{ $mutation->created_at->format('d M Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.mutations.show', $mutation->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">Tidak ada data mutasi</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $mutations->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
