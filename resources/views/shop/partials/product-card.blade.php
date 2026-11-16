<article class="product-card">
    <div class="product-card-image-wrap">
        <a class="product-image-frame" href="{{ route('shop.show', $product) }}" aria-label="Lihat {{ $product->name }}">
            @if($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" loading="lazy">
            @else
                <div class="product-placeholder" aria-label="Gambar produk belum tersedia">
                    <svg width="68" height="68" viewBox="0 0 64 64" fill="none" aria-hidden="true"><rect x="9" y="14" width="46" height="38" rx="8" stroke="currentColor" stroke-width="2.5"/><circle cx="24" cy="27" r="5" stroke="currentColor" stroke-width="2.5"/><path d="m16 45 12-12 8 8 6-6 7 10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span>Foto menyusul</span>
                </div>
            @endif
        </a>
        @if($product->stock < 1)<span class="photo-tag soldout">Stok Habis</span>@else<span class="photo-tag">Tersedia</span>@endif
    </div>
    <div class="product-card-body">
        <span class="category-label">{{ $product->category?->name ?? 'Produk' }}</span>
        <h3><a href="{{ route('shop.show', $product) }}">{{ $product->name }}</a></h3>
        <p class="product-description">{{ \Illuminate\Support\Str::limit($product->description ?: 'Produk pilihan untuk kebutuhan sehari-hari.', 78) }}</p>
        <div class="product-card-bottom">
            <div><div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div><span class="stock-label">Stok {{ number_format($product->stock, 0, ',', '.') }}</span></div>
            @auth
                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button class="icon-buy" type="submit" aria-label="Tambahkan {{ $product->name }} ke keranjang" title="Tambah ke keranjang" @disabled($product->stock < 1)>
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h2l2.1 11a2 2 0 0 0 2 1.6h10.5a2 2 0 0 0 2-1.6L22 7H5"/><circle cx="9" cy="20" r="1"/><circle cx="19" cy="20" r="1"/><path d="M12 7V3m-2 2h4"/></svg>
                    </button>
                </form>
            @else
                <a class="icon-buy" href="{{ route('login') }}" aria-label="Masuk untuk membeli {{ $product->name }}" title="Masuk untuk membeli">+</a>
            @endauth
        </div>
    </div>
</article>
