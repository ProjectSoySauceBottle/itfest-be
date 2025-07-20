@extends('layouts.admin.app')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4">Dashboard Admin</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Card Navigasi Menu -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.menu.index') }}" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Manajemen Menu</h5>
                        <p class="text-muted">Lihat, tambah, edit, dan hapus menu makanan & minuman.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card Navigasi Meja -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.meja.index') }}" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Manajemen Meja</h5>
                        <p class="text-muted">Atur nomor meja dan QR code pelanggan.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card Navigasi Pesanan -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.pesanan.index') }}" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Data Pesanan</h5>
                        <p class="text-muted">Lihat daftar pesanan pelanggan berdasarkan meja.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
