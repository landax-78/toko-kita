@extends('layouts.app')
@section('title', 'Keranjang Belanja')
@section('subtitle', 'Periksa produk dan jumlah pesanan sebelum checkout.')
@section('content')
<div class="panel admin-list-panel">
    <div class="admin-list-top"><div><h2>Isi keranjangmu</h2><p class="muted">{{ $items->count() }} jenis produk di keranjang</p></div><a class="btn alt" href="{{ route('shop.index') }}">← Lanjut Belanja</a></div>
    <div class="table-scroll"><table class="cart-table"><thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th>Aksi</th></tr></thead><tbody>
        @forelse($items as $item)
            <tr><td><div class="cart-product-cell"><div class="admin-thumb-frame">@if($item->product->image_path)<img src="{{ asset('storage/'.$item->product->image_path) }}" alt="{{ $item->product->name }}">@else<span>▧</span>@endif</div><div><a href="{{ route('shop.show',$item->product) }}">{{ $item->product->name }}</a><div class="stock-label">Stok {{ $item->product->stock }}</div></div></div></td><td>Rp {{ number_format($item->product->price,0,',','.') }}</td><td><form action="{{ route('cart.update',$item) }}" method="POST" class="form-row">@csrf @method('PUT')<input aria-label="Jumlah produk" type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" required><button type="submit" class="btn alt btn-sm">Ubah</button></form></td><td class="table-price">Rp {{ number_format($item->product->price*$item->quantity,0,',','.') }}</td><td><form action="{{ route('cart.destroy',$item) }}" method="POST" onsubmit="return confirm('Hapus produk dari keranjang?')">@csrf @method('DELETE')<button type="submit" class="btn danger btn-sm">Hapus</button></form></td></tr>
        @empty<tr><td colspan="5"><div class="table-empty">Keranjangmu masih kosong. <a href="{{ route('shop.index') }}">Mulai belanja sekarang</a>.</div></td></tr>
        @endforelse
    </tbody></table></div>
    @if($items->isNotEmpty())<div class="summary" style="padding:22px 26px;margin:0;border-top:1px solid var(--line)"><div class="cart-total">Total: <span>Rp {{ number_format($items->sum(fn($i)=>$i->product->price*$i->quantity),0,',','.') }}</span></div><a class="btn" href="{{ route('checkout.create') }}">Lanjut Checkout →</a></div>@endif
</div>
@endsection
