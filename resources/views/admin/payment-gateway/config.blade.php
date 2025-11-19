@extends('admin.layouts.app')

@section('title', 'Konfigurasi Payment Gateway')

@push('styles')
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
@endpush

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <a href="{{ route('admin.payment-gateways.index') }}" class="btn btn-icon btn-primary mr-3"><i class="fas fa-arrow-left"></i></a>
            <h1>Konfigurasi Payment Gateway</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.payment-gateways.index') }}">Payment Gateway</a></div>
                <div class="breadcrumb-item">Konfigurasi</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Konfigurasi Duitku</h4>
                        </div>
                        <form action="{{ route('admin.payment-gateways.config.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong><i class="fas fa-info-circle"></i> Informasi</strong><br>
                                    Konfigurasi ini akan digunakan untuk integrasi dengan Duitku Payment Gateway.<br>
                                    API Key akan disimpan secara ter-enkripsi di database.
                                </div>

                                <div class="form-group">
                                    <label for="merchant_code">Merchant Code <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('merchant_code') is-invalid @enderror" 
                                           id="merchant_code" 
                                           name="merchant_code" 
                                           value="{{ old('merchant_code', $duitkuConfig->merchant_code ?? '') }}" 
                                           placeholder="Contoh: DS26199"
                                           required>
                                    @error('merchant_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Kode merchant yang diberikan oleh Duitku
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="api_key">API Key <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('api_key') is-invalid @enderror" 
                                               id="api_key" 
                                               name="api_key" 
                                               value="{{ old('api_key', $duitkuConfig->api_key ?? '') }}" 
                                               placeholder="Masukkan API Key Duitku"
                                               required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="toggleApiKey">
                                                <i class="fas fa-eye" id="eyeIcon"></i>
                                            </button>
                                        </div>
                                        @error('api_key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        API Key akan disimpan secara ter-enkripsi
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="environment">Environment <span class="text-danger">*</span></label>
                                    <select class="form-control @error('environment') is-invalid @enderror" 
                                            id="environment" 
                                            name="environment" 
                                            required>
                                        <option value="sandbox" {{ old('environment', $duitkuConfig->environment ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>
                                            Sandbox (Testing)
                                        </option>
                                        <option value="production" {{ old('environment', $duitkuConfig->environment ?? '') == 'production' ? 'selected' : '' }}>
                                            Production (Live)
                                        </option>
                                    </select>
                                    @error('environment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Pilih Sandbox untuk testing, Production untuk live
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="callback_url">Callback URL</label>
                                    <input type="url" 
                                           class="form-control @error('callback_url') is-invalid @enderror" 
                                           id="callback_url" 
                                           name="callback_url" 
                                           value="{{ old('callback_url', $duitkuConfig->callback_url ?? url('/api/payment/callback')) }}" 
                                           placeholder="https://yourdomain.com/api/payment/callback">
                                    @error('callback_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        URL untuk menerima notifikasi pembayaran dari Duitku
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="return_url">Return URL</label>
                                    <input type="url" 
                                           class="form-control @error('return_url') is-invalid @enderror" 
                                           id="return_url" 
                                           name="return_url" 
                                           value="{{ old('return_url', $duitkuConfig->return_url ?? url('/payment/success')) }}" 
                                           placeholder="https://yourdomain.com/payment/success">
                                    @error('return_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        URL redirect setelah pembayaran selesai
                                    </small>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ route('admin.payment-gateways.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Konfigurasi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Status Konfigurasi</h4>
                        </div>
                        <div class="card-body">
                            @if($duitkuConfig && $duitkuConfig->merchant_code && $duitkuConfig->api_key)
                                <div class="alert alert-success mb-3">
                                    <i class="fas fa-check-circle"></i> Konfigurasi Aktif
                                </div>
                                
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Merchant Code:</strong></td>
                                        <td>{{ $duitkuConfig->merchant_code }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>API Key:</strong></td>
                                        <td>{{ $duitkuConfig->masked_api_key }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Environment:</strong></td>
                                        <td>
                                            <span class="badge badge-{{ $duitkuConfig->environment_badge }}">
                                                {{ ucfirst($duitkuConfig->environment) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge badge-success">Aktif</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Terakhir Update:</strong></td>
                                        <td>{{ $duitkuConfig->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            @else
                                <div class="alert alert-warning mb-3">
                                    <i class="fas fa-exclamation-triangle"></i> Konfigurasi Belum Lengkap
                                </div>
                                <p class="text-muted">
                                    Silakan lengkapi konfigurasi Merchant Code dan API Key untuk mengaktifkan integrasi dengan Duitku.
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Panduan</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-2">
                                <strong>Mendapatkan Kredensial:</strong>
                            </p>
                            <ol class="text-muted small pl-3">
                                <li>Login ke Dashboard Duitku</li>
                                <li>Buka menu Settings → API</li>
                                <li>Copy Merchant Code dan API Key</li>
                                <li>Paste ke form konfigurasi</li>
                            </ol>
                            <hr>
                            <p class="text-muted mb-2">
                                <strong>Link Penting:</strong>
                            </p>
                            <a href="https://sandbox.duitku.com" target="_blank" class="btn btn-sm btn-outline-primary btn-block mb-2">
                                <i class="fas fa-external-link-alt"></i> Sandbox Dashboard
                            </a>
                            <a href="https://passport.duitku.com" target="_blank" class="btn btn-sm btn-outline-success btn-block mb-2">
                                <i class="fas fa-external-link-alt"></i> Production Dashboard
                            </a>
                            <a href="https://docs.duitku.com" target="_blank" class="btn btn-sm btn-outline-info btn-block">
                                <i class="fas fa-book"></i> Dokumentasi API
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>

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
        // Toggle password visibility
        document.getElementById('toggleApiKey').addEventListener('click', function() {
            const apiKeyInput = document.getElementById('api_key');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (apiKeyInput.type === 'password') {
                apiKeyInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                apiKeyInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
@endpush
