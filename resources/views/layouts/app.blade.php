<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#102436">
    <meta name="description" content="TokoKita — belanja pilihan produk favorit dengan mudah dan nyaman.">
    <title>@yield('title', 'Belanja lebih nyaman') | TokoKita</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="@yield('body_class')">
    <div class="topline">
        <div class="container topline-inner">
            <span>Belanja mudah, pilihan beragam, semuanya di TokoKita</span>
            <span class="topline-note">Proyek demonstrasi Laravel &middot; Pembayaran simulasi</span>
        </div>
    </div>
    <header class="header">
        <div class="container nav">
            <a class="brand" href="{{ route('home') }}" aria-label="TokoKita - Beranda">
                <img class="brand-logo" src="{{ asset('images/logo-tokokita.svg') }}" alt="TokoKita">
            </a>
            <nav class="nav-main" aria-label="Navigasi utama">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                <a class="nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}" href="{{ route('shop.index') }}">Produk</a>
                @auth
                    <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">Pesanan</a>
                    @if(auth()->user()->role === 'admin')
                        <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.home') }}">Panel Admin</a>
                    @endif
                @endauth
            </nav>
            <div class="nav-actions">
                @auth
                    <a class="nav-cart" href="{{ route('cart.index') }}" aria-label="Buka keranjang belanja">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.6 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/></svg>
                        <span class="nav-cart-label">Keranjang</span>
                    </a>
                    <div class="nav-user"><span class="avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span><span class="nav-username">{{ auth()->user()->name }}</span></div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">@csrf<button class="nav-logout" type="submit">Keluar</button></form>
                @else
                    <a class="nav-login" href="{{ route('login') }}">Masuk</a>
                    <a class="btn btn-small" href="{{ route('register') }}">Daftar Gratis</a>
                @endauth
            </div>
        </div>
    </header>
    <main class="container main-content">
        @unless(trim($__env->yieldContent('hide_pagehead')))
            <div class="pagehead">
                <div>
                    <span class="eyebrow">TOKOKITA</span>
                    <h1>@yield('title', 'TokoKita')</h1>
                    @hasSection('subtitle')<p class="muted">@yield('subtitle')</p>@endif
                </div>
            </div>
        @endunless
        @if(session('success'))<div class="alert success" role="status"><strong>Berhasil.</strong> {{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert error" role="alert"><strong>Perhatian.</strong> {{ session('error') }}</div>@endif
        @if($errors->any())
            <div class="alert error" role="alert"><strong>Periksa kembali data yang diisi:</strong><ul>@foreach($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
    </main>
    <footer class="footer">
        <div class="container footer-inner">
            <div class="footer-about"><strong>Toko<span>Kita</span></strong><p>Belanja jadi lebih mudah, rapi, dan menyenangkan.</p></div>
            <div><strong>Jelajahi</strong><a href="{{ route('home') }}">Beranda</a><a href="{{ route('shop.index') }}">Katalog Produk</a></div>
            <div><strong>Informasi</strong><p>Proyek kecil Laravel. Pembayaran hanya simulasi, tidak ada transfer uang sesungguhnya.</p></div>
        </div>
        <div class="container copyright">&copy; {{ date('Y') }} TokoKita &middot; Sistem Toko Online Sederhana</div>
    </footer>
</body>
</html>
