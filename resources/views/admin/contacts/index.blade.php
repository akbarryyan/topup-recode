@extends('admin.layouts.app')

@section('title', 'Kelola Contact Us')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kelola Contact Us</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Kelola Contact Us</div>
            </div>
        </div>
    
        <div class="section-body">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Contact</h4>
                            </div>
                            <div class="card-body">
                                {{ $stats['total'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pending</h4>
                            </div>
                            <div class="card-body">
                                {{ $stats['pending'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-spinner"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>In Progress</h4>
                            </div>
                            <div class="card-body">
                                {{ $stats['in_progress'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Resolved</h4>
                            </div>
                            <div class="card-body">
                                {{ $stats['resolved'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Contact Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Contact</h4>
                        </div>
                        <div class="card-body">
                            <!-- Filters -->
                            <form method="GET" action="{{ route('admin.contacts.index') }}" class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control" onchange="this.form.submit()">
                                                <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Search</label>
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control" placeholder="Cari nama, whatsapp, atau pesan..." value="{{ request('search') }}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                                    @if(request('search') || request('status') != 'all')
                                                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary"><i class="fas fa-redo"></i></a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
    
                            <!-- Success/Error Messages -->
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
    
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tipe</th>
                                            <th>Nama</th>
                                            <th>WhatsApp</th>
                                            <th>Pesan</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($contacts as $contact)
                                        <tr>
                                            <td>{{ $contact->id }}</td>
                                            <td><span class="badge badge-info">{{ ucfirst($contact->type) }}</span></td>
                                            <td>{{ $contact->name }}</td>
                                            <td>
                                                <a href="https://wa.me/{{ $contact->whatsapp }}" target="_blank" class="text-success">
                                                    <i class="fab fa-whatsapp"></i> {{ $contact->whatsapp }}
                                                </a>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info view-message" data-id="{{ $contact->id }}" data-message="{{ $contact->message }}">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </button>
                                            </td>
                                            <td>{!! $contact->status_badge !!}</td>
                                            <td>{{ $contact->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
                                                        Aksi
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <button class="dropdown-item view-detail" data-id="{{ $contact->id }}">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </button>
                                                        <div class="dropdown-divider"></div>
                                                        <h6 class="dropdown-header">Update Status</h6>
                                                        <form action="{{ route('admin.contacts.update-status', $contact->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="pending">
                                                            <button type="submit" class="dropdown-item {{ $contact->status == 'pending' ? 'active' : '' }}">
                                                                <i class="fas fa-clock"></i> Pending
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.contacts.update-status', $contact->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="in_progress">
                                                            <button type="submit" class="dropdown-item {{ $contact->status == 'in_progress' ? 'active' : '' }}">
                                                                <i class="fas fa-spinner"></i> In Progress
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.contacts.update-status', $contact->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="resolved">
                                                            <button type="submit" class="dropdown-item {{ $contact->status == 'resolved' ? 'active' : '' }}">
                                                                <i class="fas fa-check-circle"></i> Resolved
                                                            </button>
                                                        </form>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus contact ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data contact</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
    
                            <!-- Pagination -->
                            <div class="mt-3">
                                {{ $contacts->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detailContent">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-3x"></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Message Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pesan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="messageContent">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // View detail
    $('.view-detail').click(function() {
        const id = $(this).data('id');
        $('#detailContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i></div>');
        $('#detailModal').modal('show');

        $.get(`{{ route('admin.contacts.index') }}/${id}`, function(data) {
            let statusBadge = '';
            if (data.status === 'pending') {
                statusBadge = '<span class="badge badge-warning">Pending</span>';
            } else if (data.status === 'in_progress') {
                statusBadge = '<span class="badge badge-info">In Progress</span>';
            } else if (data.status === 'resolved') {
                statusBadge = '<span class="badge badge-success">Resolved</span>';
            }

            const html = `
                <table class="table table-bordered">
                    <tr>
                        <th width="200">ID</th>
                        <td>${data.id}</td>
                    </tr>
                    <tr>
                        <th>Tipe</th>
                        <td><span class="badge badge-info">${data.type.charAt(0).toUpperCase() + data.type.slice(1)}</span></td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>${data.name}</td>
                    </tr>
                    <tr>
                        <th>WhatsApp</th>
                        <td>
                            <a href="https://wa.me/${data.whatsapp}" target="_blank" class="btn btn-sm btn-success">
                                <i class="fab fa-whatsapp"></i> ${data.whatsapp}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Pesan</th>
                        <td>${data.message}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>${statusBadge}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Submit</th>
                        <td>${new Date(data.created_at).toLocaleString('id-ID')}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>${new Date(data.updated_at).toLocaleString('id-ID')}</td>
                    </tr>
                </table>
            `;
            $('#detailContent').html(html);
        }).fail(function() {
            $('#detailContent').html('<div class="alert alert-danger">Gagal memuat data</div>');
        });
    });

    // View message
    $('.view-message').click(function() {
        const message = $(this).data('message');
        $('#messageContent').html(`<p>${message}</p>`);
        $('#messageModal').modal('show');
    });
});
</script>
@endpush
