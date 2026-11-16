@extends('layouts.app')
@section('title', 'Kelola ' . $title)
@section('subtitle', 'Kelola dan perbarui data ' . strtolower($title) . ' melalui halaman admin.')
@section('content')
@include('admin.resources.nav')
<div class="panel admin-list-panel">
    <div class="admin-list-top"><div><h2>Daftar {{ $title }}</h2><p class="muted">Total {{ $records->total() }} data tersimpan</p></div><a class="btn" href="{{ route('admin.'.$resource.'.create') }}">+ Tambah {{ $title }}</a></div>
    <div class="table-scroll"><table class="admin-table">
    @if($resource === 'products')
        <thead><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th class="action-heading">Tindakan</th></tr></thead>
        <tbody>
        @forelse($records as $record)
            <tr>
                <td><div class="product-cell"><div class="admin-thumb-frame">@if($record->image_path)<img src="{{ asset('storage/'.$record->image_path) }}" alt="{{ $record->name }}">@else<span>▧</span>@endif</div><div><strong>{{ $record->name }}</strong><small>{{ $record->sku }}</small></div></div></td>
                <td><span class="table-pill">{{ $record->category?->name ?? '-' }}</span></td>
                <td class="table-price">Rp {{ number_format($record->price,0,',','.') }}</td>
                <td><span class="stock-pill {{ $record->stock < 1 ? 'empty' : '' }}">{{ $record->stock }} unit</span></td>
                <td><div class="actions"><a class="btn alt btn-sm" href="{{ route('admin.'.$resource.'.edit', $record->id) }}">Edit</a><form method="POST" action="{{ route('admin.'.$resource.'.destroy',$record->id) }}" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">@csrf @method('DELETE')<button type="submit" class="btn danger btn-sm">Hapus</button></form></div></td>
            </tr>
        @empty<tr><td colspan="5"><div class="table-empty">Belum ada produk. Klik <b>Tambah Produk</b> untuk mulai mengisi katalog.</div></td></tr>
        @endforelse
        </tbody>
    @else
        <thead><tr><th>ID</th>@foreach($fields as $key=>$field)@if($key !== 'password' && $field['type'] !== 'file')<th>{{ $field['label'] }}</th>@endif @endforeach<th class="action-heading">Tindakan</th></tr></thead>
        <tbody>
        @forelse($records as $record)
            <tr><td class="muted">#{{ $record->id }}</td>
            @foreach($fields as $key=>$field)
                @if($key !== 'password' && $field['type'] !== 'file')
                    <td>
                        @if($field['type'] === 'select')
                            {{ $field['options'][$record->{$key}] ?? $record->{$key} }}
                        @elseif($field['type'] === 'textarea')
                            {{ \Illuminate\Support\Str::limit($record->{$key} ?? '-', 75) }}
                        @else
                            {{ $record->{$key} ?? '-' }}
                        @endif
                    </td>
                @endif
            @endforeach
            <td><div class="actions"><a class="btn alt btn-sm" href="{{ route('admin.'.$resource.'.edit', $record->id) }}">Edit</a><form method="POST" action="{{ route('admin.'.$resource.'.destroy',$record->id) }}" onsubmit="return confirm('Yakin ingin menghapus data ini?')">@csrf @method('DELETE')<button class="btn danger btn-sm" type="submit">Hapus</button></form></div></td></tr>
        @empty<tr><td colspan="20"><div class="table-empty">Belum ada data {{ strtolower($title) }}.</div></td></tr>
        @endforelse
        </tbody>
    @endif
    </table></div>
    <div class="pagination">{{ $records->links() }}</div>
</div>
@endsection
