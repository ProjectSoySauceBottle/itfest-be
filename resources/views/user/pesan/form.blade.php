@extends('layouts.admin.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Pemesanan untuk Meja Nomor: {{ $meja->nomor_meja }}</h4>

    <form id="pesanForm" action="{{ route('pesan.konfirmasi', $meja->id) }}" method="POST">
        @csrf
        <div class="row">
            @foreach ($menus as $menu)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $menu->gambar) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h5>{{ $menu->nama_menu }}</h5>
                        <p class="text-muted">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                        <input type="hidden" name="menu_data[{{ $menu->id }}][nama]" value="{{ $menu->nama_menu }}">
                        <input type="hidden" name="menu_data[{{ $menu->id }}][harga]" value="{{ $menu->harga }}">
                        <label for="jumlah_{{ $menu->id }}" class="form-label">Jumlah:</label>
                        <input type="number" name="menu_data[{{ $menu->id }}][jumlah]" id="jumlah_{{ $menu->id }}"
                               class="form-control jumlah-input" min="0" placeholder="0">
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            <label class="form-label">Metode Pembayaran:</label>
            <select name="metode_pembayaran" class="form-select" required>
                <option value="cash">Bayar di Kasir (Tunai)</option>
                <option value="qr">QRIS / Cashless</option>
            </select>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary">Lanjutkan</button>
        </div>
    </form>
</div>
@endsection
