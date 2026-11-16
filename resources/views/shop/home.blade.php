@extends('layouts.app')
@section('title', 'Beranda')
@section('hide_pagehead', 'true')
@section('body_class', 'homepage')
@section('content')
<section class="hero">
    <div class="hero-content">
        <span class="hero-label"><span class="hero-dot"></span> SELAMAT DATANG DI TOKOKITA</span>
        <h1>Temukan kebutuhanmu, <em>belanja jadi mudah.</em></h1>
        <p>Jelajahi produk pilihan dari berbagai kategori. Tambahkan ke keranjang, checkout, dan pantau pesanan di satu tempat.</p>
        <div class="hero-cta"><a href="{{ route('shop.index') }}" class="btn btn-light">Mulai Belanja <span aria-hidden="true">↗</span></a><a href="#kategori" class="hero-secondary">Lihat Kategori <span aria-hidden="true">→</span></a></div>
        <div class="hero-stats"><div><b>{{ number_format($productCount, 0, ',', '.') }}+</b><span>Produk</span></div><div><b>{{ number_format($categoryCount, 0, ',', '.') }}+</b><span>Kategori</span></div><div><b>24/7</b><span>Akses Katalog</span></div></div>
    </div>
    <div class="hero-art" aria-hidden="true"><div class="hero-blob"></div><div class="hero-art-card"><img src="{{ asset('images/contoh-headphone-ungu.jpg') }}" alt=""></div><div class="hero-float"><span class="float-star">✦</span> Pilihan untukmu</div></div>
</section>

<section class="feature-strip" aria-label="Keunggulan aplikasi">
    <div class="feature"><span class="feature-symbol"><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 7h12v10H3zM15 10h4l3 4v3h-7z"/><circle cx="7" cy="18" r="2"/><circle cx="18" cy="18" r="2"/></svg></span><div><b>Belanja Praktis</b><small>Proses pemesanan yang mudah</small></div></div>
    <div class="feature"><span class="feature-symbol"><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 2 8 4v6c0 5-3.4 8.1-8 10-4.6-1.9-8-5-8-10V6z"/><path d="m8.5 12 2.5 2.5 4.5-5"/></svg></span><div><b>Data Terorganisir</b><small>Pesanan tercatat di akunmu</small></div></div>
    <div class="feature"><span class="feature-symbol"><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18M7 15h4"/></svg></span><div><b>Pembayaran Simulasi</b><small>Aman untuk pembelajaran</small></div></div>
</section>

<section id="kategori" class="home-section">
    <div class="section-heading"><div><span class="eyebrow">JELAJAHI TOKOKITA</span><h2>Belanja sesuai kategori</h2><p>Pilih kategori yang cocok dengan kebutuhanmu.</p></div><a href="{{ route('shop.index') }}" class="text-link">Lihat semua produk <span>↗</span></a></div>
    <div class="category-grid">
        @forelse($categories as $category)
            <a class="category-tile" href="{{ route('shop.index', ['category' => $category->id]) }}"><span class="category-deco">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="category-name">{{ $category->name }}</span><span class="category-total">{{ $category->products_count }} produk <span aria-hidden="true">↗</span></span></a>
        @empty
            <div class="empty-state"><h3>Kategori sedang disiapkan</h3><p>Admin dapat menambahkan kategori melalui panel pengelolaan.</p></div>
        @endforelse
    </div>
</section>

<section class="home-section">
    <div class="section-heading"><div><span class="eyebrow">DIPILIH UNTUKMU</span><h2>Produk terbaru</h2><p>Pilihan produk yang baru ditambahkan ke katalog.</p></div><a href="{{ route('shop.index') }}" class="text-link">Jelajahi katalog <span>↗</span></a></div>
    <div class="product-grid">
        @forelse($featuredProducts as $product)
            @include('shop.partials.product-card', ['product' => $product])
        @empty
            <div class="empty-state"><h3>Produk belum tersedia</h3><p>Tambahkan produk pertama melalui Panel Admin, lalu produk akan muncul di sini.</p><a class="btn" href="{{ route('shop.index') }}">Buka Katalog</a></div>
        @endforelse
    </div>
</section>

<section class="home-banner"><div><span class="eyebrow">TOKOKITA UNTUK SEMUA</span><h2>Semua yang kamu butuhkan, dalam satu toko.</h2><p>Temukan produk, kelola keranjang, dan selesaikan pesanan tanpa berpindah tempat.</p></div><a class="btn btn-light" href="{{ route('shop.index') }}">Jelajahi Produk <span>→</span></a></section>
@endsection
