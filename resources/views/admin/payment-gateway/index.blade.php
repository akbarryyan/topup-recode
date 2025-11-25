@extends('admin.layouts.app')

@section('title', 'Kelola Payment Gateway')

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
            <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="gateway-config-tab" data-toggle="tab" href="#gateway-config" role="tab">
                        <i class="fas fa-cog"></i> Konfigurasi Gateway
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="payment-methods-tab" data-toggle="tab" href="#payment-methods" role="tab">
                        <i class="fas fa-credit-card"></i> Payment Methods ({{ $paymentMethods->count() }})
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="paymentTabsContent">
                <!-- Tab Konfigurasi Gateway -->
                <div class="tab-pane fade show active" id="gateway-config" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Payment Gateway</h4>
                            <div class="card-header-action d-flex flex-wrap">
                        <button type="button" class="btn btn-success mr-2 mb-2" data-toggle="modal" data-target="#addPaymentGatewayModal">
                            <i class="fas fa-plus"></i> Tambah Payment Gateway
                        </button>
                        <a href="{{ route('admin.payment-gateways.config') }}" class="btn btn-warning mr-2 mb-2">
                            <i class="fas fa-cog"></i> Konfigurasi
                        </a>
                        <div class="btn-group mr-2 mb-2" role="group">
                            <button id="btnFetchMethods" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-download"></i> Lihat Payment Method
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnFetchMethods">
                                <button class="dropdown-item" type="button" id="fetch-duitku-methods" data-toggle="modal" data-target="#paymentMethodsModal">
                                    <i class="fas fa-wallet"></i> Duitku Payment Method
                                </button>
                                <button class="dropdown-item" type="button" id="fetch-tripay-channels" data-toggle="modal" data-target="#tripayChannelsModal">
                                    <i class="fas fa-money-check-alt"></i> Tripay Payment Channel
                                </button>
                            </div>
                        </div>
                        <form id="sync-form" action="{{ route('admin.payment-gateways.sync') }}" method="POST" class="d-inline mb-2">
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
                                    <th>Private Key</th>
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
                                            @if($pg->private_key)
                                                <code>{{ $pg->masked_private_key }}</code>
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
                                                    <input type="checkbox" name="is_active" class="custom-switch-input" {{ $pg->is_active ? 'checked' : '' }}>
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
                                        <td colspan="10" class="text-center">Belum ada data payment gateway.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Payment Methods -->
        <div class="tab-pane fade" id="payment-methods" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Payment Methods</h4>
                    <div class="card-header-action d-flex flex-wrap">
                        <button id="deleteSelectedMethods" type="button" class="btn btn-danger mr-2 mb-2" disabled>
                            <i class="fas fa-trash"></i> Delete Selected (<span id="selected-count">0</span>)
                        </button>
                        <div class="btn-group mr-2 mb-2" role="group">
                            <button id="filterPaymentMethods" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i> Filter Gateway: <span id="current-filter">Semua</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="filterPaymentMethods">
                                <button class="dropdown-item filter-gateway" data-gateway="all" type="button">
                                    <i class="fas fa-list"></i> Semua Gateway
                                </button>
                                <div class="dropdown-divider"></div>
                                @foreach($paymentGateways as $pg)
                                <button class="dropdown-item filter-gateway" data-gateway="{{ $pg->id }}" type="button">
                                    <i class="fas fa-wallet"></i> {{ $pg->name }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        <div class="btn-group mb-2" role="group">
                            <button id="btnAddMethods" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-download"></i> Tambah Payment Method
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnAddMethods">
                                <button class="dropdown-item" type="button" data-toggle="modal" data-target="#paymentMethodsModal">
                                    <i class="fas fa-wallet"></i> Dari Duitku
                                </button>
                                <button class="dropdown-item" type="button" data-toggle="modal" data-target="#tripayChannelsModal">
                                    <i class="fas fa-money-check-alt"></i> Dari Tripay
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($paymentMethods->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Belum ada payment method yang tersimpan. Klik tombol <strong>Tambah Payment Method</strong> untuk mengambil data dari Duitku atau Tripay.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped" id="payment-methods-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="30">
                                            <input type="checkbox" id="select-all-methods">
                                        </th>
                                        <th class="text-center">#</th>
                                        <th>Gateway</th>
                                        <th>Logo</th>
                                        <th>Payment Name</th>
                                        <th>Code</th>
                                        <th>Fee dari API</th>
                                        <th>Fee Customer</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paymentMethods as $pm)
                                        <tr class="payment-method-row" data-gateway-id="{{ $pm->payment_gateway_id }}">
                                            <td class="text-center">
                                                <input type="checkbox" class="method-checkbox" value="{{ $pm->id }}">
                                            </td>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="badge badge-primary">{{ $pm->paymentGateway->name ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @if($pm->image_url)
                                                    <img src="{{ $pm->image_url }}" alt="{{ $pm->name }}" width="60" class="rounded">
                                                @else
                                                    <span class="badge badge-secondary">No Icon</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ $pm->name }}</strong></td>
                                            <td><code>{{ $pm->code }}</code></td>
                                            <td>Rp {{ number_format($pm->total_fee, 0, ',', '.') }}</td>
                                            <td>
                                                @if($pm->fee_customer_flat > 0 || $pm->fee_customer_percent > 0)
                                                    {{ $pm->formatted_customer_fee }}
                                                @else
                                                    <span class="badge badge-success">Gratis</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <form class="toggle-method-form" action="{{ route('admin.payment-methods.toggle', $pm->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <label class="custom-switch mt-2">
                                                        <input type="checkbox" name="is_active" class="custom-switch-input" {{ $pm->is_active ? 'checked' : '' }}>
                                                        <span class="custom-switch-indicator"></span>
                                                    </label>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editMethodModal{{ $pm->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.payment-methods.destroy', $pm->id) }}" method="POST" class="d-inline delete-method-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
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
                        <label for="private_key">Private Key</label>
                        <input type="text" class="form-control" id="private_key" name="private_key" placeholder="Masukkan Private Key (opsional)">
                        <small class="form-text text-muted">Private Key untuk gateway tertentu (misal: Tripay). Akan disimpan ter-enkripsi</small>
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
                        <label for="edit_private_key{{ $pg->id }}">Private Key</label>
                        <input type="text" class="form-control" id="edit_private_key{{ $pg->id }}" name="private_key" placeholder="Kosongkan jika tidak ingin mengubah">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah Private Key yang sudah ada</small>
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

<!-- Modal Payment Methods -->
<div class="modal fade" id="paymentMethodsModal" tabindex="-1" role="dialog" aria-labelledby="paymentMethodsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentMethodsModalLabel">
                    <i class="fas fa-list"></i> Payment Methods dari Duitku
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="payment-methods-loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-3">Mengambil data dari Duitku...</p>
                </div>
                <div id="payment-methods-error" class="alert alert-danger" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i> <span id="error-message"></span>
                </div>
                <div id="payment-methods-content" style="display: none;">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Pilih payment method yang ingin Anda simpan ke database.
                        <span class="badge badge-warning">Sudah Ada</span> menandakan payment method sudah tersimpan di database.
                    </div>
                    <div class="mb-3">
                        <button type="button" id="select-all" class="btn btn-sm btn-secondary">
                            <i class="fas fa-check-square"></i> Pilih Semua
                        </button>
                        <button type="button" id="deselect-all" class="btn btn-sm btn-secondary">
                            <i class="fas fa-square"></i> Hapus Semua Pilihan
                        </button>
                        <button type="button" id="select-new" class="btn btn-sm btn-success">
                            <i class="fas fa-check"></i> Pilih Yang Baru Saja
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="select-all-checkbox">
                                    </th>
                                    <th>Logo</th>
                                    <th>Payment Name</th>
                                    <th>Payment Method</th>
                                    <th>Total Fee</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="payment-methods-list">
                                <!-- Data akan diisi via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
                <button type="button" id="save-selected-methods" class="btn btn-success" style="display: none;">
                    <i class="fas fa-save"></i> Simpan Terpilih
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tripay Payment Channels -->
<div class="modal fade" id="tripayChannelsModal" tabindex="-1" role="dialog" aria-labelledby="tripayChannelsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tripayChannelsModalLabel">
                    <i class="fas fa-list"></i> Payment Channels dari Tripay
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="tripay-channels-loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-3">Mengambil data dari Tripay...</p>
                </div>
                <div id="tripay-channels-error" class="alert alert-danger" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i> <span id="tripay-error-message"></span>
                </div>
                <div id="tripay-channels-content" style="display: none;">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Pilih payment channel yang ingin Anda simpan ke database.
                        <span class="badge badge-warning">Sudah Ada</span> menandakan channel sudah tersimpan di database.
                    </div>
                    <div class="mb-3">
                        <button type="button" id="tripay-select-all" class="btn btn-sm btn-secondary">
                            <i class="fas fa-check-square"></i> Pilih Semua
                        </button>
                        <button type="button" id="tripay-deselect-all" class="btn btn-sm btn-secondary">
                            <i class="fas fa-square"></i> Hapus Semua Pilihan
                        </button>
                        <button type="button" id="tripay-select-new" class="btn btn-sm btn-success">
                            <i class="fas fa-check"></i> Pilih Yang Baru Saja
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="tripay-select-all-checkbox">
                                    </th>
                                    <th>Logo</th>
                                    <th>Channel Name</th>
                                    <th>Code</th>
                                    <th>Group</th>
                                    <th>Fee Merchant</th>
                                    <th>Fee Customer</th>
                                    <th>Status</th>
                                    <th>Active</th>
                                </tr>
                            </thead>
                            <tbody id="tripay-channels-list">
                                <!-- Data akan diisi via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
                <button type="button" id="save-selected-channels" class="btn btn-success" style="display: none;">
                    <i class="fas fa-save"></i> Simpan Terpilih
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert@2.1.2/dist/sweetalert.min.js"></script>

    @if(session('success'))
    <script>
        swal({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            timer: 3000,
            buttons: false
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        swal({
            title: 'Gagal!',
            text: '{{ session('error') }}',
            icon: 'error',
            timer: 3000,
            buttons: false
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

            // Handle Delete Payment Method Button
            $('.delete-method-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                
                swal({
                    title: 'Hapus Payment Method?',
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

            // Handle toggle checkbox change with confirmation
            $('.toggle-form .custom-switch-input').on('change', function(e) {
                const checkbox = $(this);
                const form = checkbox.closest('.toggle-form');
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
                        // Unbind the change event to prevent infinite loop, then submit
                        checkbox.off('change');
                        form.submit();
                    } else {
                        // Revert checkbox state if user cancels
                        checkbox.prop('checked', !isChecked);
                    }
                });
            });

            // Handle payment method toggle checkbox change with confirmation
            $('.toggle-method-form .custom-switch-input').on('change', function(e) {
                const checkbox = $(this);
                const form = checkbox.closest('.toggle-method-form');
                const isChecked = checkbox.is(':checked');
                const status = isChecked ? 'mengaktifkan' : 'menonaktifkan';

                swal({
                    title: 'Ubah Status?',
                    text: `Anda yakin ingin ${status} payment method ini?`,
                    icon: 'warning',
                    buttons: ['Batal', 'Ya, Ubah!'],
                    dangerMode: true,
                }).then((willChange) => {
                    if (willChange) {
                        // Unbind the change event to prevent infinite loop, then submit
                        checkbox.off('change');
                        form.submit();
                    } else {
                        // Revert checkbox state if user cancels
                        checkbox.prop('checked', !isChecked);
                    }
                });
            });


            // Handle Fetch Payment Methods
            let paymentMethodsData = [];

            $('#paymentMethodsModal').on('shown.bs.modal', function() {
                // Reset modal content
                $('#payment-methods-loading').show();
                $('#payment-methods-error').hide();
                $('#payment-methods-content').hide();
                $('#save-selected-methods').hide();
                $('#payment-methods-list').empty();

                // Fetch payment methods from API
                $.ajax({
                    url: '{{ route('admin.payment-gateways.fetch-methods') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            paymentMethodsData = response.data;
                            displayPaymentMethods(paymentMethodsData);
                            $('#payment-methods-loading').hide();
                            $('#payment-methods-content').show();
                            $('#save-selected-methods').show();
                        } else {
                            showError(response.message);
                        }
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat mengambil data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showError(message);
                    }
                });
            });

            function showError(message) {
                $('#payment-methods-loading').hide();
                $('#error-message').text(message);
                $('#payment-methods-error').show();
            }

            function displayPaymentMethods(methods) {
                let html = '';
                methods.forEach(function(method) {
                    let statusBadge = method.exists 
                        ? '<span class="badge badge-warning">Sudah Ada</span>' 
                        : '<span class="badge badge-success">Baru</span>';
                    
                    html += `
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="payment-method-checkbox" 
                                    data-method='${JSON.stringify(method)}' 
                                    ${method.exists ? '' : 'checked'}>
                            </td>
                            <td>
                                <img src="${method.paymentImage}" alt="${method.paymentName}" width="60" class="rounded">
                            </td>
                            <td><strong>${method.paymentName}</strong></td>
                            <td><code>${method.paymentMethod}</code></td>
                            <td>Rp ${parseInt(method.totalFee).toLocaleString('id-ID')}</td>
                            <td>${statusBadge}</td>
                        </tr>
                    `;
                });
                $('#payment-methods-list').html(html);
            }

            // Select All Checkbox
            $('#select-all-checkbox').on('change', function() {
                $('.payment-method-checkbox').prop('checked', $(this).is(':checked'));
            });

            // Select All Button
            $('#select-all').on('click', function() {
                $('.payment-method-checkbox').prop('checked', true);
                $('#select-all-checkbox').prop('checked', true);
            });

            // Deselect All Button
            $('#deselect-all').on('click', function() {
                $('.payment-method-checkbox').prop('checked', false);
                $('#select-all-checkbox').prop('checked', false);
            });

            // Select New Only Button
            $('#select-new').on('click', function() {
                $('.payment-method-checkbox').each(function() {
                    let method = JSON.parse($(this).attr('data-method'));
                    $(this).prop('checked', !method.exists);
                });
                $('#select-all-checkbox').prop('checked', false);
            });

            // Save Selected Methods
            $('#save-selected-methods').on('click', function() {
                let selectedMethods = [];
                $('.payment-method-checkbox:checked').each(function() {
                    selectedMethods.push(JSON.parse($(this).attr('data-method')));
                });

                if (selectedMethods.length === 0) {
                    swal({
                        title: 'Peringatan!',
                        text: 'Pilih minimal 1 payment method untuk disimpan.',
                        icon: 'warning',
                        button: 'OK'
                    });
                    return;
                }

                // Show loading
                $(this).addClass('btn-progress').prop('disabled', true);

                $.ajax({
                    url: '{{ route('admin.payment-gateways.save-methods') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        payment_methods: selectedMethods
                    },
                    success: function(response) {
                        console.log('Duitku Save Response:', response);
                        if (response.success) {
                            // Remove loading state
                            $('#save-selected-methods').removeClass('btn-progress').prop('disabled', false);
                            
                            // Close modal
                            $('#paymentMethodsModal').modal('hide');
                            
                            // Show success and reload
                            swal({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                buttons: false
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            $('#save-selected-methods').removeClass('btn-progress').prop('disabled', false);
                            swal({
                                title: 'Gagal!',
                                text: response.message,
                                icon: 'error',
                                button: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Duitku Save Error:', xhr);
                        let message = 'Terjadi kesalahan saat menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        $('#save-selected-methods').removeClass('btn-progress').prop('disabled', false);
                        swal({
                            title: 'Gagal!',
                            text: message,
                            icon: 'error',
                            button: 'OK'
                        });
                    }
                });
            });

            // ========== TRIPAY PAYMENT CHANNELS ==========
            let tripayChannelsData = [];

            $('#tripayChannelsModal').on('shown.bs.modal', function() {
                // Reset modal content
                $('#tripay-channels-loading').show();
                $('#tripay-channels-error').hide();
                $('#tripay-channels-content').hide();
                $('#save-selected-channels').hide();
                $('#tripay-channels-list').empty();

                // Fetch payment channels from Tripay API
                $.ajax({
                    url: '{{ route('admin.payment-gateways.fetch-tripay-channels') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            tripayChannelsData = response.data;
                            displayTripayChannels(tripayChannelsData);
                            $('#tripay-channels-loading').hide();
                            $('#tripay-channels-content').show();
                            $('#save-selected-channels').show();
                        } else {
                            showTripayError(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Tripay Error:', xhr);
                        let message = 'Terjadi kesalahan saat mengambil data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.status === 0) {
                            message = 'Tidak dapat terhubung ke server. Pastikan koneksi internet Anda stabil.';
                        } else {
                            message = 'HTTP Error ' + xhr.status + ': ' + xhr.statusText;
                        }
                        showTripayError(message);
                    }
                });
            });

            function showTripayError(message) {
                $('#tripay-channels-loading').hide();
                $('#tripay-error-message').text(message);
                $('#tripay-channels-error').show();
            }

            function displayTripayChannels(channels) {
                let html = '';
                channels.forEach(function(channel) {
                    let statusBadge = channel.exists 
                        ? '<span class="badge badge-warning">Sudah Ada</span>' 
                        : '<span class="badge badge-success">Baru</span>';
                    
                    let activeBadge = channel.active 
                        ? '<span class="badge badge-success">Active</span>' 
                        : '<span class="badge badge-danger">Inactive</span>';
                    
                    html += `
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="tripay-channel-checkbox" 
                                    data-channel='${JSON.stringify(channel)}' 
                                    ${channel.exists ? '' : 'checked'}>
                            </td>
                            <td>
                                <img src="${channel.icon_url}" alt="${channel.name}" width="60" class="rounded">
                            </td>
                            <td><strong>${channel.name}</strong></td>
                            <td><code>${channel.code}</code></td>
                            <td><span class="badge badge-info">${channel.group}</span></td>
                            <td>${channel.fee_merchant_display}</td>
                            <td>${channel.fee_customer_display}</td>
                            <td>${statusBadge}</td>
                            <td>${activeBadge}</td>
                        </tr>
                    `;
                });
                $('#tripay-channels-list').html(html);
            }

            // Tripay Select All Checkbox
            $('#tripay-select-all-checkbox').on('change', function() {
                $('.tripay-channel-checkbox').prop('checked', $(this).is(':checked'));
            });

            // Tripay Select All Button
            $('#tripay-select-all').on('click', function() {
                $('.tripay-channel-checkbox').prop('checked', true);
                $('#tripay-select-all-checkbox').prop('checked', true);
            });

            // Tripay Deselect All Button
            $('#tripay-deselect-all').on('click', function() {
                $('.tripay-channel-checkbox').prop('checked', false);
                $('#tripay-select-all-checkbox').prop('checked', false);
            });

            // Tripay Select New Only Button
            $('#tripay-select-new').on('click', function() {
                $('.tripay-channel-checkbox').each(function() {
                    let channel = JSON.parse($(this).attr('data-channel'));
                    $(this).prop('checked', !channel.exists);
                });
                $('#tripay-select-all-checkbox').prop('checked', false);
            });

            // ========== FILTER PAYMENT METHODS BY GATEWAY ==========
            $('.filter-gateway').on('click', function() {
                const gatewayId = $(this).data('gateway');
                const gatewayName = $(this).text().trim();
                
                $('#current-filter').text(gatewayName);
                
                if (gatewayId === 'all') {
                    $('.payment-method-row').show();
                } else {
                    $('.payment-method-row').hide();
                    $(`.payment-method-row[data-gateway-id="${gatewayId}"]`).show();
                }
                
                // Update numbering (skip checkbox column)
                let visibleIndex = 1;
                $('.payment-method-row:visible').each(function() {
                    $(this).find('td:eq(1)').text(visibleIndex++);
                });
            });

            // ========== MASS DELETE PAYMENT METHODS ==========
            // Handle "Select All" checkbox
            $('#select-all-methods').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.payment-method-row:visible .method-checkbox').prop('checked', isChecked);
                updateSelectedCount();
            });

            // Handle individual checkbox changes
            $(document).on('change', '.method-checkbox', function() {
                updateSelectedCount();
                
                // Update "select all" checkbox state
                const totalVisible = $('.payment-method-row:visible .method-checkbox').length;
                const totalChecked = $('.payment-method-row:visible .method-checkbox:checked').length;
                $('#select-all-methods').prop('checked', totalVisible > 0 && totalVisible === totalChecked);
            });

            // Update selected count and button state
            function updateSelectedCount() {
                const count = $('.method-checkbox:checked').length;
                $('#selected-count').text(count);
                $('#deleteSelectedMethods').prop('disabled', count === 0);
            }

            // Handle Delete Selected button
            $('#deleteSelectedMethods').on('click', function() {
                const selectedIds = [];
                $('.method-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    swal({
                        title: 'Peringatan!',
                        text: 'Pilih minimal 1 payment method untuk dihapus.',
                        icon: 'warning',
                        button: 'OK'
                    });
                    return;
                }

                swal({
                    title: 'Hapus Payment Methods?',
                    text: `Anda yakin ingin menghapus ${selectedIds.length} payment method yang dipilih? Data yang dihapus tidak dapat dikembalikan!`,
                    icon: 'warning',
                    buttons: ['Batal', 'Ya, Hapus!'],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        // Show loading
                        $('#deleteSelectedMethods').addClass('btn-progress').prop('disabled', true);

                        $.ajax({
                            url: '{{ route("admin.payment-methods.mass-destroy") }}',
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            data: {
                                ids: selectedIds
                            },
                            success: function(response) {
                                if (response.success) {
                                    swal({
                                        title: 'Berhasil!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 2000,
                                        buttons: false
                                    }).then(function() {
                                        location.reload();
                                    });
                                } else {
                                    $('#deleteSelectedMethods').removeClass('btn-progress').prop('disabled', false);
                                    swal({
                                        title: 'Gagal!',
                                        text: response.message || 'Terjadi kesalahan saat menghapus data.',
                                        icon: 'error',
                                        button: 'OK'
                                    });
                                }
                            },
                            error: function(xhr) {
                                $('#deleteSelectedMethods').removeClass('btn-progress').prop('disabled', false);
                                let message = 'Terjadi kesalahan saat menghapus data.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                }
                                swal({
                                    title: 'Gagal!',
                                    text: message,
                                    icon: 'error',
                                    button: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // Save Selected Tripay Channels
            $('#save-selected-channels').on('click', function() {
                let selectedChannels = [];
                $('.tripay-channel-checkbox:checked').each(function() {
                    let channel = JSON.parse($(this).attr('data-channel'));
                    selectedChannels.push(channel);
                });

                if (selectedChannels.length === 0) {
                    swal({
                        title: 'Peringatan!',
                        text: 'Pilih minimal 1 payment channel untuk disimpan.',
                        icon: 'warning',
                        button: 'OK'
                    });
                    return;
                }

                // Debug: log data yang akan dikirim
                console.log('Selected Channels:', selectedChannels);
                console.log('Sample Channel:', selectedChannels[0]);

                // Show loading
                $(this).addClass('btn-progress').prop('disabled', true);

                $.ajax({
                    url: '{{ route('admin.payment-gateways.save-tripay-channels') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        payment_channels: selectedChannels
                    },
                    success: function(response) {
                        console.log('Tripay Save Response:', response);
                        if (response.success) {
                            // Remove loading state
                            $('#save-selected-channels').removeClass('btn-progress').prop('disabled', false);
                            
                            // Close modal
                            $('#tripayChannelsModal').modal('hide');
                            
                            // Show success and reload
                            swal({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                buttons: false
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            $('#save-selected-channels').removeClass('btn-progress').prop('disabled', false);
                            swal({
                                title: 'Gagal!',
                                text: response.message,
                                icon: 'error',
                                button: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Tripay Save Error:', xhr);
                        let message = 'Terjadi kesalahan saat menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Handle validation errors
                            let errors = xhr.responseJSON.errors;
                            let errorList = Object.values(errors).flat().join('\n');
                            message = 'Validation Error:\n' + errorList;
                        }
                        $('#save-selected-channels').removeClass('btn-progress').prop('disabled', false);
                        swal({
                            title: 'Gagal!',
                            text: message,
                            icon: 'error',
                            button: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush