@extends('layouts.app')

@section('title', 'Daftar Produk')
@section('page-title', 'Manajemen Stok & Produk')

@section('content')
<div class="space-y-5">
    <!-- Top Action Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Search & Filter Form -->
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
            <div class="relative min-w-[200px] flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau SKU produk..."
                       class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <select name="category_id" class="px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold transition">
                Filter
            </button>

            @if(request()->hasAny(['search', 'category_id']))
            <a href="{{ route('products.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-medium transition">
                Reset
            </a>
            @endif
        </form>

        <!-- Tambah Produk Button -->
        <a href="{{ route('products.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-semibold shadow-md transition flex-shrink-0">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Produk Baru</span>
        </a>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left divide-y divide-slate-100">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-3">SKU</th>
                        <th class="px-4 py-3">Nama Produk</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3 text-right">Harga Beli</th>
                        <th class="px-4 py-3 text-right">Harga Jual</th>
                        <th class="px-4 py-3 text-center">Stok</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($products as $product)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-4 py-3 font-mono font-medium text-slate-800">{{ $product->sku }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $product->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[11px]">
                                {{ $product->category->name }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-medium text-slate-900">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center font-bold">
                            {{ $product->stock }} <span class="font-normal text-slate-500 text-[10px]">{{ $product->unit }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($product->isLowStock())
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700">
                                Rendah (≤ {{ $product->min_stock }})
                            </span>
                            @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                Aman
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="inline-flex items-center space-x-1">
                                <a href="{{ route('products.edit', $product->id) }}" class="p-1.5 text-sky-600 hover:text-sky-800 hover:bg-sky-50 rounded-lg transition" title="Edit Produk">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <form method="POST" action="{{ route('products.destroy', $product->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Hapus Produk">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-400">
                            Tidak ada produk ditemukan sesuai kriteria pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
