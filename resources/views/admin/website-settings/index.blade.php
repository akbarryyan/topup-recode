@extends('admin.layouts.app')

@section('title', 'Kelola Website')

@push('styles')
<style>
    .preview-image {
        max-width: 200px;
        max-height: 200px;
        object-fit: contain;
        border: 2px solid #e3e6f0;
        border-radius: 8px;
        padding: 10px;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kelola Website</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Kelola Website</div>
            </div>
        </div>
    
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pengaturan Website</h4>
                        </div>
                        <form action="{{ route('admin.website-settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible show fade">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert">
                                                <span>&times;</span>
                                            </button>
                                            {{ session('success') }}
                                        </div>
                                    </div>
                                @endif
    
                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible show fade">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert">
                                                <span>&times;</span>
                                            </button>
                                            {{ session('error') }}
                                        </div>
                                    </div>
                                @endif
    
                                <!-- Logo Website -->
                                <div class="form-group">
                                    <label>Logo Website</label>
                                    <div class="mb-3">
                                        @if(isset($settings['website_logo']) && $settings['website_logo']->value)
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $settings['website_logo']->image_url }}" alt="Current Logo" class="preview-image" id="currentLogo">
                                                <button type="button" class="btn btn-danger btn-sm" id="deleteLogo">
                                                    <i class="fas fa-trash"></i> Hapus Logo
                                                </button>
                                            </div>
                                        @else
                                            <div class="text-muted">Belum ada logo</div>
                                        @endif
                                    </div>
                                    <input type="file" name="website_logo" class="form-control @error('website_logo') is-invalid @enderror" id="logoInput" accept="image/*">
                                    <small class="form-text text-muted">Format: JPG, PNG, GIF, SVG. Maksimal 2MB.</small>
                                    @error('website_logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2">
                                        <img id="logoPreview" style="max-width: 200px; display: none;" class="preview-image">
                                    </div>
                                </div>
    
                                <!-- Nama Website -->
                                <div class="form-group">
                                    <label for="website_name">Nama Website <span class="text-danger">*</span></label>
                                    <input type="text" name="website_name" id="website_name" 
                                        class="form-control @error('website_name') is-invalid @enderror" 
                                        value="{{ old('website_name', $settings['website_name']->value ?? '') }}" 
                                        required>
                                    @error('website_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
    
                                <!-- Deskripsi Website -->
                                <div class="form-group">
                                    <label for="website_description">Deskripsi Website</label>
                                    <textarea name="website_description" id="website_description" 
                                        class="form-control @error('website_description') is-invalid @enderror" 
                                        rows="4">{{ old('website_description', $settings['website_description']->value ?? '') }}</textarea>
                                    @error('website_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Nomor Telepon -->
                                <div class="form-group">
                                    <label for="website_phone">Nomor Telepon</label>
                                    <input type="text" name="website_phone" id="website_phone" 
                                        class="form-control @error('website_phone') is-invalid @enderror" 
                                        value="{{ old('website_phone', $settings['website_phone']->value ?? '') }}" 
                                        placeholder="Contoh: 6282227113307">
                                    <small class="form-text text-muted">Format: kode negara + nomor (contoh: 6282227113307)</small>
                                    @error('website_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Alamat -->
                                <div class="form-group">
                                    <label for="website_address">Alamat</label>
                                    <textarea name="website_address" id="website_address" 
                                        class="form-control @error('website_address') is-invalid @enderror" 
                                        rows="3">{{ old('website_address', $settings['website_address']->value ?? '') }}</textarea>
                                    <small class="form-text text-muted">Maksimal 500 karakter</small>
                                    @error('website_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Mode Maintenance</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">
                                Aktifkan mode maintenance untuk menampilkan pesan pemeliharaan kepada pengunjung. Halaman admin tetap dapat diakses.
                            </p>
                            <form action="{{ route('admin.website-settings.maintenance') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label class="custom-switch">
                                        <input type="checkbox" name="is_active" value="1" class="custom-switch-input" {{ old('is_active', $maintenanceSetting->is_active) ? 'checked' : '' }}>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Aktifkan Maintenance Mode</span>
                                    </label>
                                    <small class="form-text text-muted">Saat aktif, seluruh pengunjung diarahkan ke halaman maintenance.</small>
                                </div>

                                <div class="form-group">
                                    <label>Judul Pesan <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $maintenanceSetting->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi Pesan</label>
                                    <textarea name="message" rows="3" class="form-control @error('message') is-invalid @enderror">{{ old('message', $maintenanceSetting->message) }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Teks Tombol</label>
                                    <input type="text" name="button_text" class="form-control @error('button_text') is-invalid @enderror" value="{{ old('button_text', $maintenanceSetting->button_text) }}" placeholder="Contoh: Hubungi Kami">
                                    @error('button_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>URL Tombol</label>
                                    <input type="text" name="button_url" class="form-control @error('button_url') is-invalid @enderror" value="{{ old('button_url', $maintenanceSetting->button_url) }}" placeholder="Contoh: https://wa.me/628xxx">
                                    @error('button_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-warning btn-block">
                                    <i class="fas fa-tools"></i> Simpan Pengaturan Maintenance
                                </button>
                            </form>
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
    // Preview logo before upload
    document.getElementById('logoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('logoPreview');
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    // Delete logo
    @if(isset($settings['website_logo']) && $settings['website_logo']->value)
    document.getElementById('deleteLogo').addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin menghapus logo?')) {
            fetch('{{ route('admin.website-settings.delete-logo') }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    iziToast.success({
                        title: 'Berhasil',
                        message: data.message,
                        position: 'topRight'
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    iziToast.error({
                        title: 'Error',
                        message: data.message,
                        position: 'topRight'
                    });
                }
            })
            .catch(error => {
                iziToast.error({
                    title: 'Error',
                    message: 'Terjadi kesalahan saat menghapus logo',
                    position: 'topRight'
                });
            });
        }
    });
    @endif
</script>
@endpush
