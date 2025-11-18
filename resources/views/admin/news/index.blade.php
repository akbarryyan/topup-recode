@extends('admin.layouts.app')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kelola Berita</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Kelola Berita</div>
            </div>
        </div>
        
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Berita</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Berita
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filter Form -->
                            <form method="GET" class="mb-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="search" class="form-control" placeholder="Cari berita..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="status" class="form-control">
                                            <option value="">Semua Status</option>
                                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary btn-block">
                                            <i class="fas fa-redo"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
        
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
        
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th width="50">#</th>
                                            <th width="100">Gambar</th>
                                            <th>Judul</th>
                                            <th width="120">Status</th>
                                            <th width="150">Tanggal Publish</th>
                                            <th width="150">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($news as $item)
                                            <tr>
                                                <td>{{ ($news->currentPage() - 1) * $news->perPage() + $loop->iteration }}</td>
                                                <td>
                                                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                                </td>
                                                <td>
                                                    <strong>{{ $item->title }}</strong>
                                                    @if($item->excerpt)
                                                        <br>
                                                        <small class="text-muted">{{ Str::limit($item->excerpt, 60) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->status === 'published')
                                                        <span class="badge badge-success">Published</span>
                                                    @else
                                                        <span class="badge badge-warning">Draft</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $item->published_at ? $item->published_at->format('d M Y H:i') : '-' }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteNews({{ $item->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.news.destroy', $item->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data berita</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
        
                            <div class="mt-3">
                                {{ $news->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteNews(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Berita ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endsection