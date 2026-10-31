@extends('layouts.app')

@section('title', 'Tambah Kategori - InventoHub')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Kategori Baru</h1>
            <p class="text-sm text-slate-500">Buat klasifikasi kategori baru untuk pengelompokan produk.</p>
        </div>
        <a href="{{ route('categories.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 hover:bg-slate-50 font-medium transition">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 md:p-8">
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="code" class="block text-sm font-semibold text-slate-700 mb-1">Kode Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="code" id="code" value="{{ old('code') }}" placeholder="Contoh: ELEC, ACC, PRN" required
                    class="w-full px-4 py-2.5 rounded-lg border @error('code') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                @error('code')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Elektronik & Komputer" required
                    class="w-full px-4 py-2.5 rounded-lg border @error('name') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">
                @error('name')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Singkat</label>
                <textarea name="description" id="description" rows="3" placeholder="Penjelasan singkat mengenai kategori ini..."
                    class="w-full px-4 py-2.5 rounded-lg border @error('description') border-rose-500 @else border-slate-200 @enderror focus:ring-2 focus:ring-indigo-500 text-sm">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100">
                <a href="{{ route('categories.index') }}" class="px-5 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-600 font-medium hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-indigo-600 text-sm text-white font-medium hover:bg-indigo-700 transition shadow-sm">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

