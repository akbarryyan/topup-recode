@extends('admin.layouts.app')

@section('title', 'Kelola Banner')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kelola Banner</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Kelola Banner</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Banner</h4>
                            <div class="card-header-action">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#addBannerModal">
                                    <i class="fas fa-plus"></i> Tambah Banner
                                </button>
                            </div>
                        </div>
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

                            <div class="table-responsive">
                                <table class="table table-striped" id="bannerTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Preview</th>
                                            <th>Title</th>
                                            <th>Link</th>
                                            <th>Urutan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($banners as $banner)
                                        <tr>
                                            <td>{{ $banner->id }}</td>
                                            <td>
                                                @if($banner->image)
                                                    <img src="{{ asset('storage/banners/' . $banner->image) }}" 
                                                         alt="{{ $banner->title }}" 
                                                         class="img-thumbnail" 
                                                         style="max-width: 150px; height: auto; object-fit: cover;">
                                                @else
                                                    <span class="badge badge-secondary">No Image</span>
                                                @endif
                                            </td>
                                            <td>{{ $banner->title }}</td>
                                            <td>
                                                @if($banner->link)
                                                    <a href="{{ $banner->link }}" target="_blank" class="text-primary">
                                                        <i class="fas fa-external-link-alt"></i> Link
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info badge-shadow">{{ $banner->sort_order }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <label class="custom-switch mt-2 mb-0">
                                                        <input type="checkbox" 
                                                               class="custom-switch-input toggle-status" 
                                                               data-id="{{ $banner->id }}"
                                                               {{ $banner->is_active ? 'checked' : '' }}>
                                                        <span class="custom-switch-indicator"></span>
                                                    </label>
                                                    <span class="ml-2 badge {{ $banner->is_active ? 'badge-success' : 'badge-danger' }}" id="status-badge-{{ $banner->id }}">
                                                        {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning btn-edit" 
                                                        data-id="{{ $banner->id }}"
                                                        data-title="{{ $banner->title }}"
                                                        data-link="{{ $banner->link }}"
                                                        data-sort="{{ $banner->sort_order }}"
                                                        data-active="{{ $banner->is_active ? 1 : 0 }}"
                                                        data-image="{{ $banner->image }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $banner->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Belum ada banner</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add Banner Modal -->
<div class="modal fade" id="addBannerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Banner</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Gambar Banner <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <small class="form-text text-muted">Format: JPG, PNG, WEBP. Maksimal 2MB</small>
                    </div>
                    <div class="form-group">
                        <label>Link (Optional)</label>
                        <input type="url" name="link" class="form-control" placeholder="https://example.com">
                        <small class="form-text text-muted">Link tujuan ketika banner diklik</small>
                    </div>
                    <div class="form-group">
                        <label>Urutan</label>
                        <input type="number" name="sort_order" class="form-control" value="0" min="0">
                        <small class="form-text text-muted">Semakin kecil angka, semakin awal ditampilkan</small>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_active_add" name="is_active" checked>
                            <label class="custom-control-label" for="is_active_add">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Banner Modal -->
<div class="modal fade" id="editBannerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Banner</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editBannerForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Preview Gambar Saat Ini</label>
                        <div class="text-center p-3 bg-light rounded">
                            <img id="edit_image_preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 100%; max-height: 200px; object-fit: contain;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Gambar Banner (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Format: JPG, PNG, WEBP. Maksimal 2MB</small>
                    </div>
                    <div class="form-group">
                        <label>Link (Optional)</label>
                        <input type="url" name="link" id="edit_link" class="form-control" placeholder="https://example.com">
                    </div>
                    <div class="form-group">
                        <label>Urutan</label>
                        <input type="number" name="sort_order" id="edit_sort" class="form-control" value="0" min="0">
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_active_edit" name="is_active">
                            <label class="custom-control-label" for="is_active_edit">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle Status
    $('.toggle-status').on('change', function() {
        const bannerId = $(this).data('id');
        const isActive = $(this).is(':checked');
        const statusBadge = $(`#status-badge-${bannerId}`);
        
        $.ajax({
            url: `/admin/banners/${bannerId}/toggle`,
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Update badge
                if (response.is_active) {
                    statusBadge.removeClass('badge-danger').addClass('badge-success').text('Aktif');
                } else {
                    statusBadge.removeClass('badge-success').addClass('badge-danger').text('Nonaktif');
                }
                
                iziToast.success({
                    title: 'Berhasil',
                    message: response.message,
                    position: 'topRight'
                });
            },
            error: function(xhr) {
                iziToast.error({
                    title: 'Error',
                    message: 'Gagal mengubah status',
                    position: 'topRight'
                });
                // Revert toggle
                $(this).prop('checked', !isActive);
            }
        });
    });

    // Edit Button
    $('.btn-edit').on('click', function() {
        const id = $(this).data('id');
        const title = $(this).data('title');
        const link = $(this).data('link');
        const sort = $(this).data('sort');
        const active = $(this).data('active');
        const imageFilename = $(this).data('image');
        
        $('#edit_title').val(title);
        $('#edit_link').val(link || '');
        $('#edit_sort').val(sort);
        $('#is_active_edit').prop('checked', active == 1);
        
        // Set image preview with correct path
        if (imageFilename) {
            const imagePath = imageFilename.startsWith('http') 
                ? imageFilename 
                : `/storage/banners/${imageFilename}`;
            $('#edit_image_preview').attr('src', imagePath);
        }
        
        $('#editBannerForm').attr('action', `/admin/banners/${id}`);
        
        $('#editBannerModal').modal('show');
    });

    // Delete Button
    $('.btn-delete').on('click', function() {
        const bannerId = $(this).data('id');
        const $row = $(this).closest('tr');
        
        swal({
            title: 'Apakah Anda yakin?',
            text: 'Banner akan dihapus permanen!',
            icon: 'warning',
            buttons: {
                cancel: {
                    text: 'Batal',
                    visible: true,
                    className: 'btn btn-secondary'
                },
                confirm: {
                    text: 'Hapus',
                    className: 'btn btn-danger'
                }
            },
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: `/admin/banners/${bannerId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Show success notification first
                        swal({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            buttons: false
                        });
                        
                        // Remove row from table with animation
                        $row.fadeOut(400, function() {
                            $(this).remove();
                            
                            // Check if table is empty
                            if ($('#bannerTable tbody tr').length === 0) {
                                $('#bannerTable tbody').html('<tr><td colspan="7" class="text-center">Belum ada banner</td></tr>');
                            }
                        });
                    },
                    error: function(xhr) {
                        swal({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus banner',
                            icon: 'error',
                            button: 'OK'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
