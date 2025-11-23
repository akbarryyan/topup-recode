@extends('admin.layouts.app')

@section('title', 'Detail Mutasi')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Mutasi Saldo</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.mutations.index') }}">Kelola Mutasi</a></div>
                <div class="breadcrumb-item">Detail</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informasi Mutasi</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="200">ID Mutasi</th>
                                    <td><code>#{{ $mutation->id }}</code></td>
                                </tr>
                                <tr>
                                    <th>Tipe</th>
                                    <td>
                                        @if($mutation->type == 'credit')
                                            <span class="badge badge-success badge-lg">
                                                <i class="fas fa-arrow-down"></i> Credit (Masuk)
                                            </span>
                                        @else
                                            <span class="badge badge-danger badge-lg">
                                                <i class="fas fa-arrow-up"></i> Debit (Keluar)
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>
                                        <strong class="{{ $mutation->type == 'credit' ? 'text-success' : 'text-danger' }}" style="font-size: 1.3em;">
                                            {{ $mutation->formatted_amount }}
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Saldo Sebelum</th>
                                    <td>Rp {{ number_format($mutation->balance_before, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Saldo Setelah</th>
                                    <td><strong>Rp {{ number_format($mutation->balance_after, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>{{ $mutation->description }}</td>
                                </tr>
                                @if($mutation->notes)
                                    <tr>
                                        <th>Catatan</th>
                                        <td>{{ $mutation->notes }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ $mutation->created_at->format('d M Y H:i:s') }}</td>
                                </tr>
                            </table>

                            @if($mutation->reference_type && $mutation->reference_id)
                                <hr>
                                <h6>Referensi Transaksi</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="200">Tipe Referensi</th>
                                        <td><code>{{ class_basename($mutation->reference_type) }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>ID Referensi</th>
                                        <td><code>{{ $mutation->reference_id }}</code></td>
                                    </tr>
                                    @if($mutation->reference)
                                        <tr>
                                            <th>Data Transaksi</th>
                                            <td>
                                                @if(class_basename($mutation->reference_type) == 'TopUpTransaction')
                                                    <a href="{{ route('admin.deposits.show', $mutation->reference_id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-external-link-alt"></i> Lihat Deposit
                                                    </a>
                                                @elseif(class_basename($mutation->reference_type) == 'GameTransaction')
                                                    <span class="badge badge-info">Game Transaction</span>
                                                @elseif(class_basename($mutation->reference_type) == 'PrepaidTransaction')
                                                    <span class="badge badge-info">Prepaid Transaction</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            @endif

                            @if($mutation->metadata)
                                <hr>
                                <h6>Metadata</h6>
                                <pre class="bg-light p-3 rounded">{{ json_encode(is_string($mutation->metadata) ? json_decode($mutation->metadata) : $mutation->metadata, JSON_PRETTY_PRINT) }}</pre>
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
                                    <td>{{ $mutation->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $mutation->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td><span class="badge badge-info">{{ ucfirst($mutation->user->role ?? 'member') }}</span></td>
                                </tr>
                                <tr>
                                    <th>Saldo Saat Ini</th>
                                    <td><strong>Rp {{ number_format($mutation->user->balance ?? 0, 0, ',', '.') }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Timeline</h4>
                        </div>
                        <div class="card-body">
                            <div class="activities">
                                <div class="activity">
                                    <div class="activity-icon bg-primary text-white">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="activity-detail">
                                        <div class="mb-2">
                                            <span class="text-job">Dibuat</span>
                                        </div>
                                        <p>{{ $mutation->created_at->format('d M Y H:i:s') }}</p>
                                    </div>
                                </div>
                                <div class="activity">
                                    <div class="activity-icon bg-info text-white">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <div class="activity-detail">
                                        <div class="mb-2">
                                            <span class="text-job">Terakhir Update</span>
                                        </div>
                                        <p>{{ $mutation->updated_at->format('d M Y H:i:s') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <a href="{{ route('admin.mutations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
