@extends('admin.layouts.app')

@section('title', 'Field Akun Game')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Field Akun Game</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item active">Field Akun Game</div>
            </div>
        </div>
    
        <div class="section-body">
            <div class="mb-4">
                <h2 class="section-title">Atur kolom yang dibutuhkan untuk tiap game</h2>
                <p class="section-lead">Sesuaikan input akun yang harus diisi pelanggan ketika melakukan top up.</p>
            </div>
    
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
    
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        <ul class="mb-0 pl-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
    
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tambah Field Baru</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.game-account-fields.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Nama Game <span class="text-danger">*</span></label>
                                    <input type="text" name="game_name" class="form-control" list="game-options" value="{{ old('game_name', $selectedGame) }}" placeholder="Contoh: Mobile Legends" required>
                                    <datalist id="game-options">
                                        @foreach($games as $game)
                                            <option value="{{ $game }}">
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="form-group">
                                    <label>Label Field <span class="text-danger">*</span></label>
                                    <input type="text" name="label" class="form-control" value="{{ old('label') }}" placeholder="Contoh: User ID" required>
                                </div>
                                <div class="form-group">
                                    <label>Field Key</label>
                                    <input type="text" name="field_key" class="form-control" value="{{ old('field_key') }}" placeholder="Biarkan kosong untuk otomatis">
                                    <small class="text-muted">Hanya huruf/angka/tanda hubung bawah. Digunakan sebagai nama input.</small>
                                </div>
                                <div class="form-group">
                                    <label>Placeholder</label>
                                    <input type="text" name="placeholder" class="form-control" value="{{ old('placeholder') }}" placeholder="Contoh: Masukkan User ID">
                                </div>
                                <div class="form-group">
                                    <label>Tipe Input <span class="text-danger">*</span></label>
                                    <select name="input_type" class="form-control" required>
                                        @foreach($inputTypes as $type)
                                            <option value="{{ $type }}" {{ old('input_type', 'text') === $type ? 'selected' : '' }}>{{ strtoupper($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Wajib Diisi?</label>
                                    <div class="form-check d-flex align-items-center bg-light rounded px-3 py-2">
                                        <input class="form-check-input position-static mr-2" type="checkbox" id="is-required" name="is_required" value="1" {{ old('is_required', true) ? 'checked' : '' }}>
                                        <label class="form-check-label mb-0" for="is-required">Aktifkan jika kolom wajib diisi pelanggan</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Urutan</label>
                                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order') }}" min="0" placeholder="Kosongkan untuk otomatis">
                                </div>
                                <div class="form-group">
                                    <label>Catatan Bantuan</label>
                                    <textarea name="helper_text" class="form-control" rows="3" placeholder="Tips cara menemukan ID, dsb">{{ old('helper_text') }}</textarea>
                                </div>
                                <button class="btn btn-primary btn-block" type="submit">Simpan Field</button>
                            </form>
                        </div>
                    </div>
                </div>
    
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <h4 class="mb-2 mb-md-0">Daftar Field</h4>
                            <form method="GET" class="form-inline">
                                <div class="form-group mb-0">
                                    <select name="game" class="form-control" onchange="this.form.submit()">
                                        <option value="">Pilih Game</option>
                                        @foreach($games as $game)
                                            <option value="{{ $game }}" {{ $selectedGame === $game ? 'selected' : '' }}>{{ $game }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            @if(!$selectedGame)
                                <p class="text-muted">Pilih game terlebih dahulu untuk melihat field yang tersedia.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Label</th>
                                                <th>Field Key</th>
                                                <th>Tipe</th>
                                                <th>Wajib</th>
                                                <th>Urutan</th>
                                                <th>Catatan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($fields as $field)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $field->label }}</strong>
                                                        <div class="text-muted text-xs">{{ $field->placeholder }}</div>
                                                    </td>
                                                    <td><code>{{ $field->field_key }}</code></td>
                                                    <td>{{ strtoupper($field->input_type) }}</td>
                                                    <td>
                                                        @if($field->is_required)
                                                            <span class="badge badge-success">Ya</span>
                                                        @else
                                                            <span class="badge badge-secondary">Opsional</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $field->sort_order }}</td>
                                                    <td class="text-sm text-muted">{{ $field->helper_text ?? '-' }}</td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <button type="button" class="btn btn-info btn-edit-field"
                                                                data-id="{{ $field->id }}"
                                                                data-game="{{ e($field->game_name) }}"
                                                                data-label="{{ e($field->label) }}"
                                                                data-field-key="{{ e($field->field_key) }}"
                                                                data-placeholder="{{ e($field->placeholder) }}"
                                                                data-input-type="{{ $field->input_type }}"
                                                                data-is-required="{{ $field->is_required ? '1' : '0' }}"
                                                                data-sort-order="{{ $field->sort_order }}"
                                                                data-helper-text="{{ e($field->helper_text) }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form action="{{ route('admin.game-account-fields.destroy', $field) }}" method="POST" onsubmit="return confirm('Hapus field ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">Belum ada field untuk game ini.</td>
                                                </tr>
                                            @endforelse
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
    
    <!-- Edit Modal -->
    <div class="modal fade" id="editFieldModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Field Akun</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editFieldForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Game <span class="text-danger">*</span></label>
                            <input type="text" name="game_name" class="form-control" list="game-options" required>
                        </div>
                        <div class="form-group">
                            <label>Label Field <span class="text-danger">*</span></label>
                            <input type="text" name="label" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Field Key</label>
                            <input type="text" name="field_key" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Placeholder</label>
                            <input type="text" name="placeholder" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Tipe Input</label>
                            <select name="input_type" class="form-control" required>
                                @foreach($inputTypes as $type)
                                    <option value="{{ $type }}">{{ strtoupper($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="d-block">Wajib Diisi?</label>
                            <div class="form-check d-flex align-items-center bg-light rounded px-3 py-2">
                                <input class="form-check-input position-static mr-2" type="checkbox" id="edit-is-required" name="is_required" value="1">
                                <label class="form-check-label mb-0" for="edit-is-required">Aktifkan jika kolom wajib diisi pelanggan</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Urutan</label>
                            <input type="number" name="sort_order" class="form-control" min="0">
                        </div>
                        <div class="form-group">
                            <label>Catatan Bantuan</label>
                            <textarea name="helper_text" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = $('#editFieldModal');
        const editForm = document.getElementById('editFieldForm');

        document.querySelectorAll('.btn-edit-field').forEach(button => {
            button.addEventListener('click', function () {
                const actionUrl = `{{ route('admin.game-account-fields.index') }}/${this.dataset.id}`;
                editForm.action = actionUrl;
                editForm.querySelector('input[name="game_name"]').value = this.dataset.game;
                editForm.querySelector('input[name="label"]').value = this.dataset.label;
                editForm.querySelector('input[name="field_key"]').value = this.dataset.fieldKey;
                editForm.querySelector('input[name="placeholder"]').value = this.dataset.placeholder || '';
                editForm.querySelector('select[name="input_type"]').value = this.dataset.inputType;
                editForm.querySelector('input[name="sort_order"]').value = this.dataset.sortOrder;
                editForm.querySelector('textarea[name="helper_text"]').value = this.dataset.helperText || '';
                const isRequired = this.dataset.isRequired === '1';
                const checkbox = document.getElementById('edit-is-required');
                checkbox.checked = isRequired;
                editModal.modal('show');
            });
        });
    });
</script>
@endpush
