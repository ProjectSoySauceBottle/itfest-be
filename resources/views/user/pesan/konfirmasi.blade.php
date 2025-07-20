@extends('layouts.admin.app')

@section('content')
<div class="container py-4">
    <h4>Konfirmasi Pesanan Meja #{{ $meja_id }}</h4>
    <form action="{{ route('pesan.simpan_db', $meja_id) }}" method="POST">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach ($menu_data as $item)
                    @if ($item['jumlah'] > 0)
                        @php 
                            $subtotal = $item['harga'] * $item['jumlah'];
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $item['nama'] }}</td>
                            <td>{{ $item['jumlah'] }}</td>
                            <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="mb-3">
            <label class="form-label">Metode Pembayaran</label>
            <input type="text" class="form-control" value="{{ strtoupper($metode) }}" disabled>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Lanjut Bayar</button>
        </div>
    </form>
</div>
@endsection
