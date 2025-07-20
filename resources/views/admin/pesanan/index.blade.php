@extends('layouts.admin.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Manajemen Pesanan</h4>
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
                    <th>Nama Menu</th>
                    <th>Jumlah Pesanan</th>
                    <th>Total Harga</th>
                    <th>Metode Bayar</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pesanans as $pesanan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>Meja {{ $pesanan->meja->nomor_meja ?? '-' }}</td>
                    <td>{{ $pesanan->menu->nama_menu ?? '-' }}</td>
                    <td>{{ $pesanan->jumlah_pesanan }}</td>
                    <td>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                    <td>
                        @if ($pesanan->metode_bayar === 'cash')
                            <span class="badge bg-secondary">Cash</span>
                        @else
                            <span class="badge bg-info text-dark">Cashless</span>
                        @endif
                    </td>
                    <td>
                        @if ($pesanan->status === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif ($pesanan->status === 'dibayar')
                            <span class="badge bg-primary">Dibayar</span>
                        @else
                            <span class="badge bg-success">Selesai</span>
                        @endif
                    </td>
                    <td class="text-center">
                        {{-- Tambahkan tombol aksi jika ingin nanti --}}
                        <button class="btn btn-sm btn-outline-secondary" disabled>Aksi</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Belum ada data pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $pesanans->links() }}
    </div>
</div>
@endsection
