@extends('admin.layouts.app')

@section('title', 'Profil Admin')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profil Admin</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item active">Profil</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header"><h4>Informasi Akun</h4></div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.profile.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>No. Telepon</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $profile->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Posisi / Jabatan</label>
                                    <input type="text" class="form-control @error('position') is-invalid @enderror" name="position" value="{{ old('position', $profile->position) }}">
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address', $profile->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Bio Singkat</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror" name="bio" rows="4">{{ old('bio', $profile->bio) }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header"><h4>Ringkasan</h4></div>
                        <div class="card-body text-center">
                            <img src="{{ asset('assets/img/avatar/avatar-1.png') }}" alt="Avatar" class="rounded-circle mb-3" width="90" height="90">
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-0">{{ $profile->position ?? 'Administrator' }}</p>
                        </div>
                        <div class="card-body border-top">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Email:</strong><br>{{ $user->email }}</li>
                                <li class="mb-2"><strong>Telepon:</strong><br>{{ $profile->phone ?? '-' }}</li>
                                <li class="mb-0"><strong>Alamat:</strong><br>{{ $profile->address ?? '-' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
