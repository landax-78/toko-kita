@extends('layouts.app')
@section('title', $product->name)
@section('subtitle', 'Detail produk dan informasi ketersediaannya.')
@section('content')
<div class="breadcrumbs"><a href="{{ route('home') }}">Beranda</a><span>/</span><a href="{{ route('shop.index') }}">Produk</a><span>/</span><strong>{{ $product->name }}</strong></div>
<div class="product-detail panel">
    <div class="detail-media product-image-frame">
        @if($product->image_path)
            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
        @else
            <div class="product-placeholder"><svg width="90" height="90" viewBox="0 0 64 64" fill="none" aria-hidden="true"><rect x="9" y="14" width="46" height="38" rx="8" stroke="currentColor" stroke-width="2.5"/><circle cx="24" cy="27" r="5" stroke="currentColor" stroke-width="2.5"/><path d="m16 45 12-12 8 8 6-6 7 10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg><span>Foto produk belum tersedia</span></div>
        @endif
    </div>
    <div class="detail-info">
        <span class="category-label">{{ $product->category?->name ?? 'Produk' }}</span>
        <h2>{{ $product->name }}</h2><p class="sku-line">SKU: {{ $product->sku }}</p>
        <div class="detail-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        <div class="detail-stock"><span class="stock-dot {{ $product->stock ? '' : 'out' }}"></span>{{ $product->stock ? 'Tersedia · '.$product->stock.' stok' : 'Stok habis' }}</div>
        <div class="detail-divider"></div>
        <h3>Deskripsi produk</h3><p class="detail-description">{{ $product->description ?: 'Informasi detail produk belum ditambahkan oleh admin.' }}</p>
        @auth
            <form method="POST" action="{{ route('cart.store') }}" class="detail-add-form">
                @csrf<input type="hidden" name="product_id" value="{{ $product->id }}">
                <div><label for="qty">Jumlah pembelian</label><input id="qty" name="quantity" type="number" min="1" max="{{ $product->stock }}" value="1" required></div>
                <button class="btn btn-wide" type="submit" @disabled($product->stock < 1)>+ Tambah ke Keranjang</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-wide">Masuk untuk Membeli</a>
        @endauth
        <p class="detail-assurance">✓ Transaksi simulasi untuk keperluan tugas perkuliahan.</p>
    </div>
</div>
@if($relatedProducts->isNotEmpty())<section class="home-section related-section"><div class="section-heading"><div><span class="eyebrow">MUNGKIN KAMU SUKA</span><h2>Produk serupa</h2></div><a class="text-link" href="{{ route('shop.index') }}">Lihat semua <span>↗</span></a></div><div class="product-grid">@foreach($relatedProducts as $product)@include('shop.partials.product-card', ['product' => $product])@endforeach</div></section>@endif
@endsection
