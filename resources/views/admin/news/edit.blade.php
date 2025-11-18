@extends('admin.layouts.app')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Berita</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">Kelola Berita</a></div>
                <div class="breadcrumb-item">Edit Berita</div>
            </div>
        </div>
        
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Edit Berita</h4>
                        </div>
                        <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Judul Berita <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $news->title) }}" required autofocus>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
        
                        <div class="form-group">
                            <label for="excerpt">Ringkasan Singkat</label>
                            <textarea name="excerpt" id="excerpt" class="form-control @error('excerpt') is-invalid @enderror" rows="3" placeholder="Opsional - ringkasan singkat berita (maks 500 karakter)">{{ old('excerpt', $news->excerpt) }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Ringkasan ini akan ditampilkan di preview berita (maks 500 karakter)</small>
                        </div>                                <div class="form-group">
                                    <label for="content">Konten Berita <span class="text-danger">*</span></label>
                                    <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror">{{ old('content', $news->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
        
                                <div class="form-group">
                                    <label for="image">Gambar Berita</label>
                                    
                                    @if($news->image)
                                        <div class="mb-2">
                                            <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="img-thumbnail" style="max-width: 300px;">
                                            <p class="text-muted mt-1"><small>Gambar saat ini</small></p>
                                        </div>
                                    @endif
                                    
                                    <div class="custom-file">
                                        <input type="file" name="image" id="image" class="custom-file-input @error('image') is-invalid @enderror" accept="image/*" onchange="previewImage(this)">
                                        <label class="custom-file-label" for="image">Pilih gambar baru...</label>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF, WEBP. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                                    <div id="imagePreview" class="mt-2" style="display:none;">
                                        <img src="" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                                        <p class="text-muted mt-1"><small>Preview gambar baru</small></p>
                                    </div>
                                </div>
        
                                <div class="form-group">
                                    <label for="status">Status Publikasi <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="draft" {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status', $news->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Draft = tersimpan tapi tidak ditampilkan di halaman publik</small>
                                </div>
        
                                @if($news->published_at)
                                    <div class="alert alert-info">
                                        <strong>Info:</strong> Berita ini dipublish pada {{ $news->published_at->format('d F Y H:i') }}
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Berita
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Summernote for Excerpt (simple formatting)
    $('#excerpt').summernote({
        height: 120,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'strikethrough']],
            ['para', ['ul', 'ol']],
            ['misc', ['undo', 'redo']]
        ],
        placeholder: 'Opsional - ringkasan singkat berita (maks 500 karakter)',
        tabsize: 2,
        disableDragAndDrop: true,
        callbacks: {
            onKeyup: function(e) {
                var text = $(this).summernote('code').replace(/<[^>]+>/g, '');
                if (text.length > 500) {
                    $(this).summernote('code', text.substring(0, 500));
                }
            }
        }
    });

    // Initialize Summernote for Content (full formatting like forms-editor.html)
    $('#content').summernote({
        height: 400,
        tabsize: 2,
        callbacks: {
            onImageUpload: function(files) {
                // Handle image upload in editor
                for (let i = 0; i < files.length; i++) {
                    uploadImageToEditor(files[i], $(this));
                }
            }
        }
    });
});

// Preview featured image
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = preview.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
        
        // Update label
        const fileName = input.files[0].name;
        input.nextElementSibling.textContent = fileName;
    }
}

// Upload image within editor content
function uploadImageToEditor(file, editor) {
    const data = new FormData();
    data.append('file', file);
    
    // Create a data URL for the image
    const reader = new FileReader();
    reader.onload = function(e) {
        editor.summernote('insertImage', e.target.result);
    };
    reader.readAsDataURL(file);
}
</script>
@endsection
