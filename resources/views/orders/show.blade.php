@extends('layouts.app')
@section('title', 'Detail Pesanan')
@section('subtitle', 'Kode pesanan: ' . $order->code)
@section('content')
<div class="breadcrumbs"><a href="{{ route('orders.index') }}">Riwayat Pesanan</a><span>/</span><strong>{{ $order->code }}</strong></div>
<div class="columns">
    <div class="panel"><div class="summary" style="margin:0 0 16px"><h2>Informasi Pesanan</h2><span class="order-status">{{ $order->status }}</span></div><p><strong>Penerima:</strong> {{ $order->shipping_name }}</p><p><strong>Alamat:</strong> {{ $order->shipping_address }}</p><div class="table-scroll"><table><thead><tr><th>Produk</th><th>Jumlah</th><th>Subtotal</th></tr></thead><tbody>@foreach($order->items as $item)<tr><td>{{ $item->product_name }}</td><td>{{ $item->quantity }}</td><td>Rp {{ number_format($item->subtotal,0,',','.') }}</td></tr>@endforeach</tbody></table></div><div class="order-total"><span>Total</span><strong>Rp {{ number_format($order->total,0,',','.') }}</strong></div>
        @if($order->status === 'pending')<form method="POST" action="{{ route('orders.cancel',$order) }}" onsubmit="return confirm('Batalkan pesanan ini?')">@csrf<button class="btn danger" type="submit">Batalkan Pesanan</button></form>@endif
    </div>
    <div class="panel"><h2>Pembayaran Transfer</h2><p class="info-note">Simulasi pembayaran untuk pembelajaran. Tidak ada transfer uang sungguhan.</p><p><strong>Metode:</strong> Transfer Bank</p><p><strong>Status:</strong> <span class="order-status">{{ $order->payment?->status ?? 'pending' }}</span></p>
        @if($order->status === 'pending')<form method="POST" action="{{ route('payments.submit',$order) }}">@csrf<div class="field"><label for="reference">Referensi Transfer (Simulasi)</label><input id="reference" name="reference" value="{{ old('reference',$order->payment?->reference) }}" required minlength="4" placeholder="Contoh: TRX-DEMO-001"></div><button class="btn" type="submit">Kirim Referensi</button></form>@endif
        @if($order->status === 'paid')<div class="alert success">Pembayaran sudah dikonfirmasi oleh admin.</div>@endif
    </div>
</div>
@endsection
