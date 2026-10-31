@extends('layouts.app')

@section('title', 'Catat Mutasi Stok - InventoHub')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Catat Transaksi Stok Barang</h1>
            <p class="text-sm text-slate-500">Formulir penerimaan barang masuk atau pengeluaran barang dari gudang.</p>
        </div>
        <a href="{{ route('transactions.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 hover:bg-slate-50 font-medium transition">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 md:p-8">
        <form action="{{ route('transactions.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- No Referensi -->
                <div>
                    <label for="reference_number" class="block text-sm font-semibold text-slate-700 mb-1">Nomor Referensi / Surat Jalan <span class="text-rose-500">*</span></label>
                    <input type="text" name="reference_number" id="reference_number" value="{{ old('reference_number', 'TRX-' . date('Ymd') . '-' . rand(1000, 9999)) }}" required
                        class="w-full px-4 py-2.5 rounded-lg border @error('reference_number') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                    @error('reference_number')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Transaksi -->
                <div>
                    <label for="transaction_date" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Transaksi <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', date('Y-m-d\TH:i')) }}" required
                        class="w-full px-4 py-2.5 rounded-lg border @error('transaction_date') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                    @error('transaction_date')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipe Mutasi -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-slate-700 mb-1">Tipe Mutasi Stok <span class="text-rose-500">*</span></label>
                    <select name="type" id="type" required
                        class="w-full px-4 py-2.5 rounded-lg border @error('type') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                        <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Masuk (Inbound / Tambah Stok)</option>
                        <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Keluar (Outbound / Kurang Stok)</option>
                    </select>
                    @error('type')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pilih Produk -->
                <div>
                    <label for="product_id" class="block text-sm font-semibold text-slate-700 mb-1">Pilih Produk <span class="text-rose-500">*</span></label>
                    <select name="product_id" id="product_id" required
                        class="w-full px-4 py-2.5 rounded-lg border @error('product_id') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ old('product_id') == $prod->id ? 'selected' : '' }}>
                                {{ $prod->name }} [Stok: {{ $prod->stock }} {{ $prod->unit }}]
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jumlah / Quantity -->
                <div class="md:col-span-2">
                    <label for="quantity" class="block text-sm font-semibold text-slate-700 mb-1">Jumlah Kuantitas Mutasi <span class="text-rose-500">*</span></label>
                    <input type="number" min="1" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" required
                        class="w-full px-4 py-2.5 rounded-lg border @error('quantity') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                    @error('quantity')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catatan / Keterangan -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-semibold text-slate-700 mb-1">Catatan / Keterangan Mutasi</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Contoh: Penerimaan restock supplier PT ABC atau Pengiriman DO pesanan cabang Surabaya"
                        class="w-full px-4 py-2.5 rounded-lg border @error('notes') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100">
                <a href="{{ route('transactions.index') }}" class="px-5 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-600 font-medium hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-indigo-600 text-sm text-white font-medium hover:bg-indigo-700 transition shadow-sm">
                    Simpan & Update Stok
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
