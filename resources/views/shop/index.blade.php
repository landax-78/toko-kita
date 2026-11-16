@extends('layouts.app')
@section('title', 'Katalog Produk')
@section('subtitle', 'Temukan produk favoritmu dengan mudah dan cepat.')
@section('content')
<div class="catalog-toolbar panel">
    <form action="{{ route('shop.index') }}" method="GET" class="catalog-filter">
        <div class="field search-field"><label for="search-products">Cari produk</label><input id="search-products" type="search" name="q" placeholder="Nama produk atau SKU..." value="{{ $keyword }}"></div>
        <div class="field filter-field"><label for="category">Kategori</label><select id="category" name="category"><option value="">Semua Kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected($selectedCategory === (string) $category->id)>{{ $category->name }}</option>@endforeach</select></div>
        <button class="btn" type="submit">Cari Produk</button>
        @if($keyword !== '' || $selectedCategory !== '')<a class="btn alt" href="{{ route('shop.index') }}">Reset</a>@endif
    </form>
    <div class="catalog-result">Menampilkan <strong>{{ $products->total() }}</strong> produk</div>
</div>
<div class="product-grid">
    @forelse($products as $product)
        @include('shop.partials.product-card', ['product' => $product])
    @empty
        <div class="empty-state"><div class="empty-icon">⌕</div><h3>Produk tidak ditemukan</h3><p>Coba gunakan kata kunci atau kategori lain.</p><a class="btn" href="{{ route('shop.index') }}">Lihat Semua Produk</a></div>
    @endforelse
</div>
<div class="pagination">{{ $products->links() }}</div>
@endsection
