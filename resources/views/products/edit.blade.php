@extends('layouts.app')

@section('title', 'Edit Produk - InventoHub')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Produk: {{ $product->name }}</h1>
            <p class="text-sm text-slate-500">Perbarui rincian informasi dan penetapan harga produk inventaris.</p>
        </div>
        <a href="{{ route('products.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 hover:bg-slate-50 font-medium transition">
            Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 md:p-8">
        <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- SKU -->
                <div>
                    <label for="sku" class="block text-sm font-semibold text-slate-700 mb-1">Kode SKU Produk <span class="text-rose-500">*</span></label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" required
                        class="w-full px-4 py-2.5 rounded-lg border @error('sku') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                    @error('sku')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-4 py-2.5 rounded-lg border @error('category_id') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Produk -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Produk <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                        class="w-full px-4 py-2.5 rounded-lg border @error('name') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga Beli -->
                <div>
                    <label for="purchase_price" class="block text-sm font-semibold text-slate-700 mb-1">Harga Beli / Pokok (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" step="100" min="0" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" required
                        class="w-full px-4 py-2.5 rounded-lg border @error('purchase_price') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                    @error('purchase_price')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga Jual -->
                <div>
                    <label for="selling_price" class="block text-sm font-semibold text-slate-700 mb-1">Harga Jual (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" step="100" min="0" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required
                        class="w-full px-4 py-2.5 rounded-lg border @error('selling_price') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                    @error('selling_price')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stok Saat Ini -->
                <div>
                    <label for="stock" class="block text-sm font-semibold text-slate-700 mb-1">Stok Fisik Saat Ini <span class="text-rose-500">*</span></label>
                    <input type="number" min="0" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required
                        class="w-full px-4 py-2.5 rounded-lg border @error('stock') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                    @error('stock')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Min Stok Alert -->
                <div>
                    <label for="min_stock" class="block text-sm font-semibold text-slate-700 mb-1">Batas Minimal Stok (Alert) <span class="text-rose-500">*</span></label>
                    <input type="number" min="0" name="min_stock" id="min_stock" value="{{ old('min_stock', $product->min_stock) }}" required
                        class="w-full px-4 py-2.5 rounded-lg border @error('min_stock') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                    @error('min_stock')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Satuan -->
                <div>
                    <label for="unit" class="block text-sm font-semibold text-slate-700 mb-1">Satuan Barang <span class="text-rose-500">*</span></label>
                    <input type="text" name="unit" id="unit" value="{{ old('unit', $product->unit) }}" required
                        class="w-full px-4 py-2.5 rounded-lg border @error('unit') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                    @error('unit')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-600 font-medium hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-indigo-600 text-sm text-white font-medium hover:bg-indigo-700 transition shadow-sm">
                    Perbarui Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

