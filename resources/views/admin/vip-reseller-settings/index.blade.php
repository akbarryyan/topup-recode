@extends('admin.layouts.app')

@section('title', 'Konfigurasi VIP Reseller')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Konfigurasi VIP Reseller</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item active">VIP Reseller</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header"><h4>Credensial API</h4></div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <ul class="mb-0 pl-3">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            @if(session('profile_error'))
                                <div class="alert alert-danger alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        {{ session('profile_error') }}
                                    </div>
                                </div>
                            @endif

                            @if(session('profile_message'))
                                <div class="alert alert-info alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        {{ session('profile_message') }}
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.vip-reseller-settings.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label>API URL <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control" name="api_url" value="{{ old('api_url', $setting->api_url ?? 'https://vip-reseller.co.id/api') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>API ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="api_id" value="{{ old('api_id', $setting->api_id ?? '') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>API Key <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="api_key" value="{{ old('api_key', $setting->api_key ?? '') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Sign <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="sign" value="{{ old('sign', $setting->sign ?? '') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Catatan</label>
                                    <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Gunakan akun cabang A">{{ old('notes', $setting->notes ?? '') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Status</label>
                                    <div class="form-check d-flex align-items-center bg-light rounded px-3 py-2">
                                        <input class="form-check-input position-static mr-2" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $setting->is_active ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label mb-0" for="is_active">Aktifkan integrasi VIP Reseller</label>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap align-items-center">
                                    <button class="btn btn-primary mr-2" type="submit">Simpan Konfigurasi</button>
                                    <button class="btn btn-info" type="submit" form="vip-profile-check-form">Cek Profil VIP</button>
                                </div>
                            </form>

                            <form id="vip-profile-check-form" method="POST" action="{{ route('admin.vip-reseller-settings.check-profile') }}" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header"><h4>Informasi</h4></div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Terakhir diupdate:</strong><br>{{ optional($setting)->updated_at ? $setting->updated_at->format('d M Y H:i') : '-' }}</li>
                                <li class="mb-2"><strong>Status:</strong> 
                                    @if(optional($setting)->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-secondary">Nonaktif</span>
                                    @endif
                                </li>
                                <li class="mb-2"><strong>Petunjuk:</strong>
                                    <p class="text-sm text-muted mb-0">Pastikan IP server Anda sudah di-whitelist di dashboard VIP Reseller. Setelah mengganti data, jalankan ulang proses sync agar kredensial baru digunakan.</p>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h4>Profil VIP Reseller</h4></div>
                        <div class="card-body">
                            @php($profile = session('profile'))
                            @if($profile)
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><strong>Nama Lengkap:</strong><br>{{ $profile['full_name'] ?? '-' }}</li>
                                    <li class="mb-2"><strong>Username:</strong><br>{{ $profile['username'] ?? '-' }}</li>
                                    <li class="mb-2"><strong>Saldo:</strong><br>Rp {{ number_format($profile['balance'] ?? 0, 0, ',', '.') }}</li>
                                    <li class="mb-2"><strong>Poin:</strong><br>{{ $profile['point'] ?? 0 }}</li>
                                    <li class="mb-2"><strong>Level:</strong><br>{{ $profile['level'] ?? '-' }}</li>
                                    <li class="mb-0"><strong>Terdaftar Sejak:</strong><br>{{ $profile['registered'] ?? '-' }}</li>
                                </ul>
                            @else
                                <p class="text-muted mb-0">Klik tombol <strong>Cek Profil VIP</strong> untuk menampilkan informasi akun.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
