<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'InventoHub') - Sistem Manajemen Inventaris Gudang</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 bg-slate-900 text-slate-200 flex-shrink-0 flex flex-col">
            <!-- Brand Logo -->
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-sky-400 flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-sky-500/20">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <h1 class="font-extrabold text-lg tracking-tight text-white">Invento<span class="text-sky-400">Hub</span></h1>
                        <p class="text-xs text-slate-400">Warehouse System</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
                <div class="px-3 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Navigasi Utama</div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-gauge w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('products.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('products.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-box-archive w-5 text-center"></i>
                    <span>Stok Produk</span>
                </a>

                <a href="{{ route('transactions.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('transactions.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-right-left w-5 text-center"></i>
                    <span>Transaksi Masuk/Keluar</span>
                </a>

                @if(Auth::check() && Auth::user()->isAdmin())
                <div class="pt-4 px-3 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Admin Area</div>
                <a href="{{ route('categories.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('categories.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-tags w-5 text-center"></i>
                    <span>Kategori Produk</span>
                </a>
                @endif
            </nav>

            <!-- User Info & Logout in Sidebar -->
            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center space-x-3 mb-3 p-2 rounded-lg bg-slate-800/60">
                    <div class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center text-sky-400 font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden flex-1">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                        <span class="inline-block px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider rounded-full {{ Auth::user()->role === 'admin' ? 'bg-amber-500/20 text-amber-300' : 'bg-emerald-500/20 text-emerald-300' }}">
                            {{ Auth::user()->role ?? 'Staff' }}
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Topbar -->
            <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center space-x-4">
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">@yield('page-title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center space-x-3 text-xs text-slate-500">
                    <span class="bg-slate-100 px-2.5 py-1.5 rounded-md border border-slate-200">
                        <i class="fa-regular fa-calendar-check mr-1.5 text-slate-400"></i>{{ date('d M Y') }}
                    </span>
                    <span class="hidden sm:inline bg-sky-50 text-sky-700 px-2.5 py-1.5 rounded-md border border-sky-200 font-medium">
                        UAS WAD - Univ. Cakrawala
                    </span>
                </div>
            </header>

            <!-- Page Body -->
            <div class="p-6 flex-1 max-w-7xl w-full mx-auto space-y-6">
                <!-- Alerts / Flash Messages -->
                @if(session('success'))
                <div class="flex items-center p-4 text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl shadow-sm">
                    <i class="fa-solid fa-circle-check text-lg mr-3 text-emerald-600"></i>
                    <div class="text-sm font-medium">{{ session('success') }}</div>
                </div>
                @endif

                @if(session('error'))
                <div class="flex items-center p-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation text-lg mr-3 text-rose-600"></i>
                    <div class="text-sm font-medium">{{ session('error') }}</div>
                </div>
                @endif

                @if($errors->any())
                <div class="p-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-sm">
                    <div class="flex items-center mb-2 font-semibold text-sm">
                        <i class="fa-solid fa-circle-exclamation mr-2 text-rose-600"></i> Terjadi kesalahan input:
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 text-rose-700">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200 px-6 py-4 text-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} <strong>InventoHub</strong> - Sistem Manajemen Inventaris Gudang. Dibuat oleh <strong>Halimah Sukmawaty</strong> (NIM: Cakrawala University).</p>
            </footer>
        </main>
    </div>
</body>
</html>

