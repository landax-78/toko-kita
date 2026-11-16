@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('subtitle', 'Ringkasan aktivitas dan data toko dalam satu tampilan.')
@section('content')
@include('admin.resources.nav')
<div class="dashboard-welcome"><div><span class="eyebrow">HALO, ADMIN TOKOKITA</span><h2>Kelola toko dengan lebih mudah.</h2><p>Pantau produk dan pesanan, lalu lanjutkan pekerjaan dari menu pengelolaan.</p></div><a class="btn btn-light" href="{{ route('admin.products.create') }}">+ Tambah Produk</a></div>
<div class="stats-grid">
    <a href="{{ route('admin.products.index') }}" class="stat-card"><span>Total Produk</span><strong>{{ number_format($totalProducts,0,',','.') }}</strong><small>Produk di katalog ↗</small></a>
    <a href="{{ route('admin.categories.index') }}" class="stat-card"><span>Total Kategori</span><strong>{{ number_format($totalCategories,0,',','.') }}</strong><small>Kelompok produk ↗</small></a>
    <a href="{{ route('admin.orders.index') }}" class="stat-card"><span>Total Pesanan</span><strong>{{ number_format($totalOrders,0,',','.') }}</strong><small>Transaksi tercatat ↗</small></a>
    <a href="{{ route('admin.users.index') }}" class="stat-card"><span>Pelanggan</span><strong>{{ number_format($totalCustomers,0,',','.') }}</strong><small>Akun pelanggan ↗</small></a>
</div>
<div class="panel admin-list-panel"><div class="admin-list-top"><div><h2>Pesanan terbaru</h2><p class="muted">Total nilai pesanan berstatus dibayar: Rp {{ number_format($paidTotal,0,',','.') }} (simulasi)</p></div><a class="btn alt" href="{{ route('admin.orders.index') }}">Lihat Semua Pesanan</a></div>
<div class="table-scroll"><table><thead><tr><th>Kode Pesanan</th><th>Pelanggan</th><th>Total</th><th>Status</th><th>Tanggal</th></tr></thead><tbody>
    @forelse($recentOrders as $order)<tr><td class="order-code">{{ $order->code }}</td><td>{{ $order->user?->name ?? '—' }}</td><td class="table-price">Rp {{ number_format($order->total,0,',','.') }}</td><td><span class="order-status">{{ $order->status }}</span></td><td>{{ $order->created_at->format('d/m/Y') }}</td></tr>
    @empty<tr><td colspan="5"><div class="table-empty">Belum ada pesanan yang masuk.</div></td></tr>@endforelse
</tbody></table></div></div>
@endsection
