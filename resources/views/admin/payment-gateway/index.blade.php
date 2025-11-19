@extends('admin.layouts.app')

@section('title', 'Kelola Payment Gateway')

@push('styles')
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
@endpush

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kelola Payment Gateway</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Kelola Payment Gateway</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Payment Gateway</h4>
                    <div class="card-header-action">
                        <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#addPaymentGatewayModal">
                            <i class="fas fa-plus"></i> Tambah Payment Gateway
                        </button>
                        <a href="{{ route('admin.payment-gateways.config') }}" class="btn btn-warning mr-2">
                            <i class="fas fa-cog"></i> Konfigurasi
                        </a>
                        <form id="sync-form" action="{{ route('admin.payment-gateways.sync') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="button" id="sync-button" class="btn btn-primary">
                                <i class="fas fa-sync"></i> Sinkronisasi Payment Gateway
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Logo</th>
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th>Merchant Code</th>
                                    <th>API Key</th>
                                    <th>Environment</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentGateways as $pg)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            @if($pg->icon_url)
                                                <img src="{{ asset('storage/payment-icons/' . $pg->icon_url) }}" alt="{{ $pg->name }}" width="60" class="rounded">
                                            @else
                                                <span class="badge badge-secondary">No Icon</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $pg->name }}</strong></td>
                                        <td><code>{{ $pg->code }}</code></td>
                                        <td>{{ $pg->merchant_code ?? '-' }}</td>
                                        <td>
                                            @if($pg->api_key)
                                                <code>{{ $pg->masked_api_key }}</code>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pg->environment)
                                                <span class="badge badge-{{ $pg->environment_badge }}">
                                                    {{ ucfirst($pg->environment) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <form class="toggle-form" action="{{ route('admin.payment-gateways.toggle', $pg->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <label class="custom-switch mt-2">
                                                    <input type="checkbox" name="is_active" class="custom-switch-input" {{ $pg->is_active ? 'checked' : '' }} onchange="this.form.submit()">
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                            </form>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal{{ $pg->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.payment-gateways.destroy', $pg->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada data payment gateway.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah Payment Gateway -->
<div class="modal fade" id="addPaymentGatewayModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentGatewayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentGatewayModalLabel">
                    <i class="fas fa-plus-circle"></i> Tambah Payment Gateway
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.payment-gateways.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Payment Gateway <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Contoh: Midtrans" required>
                        <small class="form-text text-muted">Nama payment gateway seperti: Midtrans, Xendit, dll</small>
                    </div>

                    <div class="form-group">
                        <label for="merchant_code">Merchant Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="merchant_code" name="merchant_code" placeholder="Contoh: MID123456" required>
                    </div>

                    <div class="form-group">
                        <label for="api_key">API Key <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="api_key" name="api_key" placeholder="Masukkan API Key" required>
                        <small class="form-text text-muted">API Key akan disimpan secara ter-enkripsi</small>
                    </div>

                    <div class="form-group">
                        <label for="environment">Environment <span class="text-danger">*</span></label>
                        <select class="form-control" id="environment" name="environment" required>
                            <option value="sandbox">Sandbox (Testing)</option>
                            <option value="production">Production (Live)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="icon">Icon Payment Gateway</label>
                        <input type="file" class="form-control" id="icon" name="icon" accept="image/*">
                        <small class="form-text text-muted">Upload logo/icon payment gateway (PNG, JPG, max 2MB)</small>
                    </div>

                    <div class="form-group">
                        <label for="callback_url">Callback URL</label>
                        <input type="url" class="form-control" id="callback_url" name="callback_url" placeholder="https://yourdomain.com/api/callback">
                    </div>

                    <div class="form-group">
                        <label for="return_url">Return URL</label>
                        <input type="url" class="form-control" id="return_url" name="return_url" placeholder="https://yourdomain.com/payment/success">
                    </div>

                    <div class="form-group">
                        <label for="is_active">Status</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Payment Gateway -->
@foreach($paymentGateways as $pg)
<div class="modal fade" id="editModal{{ $pg->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $pg->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $pg->id }}">
                    <i class="fas fa-edit"></i> Edit Payment Gateway: {{ $pg->name }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.payment-gateways.update', $pg->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name{{ $pg->id }}">Nama Payment Gateway <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name{{ $pg->id }}" name="name" value="{{ $pg->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_merchant_code{{ $pg->id }}">Merchant Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_merchant_code{{ $pg->id }}" name="merchant_code" value="{{ $pg->merchant_code }}" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_api_key{{ $pg->id }}">API Key</label>
                        <input type="text" class="form-control" id="edit_api_key{{ $pg->id }}" name="api_key" placeholder="Kosongkan jika tidak ingin mengubah">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah API Key yang sudah ada</small>
                    </div>

                    <div class="form-group">
                        <label for="edit_environment{{ $pg->id }}">Environment <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_environment{{ $pg->id }}" name="environment" required>
                            <option value="sandbox" {{ $pg->environment === 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                            <option value="production" {{ $pg->environment === 'production' ? 'selected' : '' }}>Production (Live)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_icon{{ $pg->id }}">Icon Payment Gateway</label>
                        @if($pg->icon_url)
                            <div class="mb-2">
                                <img src="{{ asset('storage/payment-icons/' . $pg->icon_url) }}" alt="{{ $pg->name }}" width="100" class="rounded">
                                <p class="small text-muted mb-0">Icon saat ini</p>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="edit_icon{{ $pg->id }}" name="icon" accept="image/*">
                        <small class="form-text text-muted">Upload logo/icon baru (PNG, JPG, max 2MB). Kosongkan jika tidak ingin mengubah.</small>
                    </div>

                    <div class="form-group">
                        <label for="edit_callback_url{{ $pg->id }}">Callback URL</label>
                        <input type="url" class="form-control" id="edit_callback_url{{ $pg->id }}" name="callback_url" value="{{ $pg->callback_url }}" placeholder="https://yourdomain.com/api/callback">
                    </div>

                    <div class="form-group">
                        <label for="edit_return_url{{ $pg->id }}">Return URL</label>
                        <input type="url" class="form-control" id="edit_return_url{{ $pg->id }}" name="return_url" value="{{ $pg->return_url }}" placeholder="https://yourdomain.com/payment/success">
                    </div>

                    <div class="form-group">
                        <label for="edit_is_active{{ $pg->id }}">Status</label>
                        <select class="form-control" id="edit_is_active{{ $pg->id }}" name="is_active">
                            <option value="1" {{ $pg->is_active ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$pg->is_active ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    @if(session('success'))
    <script>
        iziToast.success({
            title: 'Berhasil!',
            message: '{{ session('success') }}',
            position: 'topRight'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        iziToast.error({
            title: 'Gagal!',
            message: '{{ session('error') }}',
            position: 'topRight'
        });
    </script>
    @endif

    <script>
        $(document).ready(function() {
            // Handle Sync Button
            $('#sync-button').on('click', function(e) {
                e.preventDefault();
                swal({
                    title: 'Anda yakin?',
                    text: 'Akan melakukan sinkronisasi data payment gateway dari provider. Ini mungkin menimpa perubahan lokal. Lanjutkan?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((willSync) => {
                    if (willSync) {
                        // Show loading indicator
                        $(this).addClass('btn-progress');
                        $('#sync-form').submit();
                    }
                });
            });

            // Handle Delete Button
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                
                swal({
                    title: 'Hapus Payment Gateway?',
                    text: 'Data yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    buttons: ['Batal', 'Ya, Hapus!'],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
            });

            // Handle toggle form submission with confirmation
            $('.toggle-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const checkbox = $(form).find('input[type="checkbox"]');
                const isChecked = checkbox.is(':checked');
                const status = isChecked ? 'mengaktifkan' : 'menonaktifkan';

                swal({
                    title: 'Ubah Status?',
                    text: `Anda yakin ingin ${status} payment gateway ini?`,
                    icon: 'warning',
                    buttons: ['Batal', 'Ya, Ubah!'],
                    dangerMode: true,
                }).then((willChange) => {
                    if (willChange) {
                        form.submit();
                    } else {
                        // Revert checkbox state if user cancels
                        checkbox.prop('checked', !isChecked);
                    }
                });
            });

            // Prevent toggle form submission on initial load change
            $('.custom-switch-input').on('change', function(e) {
                $(this).closest('form').submit();
            });
        });
    </script>
@endpush