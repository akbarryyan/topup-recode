@extends('admin.layouts.app')

@section('content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kelola Users</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </div>
                <div class="breadcrumb-item">Kelola Users</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {{ session('success') }}
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {{ session('error') }}
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                                                <div class="card-header">
                                                    <h4>Data Users</h4>
                                                    <div class="card-header-action">
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                                                            <i class="fas fa-plus"></i> Tambah User
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped" id="table-1">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">#</th>
                                                                    <th>Nama</th>
                                                                    <th>Username</th>
                                                                    <th>Email</th>
                                                                    <th>Tanggal Daftar</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($users as $index => $user)
                                                                <tr>
                                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                                    <td>{{ $user->name }}</td>
                                                                    <td>{{ $user->username ?? '-' }}</td>
                                                                    <td>{{ $user->email }}</td>
                                                                    <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                                                                    <td>
                                                                        <span class="badge badge-success">Active</span>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm btn-info" 
                                                                            data-toggle="modal" 
                                                                            data-target="#detailModal{{ $user->id }}"
                                                                            title="Detail">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                                                            class="btn btn-sm btn-warning" 
                                                                            title="Edit">
                                                                            <i class="fas fa-edit"></i>
                                                                        </a>
                                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                            onclick="confirmDelete({{ $user->id }})"
                                                                            title="Hapus">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                        
                                                                        <!-- Delete Form -->
                                                                        <form id="delete-form-{{ $user->id }}" 
                                                                            action="{{ route('admin.users.destroy', $user->id) }}" 
                                                                            method="POST" style="display: none;">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                                @empty
                                                                <tr>
                                                                    <td colspan="7" class="text-center text-muted">
                                                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                                        Tidak ada data user
                                                                    </td>
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
                        
                        <!-- Add User Modal -->
                        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('admin.users.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addUserModalLabel">Tambah User Baru</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="username">Username <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                                                @error('username')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Nomor WhatsApp</label>
                                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                                                <div class="form-group">
                                                                    <label for="password">Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                                                    @error('password')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="role">Role <span class="text-danger">*</span></label>
                                                                    <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                                                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                                                    </select>
                                                                    @error('role')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Detail Modals -->
                        @foreach($users as $user)
                        <div class="modal fade" id="detailModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $user->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel{{ $user->id }}">Detail User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="40%">ID</th>
                                                <td>{{ $user->id }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama Lengkap</th>
                                                <td>{{ $user->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Username</th>
                                                <td>{{ $user->username ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nomor WhatsApp</th>
                                                <td>{{ $user->phone ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Role</th>
                                                <td><span class="badge badge-primary">{{ ucfirst($user->role) }}</span></td>
                                            </tr>
                                            <tr>
                                                <th>Email Verified</th>
                                                <td>
                                                    @if($user->email_verified_at)
                                                        <span class="badge badge-success">Verified</span>
                                                        <br><small>{{ $user->email_verified_at->format('d M Y, H:i') }}</small>
                                                    @else
                                                        <span class="badge badge-warning">Not Verified</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Daftar</th>
                                                <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Terakhir Update</th>
                                                <td>{{ $user->updated_at->format('d M Y, H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        @push('scripts')
                        <script>
                            function confirmDelete(userId) {
                                swal({
                                    title: 'Apakah Anda yakin?',
                                    text: 'Data user akan dihapus permanen!',
                                    icon: 'warning',
                                    buttons: {
                                        cancel: {
                                            text: 'Batal',
                                            value: null,
                                            visible: true,
                                            className: '',
                                            closeModal: true,
                                        },
                                        confirm: {
                                            text: 'Ya, Hapus!',
                                            value: true,
                                            visible: true,
                                            className: 'bg-danger',
                                            closeModal: true
                                        }
                                    },
                                    dangerMode: true,
                                }).then((willDelete) => {
                                    if (willDelete) {
                                        document.getElementById('delete-form-' + userId).submit();
                                    }
                                });
                            }
                        </script>
                        @if($errors->any())
                        <script>
                            $(document).ready(function() {
                                $('#addUserModal').modal('show');
                            });
                        </script>
                        @endif
                        @endpush
                        @endsection
                        