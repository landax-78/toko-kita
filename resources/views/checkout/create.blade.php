@extends('layouts.app')
@section('title', 'Checkout')
@section('subtitle', 'Konfirmasi alamat pengiriman dan ringkasan pesananmu.')
@section('content')
<div class="columns">
    <div class="panel"><h2>Alamat Pengiriman</h2><p class="muted">Lengkapi data penerima dan alamat dengan benar.</p><form method="POST" action="{{ route('checkout.store') }}">@csrf<div class="field" style="margin-top:22px"><label for="shipping_name">Nama Penerima</label><input id="shipping_name" name="shipping_name" value="{{ old('shipping_name',auth()->user()->name) }}" maxlength="100" required></div><div class="field" style="margin-top:17px"><label for="shipping_address">Alamat Lengkap</label><textarea id="shipping_address" name="shipping_address" rows="5" required minlength="10" maxlength="500">{{ old('shipping_address') }}</textarea></div><button class="btn btn-wide" type="submit">Buat Pesanan →</button></form></div>
    <div class="panel"><h2>Ringkasan Pesanan</h2><p class="muted">Detail pembelian akan dicatat setelah checkout.</p>
        @foreach($items as $item)<div class="summary-order-item"><span>{{ $item->product->name }} <span class="muted">× {{ $item->quantity }}</span></span><strong>Rp {{ number_format($item->product->price*$item->quantity,0,',','.') }}</strong></div>@endforeach
        <div class="order-total"><span>Total Pembayaran</span><strong>Rp {{ number_format($items->sum(fn($i)=>$i->product->price*$i->quantity),0,',','.') }}</strong></div><div class="info-note">Pembayaran pada aplikasi ini merupakan simulasi untuk keperluan tugas. Harga dan stok diperiksa kembali saat checkout.</div>
    </div>
</div>
@endsection
