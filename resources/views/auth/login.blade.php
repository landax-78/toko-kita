@extends('layouts.app')
@section('title', 'Masuk Akun')
@section('subtitle', 'Masuk untuk melanjutkan belanja dan mengelola pesanan.')
@section('content')
<div class="auth-wrap">
    <div class="auth-intro"><span class="hero-label">SELAMAT DATANG KEMBALI</span><h2>Belanja nyaman dimulai dari sini.</h2><p>Masuk untuk menyimpan produk di keranjang, melakukan checkout, dan melihat riwayat pesanan.</p></div>
    <div class="panel auth-form"><span class="eyebrow">AKUN TOKOKITA</span><h2>Masuk ke akunmu</h2><form method="POST" action="{{ route('login') }}">@csrf<div class="field"><label for="email">Alamat Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus autocomplete="email"></div><div class="field"><label for="password">Kata Sandi</label><input id="password" type="password" name="password" placeholder="Masukkan kata sandi" required autocomplete="current-password"></div><button type="submit" class="btn">Masuk ke TokoKita →</button></form><p>Belum memiliki akun? <a href="{{ route('register') }}">Daftar di sini</a></p></div>
</div>
@endsection
