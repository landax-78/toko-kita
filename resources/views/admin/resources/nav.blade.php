<div class="admin-section-head"><div><span class="eyebrow">PENGELOLAAN TOKO</span><p>Kelola data produk, pengguna, dan transaksi TokoKita melalui menu berikut.</p></div><span class="admin-role-tag">Panel Administrator</span></div>
<nav class="admin-nav" aria-label="Navigasi panel admin">
@foreach([
    'home'=>'Dashboard','products'=>'Produk','categories'=>'Kategori','orders'=>'Pesanan','payments'=>'Pembayaran',
    'users'=>'Pengguna','cart-items'=>'Keranjang','order-items'=>'Detail Pesanan'
] as $name=>$label)
    <a href="{{ route($name === 'home' ? 'admin.home' : 'admin.'.$name.'.index') }}" class="{{ (request()->routeIs('admin.'.$name.'.*') || ($name === 'home' && request()->routeIs('admin.home'))) ? 'active' : '' }}">{{ $label }}</a>
@endforeach
</nav>
