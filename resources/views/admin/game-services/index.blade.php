@extends('admin.layouts.app')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kelola Layanan Game</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Layanan Game</div>
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
                            <h4>Daftar Layanan Game</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-info mr-2" data-toggle="modal" data-target="#bulkStockModal">
                                    <i class="fas fa-box"></i> Bulk Cek Stock
                                </button>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#syncModal">
                                    <i class="fas fa-sync"></i> Sync dari API
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filter Form -->
                            <form method="GET" action="{{ route('admin.game-services.index') }}" class="mb-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Game</label>
                                            <select name="game" class="form-control">
                                                <option value="">-- Semua Game --</option>
                                                @foreach($games as $game)
                                                    <option value="{{ $game }}" {{ request('game') == $game ? 'selected' : '' }}>
                                                        {{ $game }}
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Cari</label>
                                            <input type="text" name="search" class="form-control" placeholder="Nama layanan atau kode..." value="{{ request('search') }}">
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
                                @if(request()->hasAny(['game', 'status', 'is_active', 'search']))
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ route('admin.game-services.index') }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-times"></i> Reset Filter
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </form>

                            <div class="table-responsive">
                                <table class="table table-striped" id="servicesTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Gambar</th>
                                            <th>Code</th>
                                            <th>Game</th>
                                            <th>Nama Layanan</th>
                                            <th>Harga Basic</th>
                                            <th>Harga Premium</th>
                                            <th>Harga Special</th>
                                            <th>Stock</th>
                                            <th>Status API</th>
                                            <th>Aktif</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($services as $index => $service)
                                        <tr>
                                            <td>{{ ($services->currentPage() - 1) * $services->perPage() + $index + 1 }}</td>
                                            <td>
                                                <img src="{{ $service->image_url }}" alt="{{ $service->game }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;" onclick="openImageModal('{{ $service->game }}', '{{ $service->image_url }}', {{ $service->has_image ? 'true' : 'false' }})">
                                            </td>
                                            <td><code>{{ $service->code }}</code></td>
                                            <td><strong>{{ $service->game }}</strong></td>
                                            <td>{{ $service->name }}</td>
                                            <td>{{ $service->formatted_price_basic }}</td>
                                            <td>{{ $service->formatted_price_premium }}</td>
                                            <td>{{ $service->formatted_price_special }}</td>
                                            <td>
                                                @if($service->stock === -1)
                                                    <span class="badge badge-warning" title="{{ $service->description }}">
                                                        Not Supported
                                                    </span>
                                                    @if($service->stock_updated_at && $service->stock_updated_at instanceof \Carbon\Carbon)
                                                        <br><small class="text-muted">{{ $service->stock_updated_at->diffForHumans() }}</small>
                                                    @endif
                                                @elseif($service->stock !== null)
                                                    <span class="badge badge-{{ $service->stock > 0 ? 'success' : 'danger' }}">
                                                        {{ $service->stock }}
                                                    </span>
                                                    @if($service->stock_updated_at && $service->stock_updated_at instanceof \Carbon\Carbon)
                                                        <br><small class="text-muted">{{ $service->stock_updated_at->diffForHumans() }}</small>
                                                    @endif
                                                @else
                                                    <span class="badge badge-secondary">N/A</span>
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
                                                    <button type="button" class="btn btn-sm btn-info" onclick="openImageModal('{{ $service->game }}', '{{ $service->image_url }}', {{ $service->has_image ? 'true' : 'false' }})" title="Upload Gambar Game">
                                                        <i class="fas fa-image"></i>
                                                    </button>

                                                    <form action="{{ route('admin.game-services.toggle', $service->id) }}" method="POST">
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
                                            <td colspan="12" class="text-center">Tidak ada data. Silakan sync dari API terlebih dahulu.</td>
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

            @if(session('debug_responses'))
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>🔍 Debug Info - Sample API Responses</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-sm btn-primary" onclick="copyDebugInfo()">
                                    <i class="fas fa-copy"></i> Copy untuk CS
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Berikut adalah sample response API yang bisa dikirim ke Customer Service VIP Reseller
                            </div>
                            <div id="debugContent">
                                <pre style="background: #f4f4f4; padding: 15px; border-radius: 5px; max-height: 500px; overflow-y: auto;">{{ json_encode(session('debug_responses'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                            <hr>
                            <p class="mb-0"><strong>IP Server Saat Ini:</strong> <code>195.88.211.226</code></p>
                            <p class="mb-0"><strong>Timestamp:</strong> <code>{{ now()->format('Y-m-d H:i:s') }}</code></p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
</div>

<!-- Sync Modal -->
<div class="modal fade" id="syncModal" tabindex="-1" role="dialog" aria-labelledby="syncModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.game-services.sync') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="syncModalLabel">Sync Layanan dari API</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Filter Game (Optional)</label>
                        <select name="filter_game" class="form-control">
                            <option value="">-- Semua Game --</option>
                            @foreach($games as $game)
                                <option value="{{ $game }}">{{ $game }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kosongkan untuk sync semua game</small>
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
                        <i class="fas fa-info-circle"></i> Proses sync akan mengambil data layanan terbaru dari VIP Reseller API, menambahkan margin, dan memperbarui database.<br>
                        <strong>Behavior:</strong><br>
                        • Status <span class="badge badge-success">Available</span> → Disimpan/diupdate<br>
                        • Status <span class="badge badge-danger">Empty</span> (baru) → Dilewati<br>
                        • Status <span class="badge badge-danger">Empty</span> (sudah ada di database) → <strong>Dihapus otomatis</strong>
                    </div>

                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <strong>Gambar game aman!</strong> Gambar yang sudah diupload tidak akan hilang saat sync ulang.
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

<!-- Bulk Check Stock Modal -->
<div class="modal fade" id="bulkStockModal" tabindex="-1" role="dialog" aria-labelledby="bulkStockModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.game-services.bulk-check-stock') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkStockModalLabel">Bulk Cek Stock Layanan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Filter Game (Optional)</label>
                        <select name="game" class="form-control">
                            <option value="">-- Semua Game --</option>
                            @foreach($games as $game)
                                <option value="{{ $game }}">{{ $game }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kosongkan untuk cek stock semua layanan</small>
                    </div>

                    <div class="form-group">
                        <label>Limit Jumlah (Optional)</label>
                        <input type="number" name="limit" class="form-control" value="50" min="1" max="200">
                        <small class="form-text text-muted">Maksimal 200 layanan per proses (default: 50, disarankan max 100)</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="debugMode" name="debug" value="1">
                            <label class="custom-control-label" for="debugMode">
                                <strong>Debug Mode</strong> - Simpan sample response API untuk troubleshooting
                            </label>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian!</strong><br>
                        Proses ini akan mengecek stock satu per satu dari API dan membutuhkan waktu lama.<br>
                        <strong>Estimasi: ~10 detik untuk 50 layanan, ~20 detik untuk 100 layanan</strong><br>
                        Disarankan cek per game untuk menghindari timeout.
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Stock akan diperbarui dengan data terbaru dari VIP Reseller API.
                    </div>
                    
                    <div class="alert alert-danger">
                        <i class="fas fa-shield-alt"></i> <strong>IP Whitelist Required!</strong><br>
                        Pastikan IP server Anda <strong>(195.88.211.226)</strong> sudah ditambahkan ke whitelist di:<br>
                        <a href="https://vip-reseller.co.id/setting/ip-whitelist" target="_blank" class="text-white font-weight-bold">
                            <u>https://vip-reseller.co.id/setting/ip-whitelist</u>
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-box"></i> Mulai Cek Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Upload Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Upload Gambar Game</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="imageUploadForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="previewImage" src="" alt="" class="img-thumbnail" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        <p class="mt-2"><strong id="gameName"></strong></p>
                    </div>
                    
                    <div class="form-group">
                        <label>Pilih Gambar Baru</label>
                        <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Format: JPG, PNG, GIF, WEBP. Maksimal 2MB</small>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Upload 1x untuk semua layanan game ini!</strong><br>
                        Gambar ini akan otomatis digunakan oleh semua layanan dari game <strong id="gameNameInfo"></strong>.<br>
                        Ukuran disarankan: 500x500px atau rasio 1:1
                    </div>

                    <div id="deleteImageSection" style="display: none;">
                        <hr>
                        <button type="button" class="btn btn-danger btn-block" id="deleteImageBtn">
                            <i class="fas fa-trash"></i> Hapus Gambar
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload Gambar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Open image modal
var currentGameName = null;

function openImageModal(gameName, imageUrl, hasImage) {
    currentGameName = gameName;
    $('#previewImage').attr('src', imageUrl);
    $('#gameName').text(gameName);
    $('#gameNameInfo').text(gameName);
    $('#imageUploadForm').attr('action', '/admin/game-services/upload-image/' + encodeURIComponent(gameName));
    $('#imageInput').val('');
    
    if (hasImage) {
        $('#deleteImageSection').show();
    } else {
        $('#deleteImageSection').hide();
    }
    
    $('#imageModal').modal('show');
}

// Preview image before upload
$('#imageInput').on('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#previewImage').attr('src', e.target.result);
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});

// Delete image
$('#deleteImageBtn').on('click', function() {
    swal({
        title: "Hapus Gambar Game?",
        text: "Gambar game " + currentGameName + " akan dihapus dan semua layanannya akan menggunakan placeholder default.",
        icon: "warning",
        buttons: {
            cancel: "Batal",
            confirm: "Ya, Hapus!"
        },
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var form = $('<form>', {
                'method': 'POST',
                'action': '/admin/game-services/delete-image/' + encodeURIComponent(currentGameName)
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

    // Copy debug info to clipboard
    window.copyDebugInfo = function() {
        var debugContent = document.getElementById('debugContent').innerText;
        var tempInput = document.createElement('textarea');
        tempInput.value = 'IP Server: 195.88.211.226\\nTimestamp: {{ now()->format("Y-m-d H:i:s") }}\\n\\nAPI Responses:\\n' + debugContent;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        swal('Berhasil!', 'Debug info telah disalin ke clipboard. Silakan paste ke chat CS VIP Reseller.', 'success');
    };

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
                    'action': '/admin/game-services/' + serviceId
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