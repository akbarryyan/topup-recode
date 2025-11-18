@extends('admin.layouts.app')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kelola Layanan Pulsa & PPOB</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Layanan Pulsa & PPOB</div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {!! nl2br(e(session('error'))) !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {!! nl2br(e(session('warning'))) !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Layanan Pulsa & PPOB</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#syncModal">
                                    <i class="fas fa-sync"></i> Sync dari API
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filter Form -->
                            <form method="GET" action="{{ route('admin.prepaid-services.index') }}" class="mb-4">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Brand</label>
                                            <select name="brand" class="form-control">
                                                <option value="">-- Semua Brand --</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                                        {{ $brand }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select name="category" class="form-control">
                                                <option value="">-- Semua --</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                                        {{ $category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Type</label>
                                            <select name="type" class="form-control">
                                                <option value="">-- Semua --</option>
                                                @foreach($types as $type)
                                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                                        {{ $type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Status API</label>
                                            <select name="status" class="form-control">
                                                <option value="">-- Semua --</option>
                                                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                                <option value="empty" {{ request('status') == 'empty' ? 'selected' : '' }}>Empty</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Status Aktif</label>
                                            <select name="is_active" class="form-control">
                                                <option value="">-- Semua --</option>
                                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div>
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label>Cari</label>
                                            <input type="text" name="search" class="form-control" placeholder="Nama layanan atau kode..." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            @if(request()->hasAny(['brand', 'category', 'type', 'status', 'is_active', 'search']))
                                            <a href="{{ route('admin.prepaid-services.index') }}" class="btn btn-secondary btn-block">
                                                <i class="fas fa-times"></i> Reset
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-striped" id="servicesTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Brand</th>
                                            <th>Category</th>
                                            <th>Type</th>
                                            <th>Nama Layanan</th>
                                            <th>Harga Basic</th>
                                            <th>Harga Premium</th>
                                            <th>Harga Special</th>
                                            <th>Multi Trx</th>
                                            <th>Maintenance</th>
                                            <th>Status API</th>
                                            <th>Aktif</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($services as $index => $service)
                                        <tr>
                                            <td>{{ ($services->currentPage() - 1) * $services->perPage() + $index + 1 }}</td>
                                            <td><code>{{ $service->code }}</code></td>
                                            <td><strong>{{ $service->brand ?? '-' }}</strong></td>
                                            <td><span class="badge badge-info">{{ $service->category ?? '-' }}</span></td>
                                            <td><span class="badge badge-secondary">{{ $service->type ?? '-' }}</span></td>
                                            <td>
                                                {{ $service->name }}
                                                @if($service->note)
                                                    <br><small class="text-muted">{{ $service->note }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $service->formatted_price_basic }}</td>
                                            <td>{{ $service->formatted_price_premium }}</td>
                                            <td>{{ $service->formatted_price_special }}</td>
                                            <td>
                                                @if($service->multi_trx)
                                                    <span class="badge badge-success"><i class="fas fa-check"></i> Ya</span>
                                                @else
                                                    <span class="badge badge-secondary"><i class="fas fa-times"></i> Tidak</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($service->maintenance)
                                                    <span class="badge badge-warning" title="{{ $service->maintenance }}">
                                                        <i class="fas fa-wrench"></i>
                                                    </span>
                                                @else
                                                    <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($service->status === 'available')
                                                    <span class="badge badge-success">Available</span>
                                                @else
                                                    <span class="badge badge-danger">Empty</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($service->is_active)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <form action="{{ route('admin.prepaid-services.toggle', $service->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-{{ $service->is_active ? 'secondary' : 'success' }}" title="Toggle Status">
                                                            <i class="fas fa-{{ $service->is_active ? 'times' : 'check' }}"></i>
                                                        </button>
                                                    </form>

                                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $service->id }}" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="14" class="text-center">Tidak ada data. Silakan sync dari API terlebih dahulu.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        @if($services->hasPages())
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    Menampilkan {{ $services->firstItem() ?? 0 }} - {{ $services->lastItem() ?? 0 }} dari {{ $services->total() }} layanan
                                </small>
                                <nav>
                                    {{ $services->links('pagination::bootstrap-4') }}
                                </nav>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Sync Modal -->
<div class="modal fade" id="syncModal" tabindex="-1" role="dialog" aria-labelledby="syncModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.prepaid-services.sync') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="syncModalLabel">Sync Layanan dari API</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Filter Brand (Optional)</label>
                        <select name="filter_brand" id="filterBrand" class="form-control">
                            <option value="">-- Semua Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand }}">{{ $brand }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kosongkan untuk sync semua brand</small>
                    </div>

                    <div class="form-group">
                        <label>Filter Type (Optional)</label>
                        <select name="filter_type" id="filterType" class="form-control">
                            <option value="">-- Semua Type --</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kosongkan untuk sync semua type</small>
                    </div>

                    <div class="form-group">
                        <label>Limit Jumlah (Optional)</label>
                        <input type="number" name="limit" class="form-control" value="1000" min="1" max="10000">
                        <small class="form-text text-muted">Maksimal 10000 layanan per proses (default: 1000)</small>
                    </div>

                    <hr>

                    <h6 class="mb-3"><i class="fas fa-dollar-sign"></i> Pengaturan Margin Harga</h6>
                    
                    <div class="form-group">
                        <label>Tipe Margin <span class="text-danger">*</span></label>
                        <select name="margin_type" id="marginType" class="form-control" required>
                            <option value="fixed">Fixed (Nominal Tetap)</option>
                            <option value="percent">Percent (Persentase)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nilai Margin <span class="text-danger">*</span></label>
                        <input type="number" name="margin_value" id="marginValue" class="form-control" min="0" step="0.01" placeholder="Contoh: 2000 atau 10" required>
                        <small class="form-text text-muted">
                            <span id="marginHelp">
                                Masukkan nominal dalam Rupiah (contoh: 2000 untuk Rp 2.000)
                            </span>
                        </small>
                    </div>

                    <div class="alert alert-warning">
                        <strong><i class="fas fa-calculator"></i> Contoh Perhitungan:</strong>
                        <div id="marginExample" class="mt-2">
                            Jika harga dari API: <strong>Rp 10.000</strong><br>
                            Margin: <strong>Rp 2.000</strong><br>
                            Harga tersimpan: <strong>Rp 12.000</strong>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Proses sync akan mengambil data layanan terbaru dari VIP Reseller API Prepaid Endpoint, menambahkan margin, dan memperbarui database.<br>
                        <strong>Behavior:</strong><br>
                        • Status <span class="badge badge-success">Available</span> → Disimpan/diupdate<br>
                        • Status <span class="badge badge-danger">Empty</span> (baru) → Dilewati<br>
                        • Status <span class="badge badge-danger">Empty</span> (sudah ada di database) → <strong>Dihapus otomatis</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sync"></i> Mulai Sync
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Margin calculator
    $('#marginType, #marginValue').on('change keyup', function() {
        var type = $('#marginType').val();
        var value = parseFloat($('#marginValue').val()) || 0;
        var basePrice = 10000;
        
        if (type === 'fixed') {
            $('#marginHelp').text('Masukkan nominal dalam Rupiah (contoh: 2000 untuk Rp 2.000)');
            var finalPrice = basePrice + value;
            $('#marginExample').html(
                'Jika harga dari API: <strong>Rp ' + basePrice.toLocaleString('id-ID') + '</strong><br>' +
                'Margin: <strong>Rp ' + value.toLocaleString('id-ID') + '</strong><br>' +
                'Harga tersimpan: <strong>Rp ' + finalPrice.toLocaleString('id-ID') + '</strong>'
            );
        } else {
            $('#marginHelp').text('Masukkan persentase (contoh: 10 untuk 10%)');
            var marginAmount = basePrice * (value / 100);
            var finalPrice = basePrice + marginAmount;
            $('#marginExample').html(
                'Jika harga dari API: <strong>Rp ' + basePrice.toLocaleString('id-ID') + '</strong><br>' +
                'Margin ' + value + '%: <strong>Rp ' + marginAmount.toLocaleString('id-ID') + '</strong><br>' +
                'Harga tersimpan: <strong>Rp ' + finalPrice.toLocaleString('id-ID') + '</strong>'
            );
        }
    });

    // Delete confirmation
    $('.delete-btn').on('click', function() {
        var serviceId = $(this).data('id');
        
        swal({
            title: "Apakah Anda yakin?",
            text: "Layanan ini akan dihapus secara permanen!",
            icon: "warning",
            buttons: {
                cancel: "Batal",
                confirm: "Ya, Hapus!"
            },
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                // Create and submit form
                var form = $('<form>', {
                    'method': 'POST',
                    'action': '/admin/prepaid-services/' + serviceId
                });
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '{{ csrf_token() }}'
                }));
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_method',
                    'value': 'DELETE'
                }));
                
                $('body').append(form);
                form.submit();
            }
        });
    });
});
</script>
@endpush
