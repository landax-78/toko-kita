@extends('layouts.app')
@section('title', 'Riwayat Pesanan')
@section('subtitle', 'Pantau semua transaksi dan status pesananmu di sini.')
@section('content')
<div class="panel admin-list-panel"><div class="admin-list-top"><div><h2>Daftar Pesanan</h2><p class="muted">Riwayat transaksi akunmu</p></div><a href="{{ route('shop.index') }}" class="btn alt">+ Belanja Lagi</a></div>
<div class="table-scroll"><table><thead><tr><th>Kode</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Detail</th></tr></thead><tbody>
    @forelse($orders as $order)<tr><td><span class="order-code">{{ $order->code }}</span></td><td>{{ $order->created_at->format('d/m/Y H:i') }}</td><td class="table-price">Rp {{ number_format($order->total,0,',','.') }}</td><td><span class="order-status">{{ $order->status }}</span></td><td><a class="btn alt btn-sm" href="{{ route('orders.show',$order) }}">Lihat Detail</a></td></tr>
    @empty<tr><td colspan="5"><div class="table-empty">Belum ada pesanan. <a href="{{ route('shop.index') }}">Belanja sekarang</a>.</div></td></tr>@endforelse
</tbody></table></div><div class="pagination">{{ $orders->links() }}</div></div>
@endsection
