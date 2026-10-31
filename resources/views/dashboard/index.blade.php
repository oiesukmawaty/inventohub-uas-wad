@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Ringkasan Dashboard Inventaris')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Produk</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ number_format($totalProducts) }}</h3>
                <span class="text-[11px] text-emerald-600 font-medium"><i class="fa-solid fa-check mr-1"></i>Tercatat di sistem</span>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-tags"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori Aktif</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ number_format($totalCategories) }}</h3>
                <span class="text-[11px] text-indigo-600 font-medium">Klasifikasi Barang</span>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-money-bill-trend-up"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Valuasi Aset Stok</p>
                <h3 class="text-xl font-bold text-slate-800">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</h3>
                <span class="text-[11px] text-emerald-600 font-medium">Nilai Modal Barang</span>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-right-left"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Transaksi</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ number_format($totalTransactions) }}</h3>
                <span class="text-[11px] text-slate-500 font-medium">Stok Masuk & Keluar</span>
            </div>
        </div>
    </div>

    <!-- Alert Stok Menipis -->
    @if($lowStockProducts->count() > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center space-x-3 mb-3">
            <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center text-sm font-bold">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-sm">Peringatan: Stok Barang di Bawah Batas Minimum (Perlu Restock)</h4>
                <p class="text-xs text-slate-600">Terdapat {{ $lowStockProducts->count() }} produk yang persediaannya telah menyentuh batas aman.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left">
                <thead class="bg-amber-100/60 text-slate-700 font-semibold uppercase">
                    <tr>
                        <th class="px-3 py-2 rounded-l">SKU</th>
                        <th class="px-3 py-2">Nama Barang</th>
                        <th class="px-3 py-2">Kategori</th>
                        <th class="px-3 py-2 text-center">Sisa Stok</th>
                        <th class="px-3 py-2 text-center">Batas Minimum</th>
                        <th class="px-3 py-2 rounded-r text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100">
                    @foreach($lowStockProducts as $prod)
                    <tr class="hover:bg-amber-100/30">
                        <td class="px-3 py-2 font-mono font-medium">{{ $prod->sku }}</td>
                        <td class="px-3 py-2 font-semibold text-slate-800">{{ $prod->name }}</td>
                        <td class="px-3 py-2 text-slate-600">{{ $prod->category->name }}</td>
                        <td class="px-3 py-2 text-center">
                            <span class="px-2 py-0.5 rounded-full font-bold bg-rose-100 text-rose-700">
                                {{ $prod->stock }} {{ $prod->unit }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-center text-slate-600">{{ $prod->min_stock }} {{ $prod->unit }}</td>
                        <td class="px-3 py-2 text-right">
                            <a href="{{ route('transactions.create') }}?product_id={{ $prod->id }}&type=in" 
                               class="inline-flex items-center px-2 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded font-medium text-[11px] transition">
                                <i class="fa-solid fa-plus-circle mr-1"></i> Tambah Stok
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Transactions Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-base">5 Transaksi Terakhir</h3>
                <p class="text-xs text-slate-500">Mutasi barang masuk dan keluar terbaru di gudang.</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-800 flex items-center space-x-1">
                <span>Lihat Semua Transaksi</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left divide-y divide-slate-100">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-3">No. Referensi</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Barang</th>
                        <th class="px-4 py-3 text-center">Jumlah</th>
                        <th class="px-4 py-3">Petugas</th>
                        <th class="px-4 py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentTransactions as $trx)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-4 py-3 font-mono font-medium text-slate-800">{{ $trx->reference_number }}</td>
                        <td class="px-4 py-3">{{ date('d/m/Y', strtotime($trx->transaction_date)) }}</td>
                        <td class="px-4 py-3">
                            @if($trx->type === 'in')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                                <i class="fa-solid fa-arrow-down mr-1"></i> Masuk
                            </span>
                            @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 uppercase tracking-wider">
                                <i class="fa-solid fa-arrow-up mr-1"></i> Keluar
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-semibold">{{ $trx->product->name }}</td>
                        <td class="px-4 py-3 text-center font-bold">{{ $trx->quantity }} {{ $trx->product->unit }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $trx->user->name }}</td>
                        <td class="px-4 py-3 text-slate-500 truncate max-w-xs">{{ $trx->notes ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-slate-400">Belum ada data transaksi dicatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

