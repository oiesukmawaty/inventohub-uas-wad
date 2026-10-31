<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - InventoHub Sistem Inventaris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-sky-950 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-100">
        <!-- Header -->
        <div class="bg-gradient-to-r from-sky-600 to-indigo-700 p-8 text-center text-white">
            <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-2xl shadow-inner border border-white/20">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <h1 class="text-2xl font-black tracking-tight">Invento<span class="text-sky-300">Hub</span></h1>
            <p class="text-xs text-sky-100 mt-1">Sistem Manajemen Operasional & Inventaris Gudang</p>
        </div>

        <!-- Form Body -->
        <div class="p-8 space-y-6">
            @if(session('success'))
            <div class="p-3 text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="p-3 text-xs bg-rose-50 text-rose-700 border border-rose-200 rounded-lg">
                {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf <!-- Proteksi CSRF Token -->

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="nama@invento.id"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                    </div>
                    @error('email')
                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                    </div>
                    @error('password')
                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs text-slate-600">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl text-sm shadow-md hover:shadow-lg transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Masuk ke Sistem</span>
                </button>
            </form>

            <!-- Akun Demo Box -->
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs space-y-1.5">
                <p class="font-bold text-slate-700 flex items-center">
                    <i class="fa-solid fa-key text-amber-500 mr-1.5"></i> Kredensial Akun Uji Coba:
                </p>
                <div class="grid grid-cols-2 gap-2 text-[11px]">
                    <div class="bg-white p-2 rounded border border-slate-200">
                        <span class="font-semibold text-sky-700">Admin Role:</span><br>
                        <code>admin@invento.id</code><br>
                        <code>password123</code>
                    </div>
                    <div class="bg-white p-2 rounded border border-slate-200">
                        <span class="font-semibold text-emerald-700">Staff Role:</span><br>
                        <code>halimah.sukmawaty@cakrawala.ac.id</code><br>
                        <code>password123</code>
                    </div>
                </div>
            </div>

            <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100">
                Belum memiliki akun? 
                <a href="{{ route('register') }}" class="font-semibold text-sky-600 hover:text-sky-800 transition">Daftar Akun Baru</a>
            </div>
        </div>
    </div>
</body>
</html>

