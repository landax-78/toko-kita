@extends('layouts.app')
@section('title', 'Daftar Akun')
@section('subtitle', 'Buat akun pelanggan dan mulai belanja dengan mudah.')
@section('content')
<div class="auth-wrap">
    <div class="auth-intro"><span class="hero-label">BERGABUNG DENGAN TOKOKITA</span><h2>Satu akun, banyak pilihan.</h2><p>Daftarkan akunmu untuk menelusuri produk, mengisi keranjang, dan menyimpan riwayat pesanan dalam satu tempat.</p></div>
    <div class="panel auth-form"><span class="eyebrow">PENDAFTARAN</span><h2>Buat akun baru</h2><form method="POST" action="{{ route('register') }}">@csrf
        <div class="field"><label for="name">Nama Lengkap</label><input id="name" name="name" value="{{ old('name') }}" required maxlength="100" placeholder="Nama lengkap"></div>
        <div class="field"><label for="email">Alamat Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="nama@email.com"></div>
        <div class="field"><label for="password">Kata Sandi (minimal 8 karakter)</label><input id="password" name="password" type="password" required minlength="8" autocomplete="new-password"></div>
        <div class="field"><label for="password_confirmation">Konfirmasi Kata Sandi</label><input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password"></div>
        <button class="btn" type="submit">Buat Akun →</button></form><p>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p></div>
</div>
@endsection
