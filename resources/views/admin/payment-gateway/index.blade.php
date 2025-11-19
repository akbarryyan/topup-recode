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
                                    <th>Tipe</th>
                                    <th>Grup</th>
                                    <th>Biaya (Rp)</th>
                                    <th>Biaya (%)</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentGateways as $pg)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td><img src="{{ $pg->icon_url }}" alt="{{ $pg->name }}" width="80"></td>
                                        <td>{{ $pg->name }}</td>
                                        <td>{{ $pg->code }}</td>
                                        <td>{{ $pg->type }}</td>
                                        <td>{{ $pg->group }}</td>
                                        <form action="{{ route('admin.payment-gateways.update', $pg->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <td>
                                                <input type="number" name="fee_flat" class="form-control" value="{{ $pg->fee_flat }}" step="1">
                                            </td>
                                            <td>
                                                <input type="number" name="fee_percent" class="form-control" value="{{ $pg->fee_percent }}" step="0.01">
                                            </td>
                                        </form>
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
                                            <button type="button" class="btn btn-sm btn-primary save-btn">Simpan</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Belum ada data payment gateway. Silakan lakukan sinkronisasi.</td>
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

            // Handle Save Button for each row
            $('.save-btn').on('click', function() {
                // Find the form associated with this row and submit it
                $(this).closest('tr').find('form[action*="payment-gateways/"]').submit();
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