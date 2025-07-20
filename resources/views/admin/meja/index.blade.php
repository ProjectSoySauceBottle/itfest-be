@extends('layouts.admin.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Manajemen Meja</h4>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Meja</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle table-bordered">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nomor Meja</th>
                    <th>QR Code</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mejas as $meja)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $meja->nomor_meja }}</td>
                    <td>
                        @if($meja->qr_code_path)
                            <img src="{{ asset('storage/' . $meja->qr_code_path) }}" width="120">
                        @else
                            <span class="text-muted">Belum tersedia</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $meja->id }}">Hapus</button>
                    </td>
                </tr>

                {{-- Modal Delete --}}
                <div class="modal fade" id="deleteModal{{ $meja->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.meja.destroy', $meja->id) }}" method="POST" class="modal-content">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin menghapus meja <strong>{{ $meja->nomor_meja }}</strong>?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger">Ya, Hapus</button>
                            </div>
                        </form>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Belum ada data meja.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah Meja --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.meja.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Meja Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nomor Meja</label>
                    <input type="text" name="nomor_meja" class="form-control" required>
                    <small class="text-muted">Contoh: M1, M2, M3</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
