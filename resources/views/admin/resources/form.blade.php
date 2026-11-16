@extends('layouts.app')
@section('title', ($record ? 'Edit ' : 'Tambah ') . $title)
@section('subtitle', 'Lengkapi informasi ' . strtolower($title) . ' dan simpan perubahan ke database.')
@section('content')
@include('admin.resources.nav')
<form class="admin-form" method="POST" enctype="multipart/form-data" action="{{ $record ? route('admin.'.$resource.'.update', $record->id) : route('admin.'.$resource.'.store') }}">
    @csrf
    @if($record)@method('PUT')@endif
    @if($resource === 'products')
        <div class="product-form-layout">
            <div class="panel upload-panel">
                <div class="panel-title-row"><span class="section-icon">▧</span><div><h2>Foto Produk</h2><p>Rasio tampilan 1:1, posisi gambar di tengah.</p></div></div>
                <div class="upload-preview-frame" id="photo-preview-frame">
                    @if($record && $record->image_path)
                        <img id="photo-preview" src="{{ asset('storage/'.$record->image_path) }}" alt="Foto produk saat ini">
                    @else
                        <img id="photo-preview" alt="Pratinjau gambar produk" hidden>
                        <div class="preview-empty" id="photo-empty"><svg width="58" height="58" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="4"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m4 17 5-5 4 4 3-3 4 4"/></svg><span>Pratinjau Foto Produk</span></div>
                    @endif
                </div>
                <label class="upload-button" for="image_upload">Pilih Gambar</label>
                <input id="image_upload" type="file" name="image_upload" accept="image/jpeg,image/png,image/webp" data-image-preview="photo-preview" data-empty-preview="photo-empty" class="file-input">
                <p class="upload-note">JPG, PNG, atau WebP, maksimal 2 MB. Foto otomatis ditampilkan persegi dan tetap proporsional.</p>
                @if($record && $record->image_path)<p class="upload-note">Kosongkan pilihan gambar jika tidak ingin mengganti foto saat ini.</p>@endif
                @error('image_upload')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="panel form-panel">
                <div class="panel-title-row"><span class="section-icon">≡</span><div><h2>Informasi Produk</h2><p>Isi informasi produk secara lengkap dan akurat.</p></div></div>
                <div class="form-fields-grid">
                    @foreach($fields as $key=>$field)
                        @continue($field['type'] === 'file')
                        <div class="field {{ $field['type'] === 'textarea' ? 'field-full' : '' }}"><label for="field-{{ $key }}">{{ $field['label'] }} @if($key !== 'description')<span class="required-mark">*</span>@endif</label>
                            @if($field['type'] === 'textarea')
                                <textarea id="field-{{ $key }}" name="{{ $key }}" rows="5" placeholder="Tuliskan deskripsi produk...">{{ old($key, $record?->{$key} ?? '') }}</textarea>
                            @elseif($field['type'] === 'select')
                                <select id="field-{{ $key }}" name="{{ $key }}" required><option value="">Pilih kategori...</option>@foreach($field['options'] ?? [] as $optionValue=>$optionLabel)<option value="{{ $optionValue }}" @selected((string) old($key,$record?->{$key} ?? '') === (string) $optionValue)>{{ $optionLabel }}</option>@endforeach</select>
                            @else
                                <input id="field-{{ $key }}" name="{{ $key }}" type="{{ $field['type'] }}" @if($field['type'] === 'number')step="{{ $field['step'] ?? 'any' }}" min="0"@endif value="{{ old($key,$record?->{$key} ?? '') }}" required>
                            @endif
                            @error($key)<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                    @endforeach
                </div>
                <div class="form-actions"><a href="{{ route('admin.'.$resource.'.index') }}" class="btn alt">Kembali</a><button class="btn" type="submit">{{ $record ? 'Simpan Perubahan' : 'Simpan Produk' }}</button></div>
            </div>
        </div>
    @else
        <div class="panel form-panel narrow"><div class="panel-title-row"><span class="section-icon">≡</span><div><h2>{{ $record ? 'Perbarui' : 'Tambah' }} Data {{ $title }}</h2><p>Pastikan data yang dimasukkan sesuai.</p></div></div>
            <div class="form-fields-grid">
            @foreach($fields as $key=>$field)
                <div class="field {{ $field['type'] === 'textarea' ? 'field-full' : '' }}"><label for="field-{{ $key }}">{{ $field['label'] }}</label>
                    @if($field['type'] === 'textarea')
                        <textarea id="field-{{ $key }}" name="{{ $key }}" rows="4">{{ old($key, $record?->{$key} ?? '') }}</textarea>
                    @elseif($field['type'] === 'select')
                        <select id="field-{{ $key }}" name="{{ $key }}" required><option value="">-- Pilih --</option>@foreach($field['options'] ?? [] as $optionValue=>$optionLabel)<option value="{{ $optionValue }}" @selected((string) old($key,$record?->{$key} ?? '') === (string) $optionValue)>{{ $optionLabel }}</option>@endforeach</select>
                    @elseif($field['type'] === 'file')
                        <input id="field-{{ $key }}" type="file" name="{{ $key }}" accept="image/png,image/jpeg,image/webp">
                    @else
                        <input id="field-{{ $key }}" name="{{ $key }}" type="{{ $field['type'] }}" @if($field['type'] === 'number')step="{{ $field['step'] ?? 'any' }}" min="0"@endif value="{{ $field['type'] === 'password' ? '' : old($key,$record?->{$key} ?? '') }}" @if($field['type'] === 'password')autocomplete="new-password"@endif @required(!($record && $key === 'password'))>
                    @endif
                    @error($key)<div class="field-error">{{ $message }}</div>@enderror
                </div>
            @endforeach
            </div>
            <div class="form-actions"><a class="btn alt" href="{{ route('admin.'.$resource.'.index') }}">Kembali</a><button class="btn" type="submit">{{ $record ? 'Simpan Perubahan' : 'Tambah Data' }}</button></div>
        </div>
    @endif
</form>
@endsection
