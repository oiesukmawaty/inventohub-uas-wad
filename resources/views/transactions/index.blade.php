@extends('layouts.app')

@section('title', 'Riwayat Transaksi Stok - InventoHub')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Riwayat Mutasi & Transaksi Stok</h1>
            <p class="text-sm text-slate-500">Log pencatatan barang masuk (inbound) dan barang keluar (outbound) gudang.</p>
        </div>
        <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Catat Transaksi Stok
        </a>
    </div>

    <!-- Filter Card -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-wrap gap-4 items-center justify-between">
        <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <label class="text-xs font-semibold text-slate-500">Filter Tipe:</label>
            <select name="type" onchange="this.form.submit()" class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Mutasi</option>
                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Barang Masuk (In)</option>
                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Barang Keluar (Out)</option>
            </select>
            @if(request('type'))
                <a href="{{ route('transactions.index') }}" class="text-xs text-rose-600 hover:underline">Reset Filter</a>
            @endif
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-100 text-xs font-semibold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">No. Referensi</th>
                        <th class="py-3.5 px-4">Tanggal</th>
                        <th class="py-3.5 px-4">Tipe Mutasi</th>
                        <th class="py-3.5 px-4">Nama Produk</th>
                        <th class="py-3.5 px-4 text-right">Jumlah</th>
                        <th class="py-3.5 px-4">Petugas / User</th>
                        <th class="py-3.5 px-4">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 font-mono font-medium text-slate-800 text-xs">
                                {{ $trx->reference_number }}
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y, H:i') }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($trx->type == 'in')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                        Masuk
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                        Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-800">
                                {{ $trx->product->name ?? 'Produk Dihapus' }}
                                <span class="block text-xs text-slate-400 font-mono">{{ $trx->product->sku ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold {{ $trx->type == 'in' ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $trx->type == 'in' ? '+' : '-' }}{{ number_format($trx->quantity) }} {{ $trx->product->unit ?? '' }}
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-600">
                                {{ $trx->user->name ?? 'System' }}
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-500 max-w-xs truncate">
                                {{ $trx->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Belum ada riwayat transaksi stok yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
