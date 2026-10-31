/**
 * InventoHub - Local Interactive Demo Server (Node.js Built-in HTTP)
 * Dibuat untuk memfasilitasi perekaman video demo dan pengujian lokal langsung
 * tanpa memerlukan konfigurasi PHP/MySQL lokal yang rumit.
 * 
 * Cara Menjalankan:
 *   node preview-server.js
 * Lalu buka browser di: http://localhost:8000
 */

const http = require('http');
const url = require('url');

const PORT = process.env.PORT || 8000;

// Mock Data State
let categories = [
    { id: 1, code: 'ELEC', name: 'Elektronik & Komputer', description: 'Perangkat komputasi, laptop, monitor, dan periferal', count: 5 },
    { id: 2, code: 'ACC', name: 'Aksesoris & Kabel', description: 'Kabel data, adapter, hub USB, mouse, keyboard', count: 4 },
    { id: 3, code: 'NETW', name: 'Jaringan & Networking', description: 'Router, switch manageable, access point, kabel UTP', count: 3 },
    { id: 4, code: 'PRN', name: 'Perangkat Cetak', description: 'Printer barcode, printer thermal kasir, toner, pita', count: 2 },
    { id: 5, code: 'STOR', name: 'Media Penyimpanan', description: 'SSD NVMe, harddisk eksternal, flashdrive OTG', count: 3 }
];

let products = [
    { id: 1, sku: 'SKU-LAP-001', category_id: 1, category_name: 'Elektronik & Komputer', name: 'Laptop Asus Zenbook 14 OLED UX3402', purchase_price: 14500000, selling_price: 16999000, stock: 12, min_stock: 5, unit: 'Unit' },
    { id: 2, sku: 'SKU-LAP-002', category_id: 1, category_name: 'Elektronik & Komputer', name: 'Lenovo ThinkPad E14 Gen 5 AMD', purchase_price: 11200000, selling_price: 13250000, stock: 4, min_stock: 5, unit: 'Unit' },
    { id: 3, sku: 'SKU-MON-001', category_id: 1, category_name: 'Elektronik & Komputer', name: 'Monitor LG UltraGear 24GN60R 144Hz', purchase_price: 1850000, selling_price: 2199000, stock: 18, min_stock: 5, unit: 'Unit' },
    { id: 4, sku: 'SKU-ACC-001', category_id: 2, category_name: 'Aksesoris & Kabel', name: 'Logitech MX Master 3S Wireless Mouse', purchase_price: 1250000, selling_price: 1549000, stock: 25, min_stock: 8, unit: 'Pcs' },
    { id: 5, sku: 'SKU-ACC-002', category_id: 2, category_name: 'Aksesoris & Kabel', name: 'Keychron K2 V2 Wireless Mechanical Keyboard', purchase_price: 1050000, selling_price: 1299000, stock: 2, min_stock: 5, unit: 'Pcs' },
    { id: 6, sku: 'SKU-NET-001', category_id: 3, category_name: 'Jaringan & Networking', name: 'MikroTik RouterBOARD RB750Gr3 (hEX)', purchase_price: 720000, selling_price: 890000, stock: 15, min_stock: 5, unit: 'Unit' },
    { id: 7, sku: 'SKU-PRN-001', category_id: 4, category_name: 'Perangkat Cetak', name: 'Zebra ZD220 Barcode Label Thermal Printer', purchase_price: 2100000, selling_price: 2550000, stock: 8, min_stock: 3, unit: 'Unit' },
    { id: 8, sku: 'SKU-STO-001', category_id: 5, category_name: 'Media Penyimpanan', name: 'Samsung 980 Pro SSD NVMe 1TB PCIe 4.0', purchase_price: 1400000, selling_price: 1750000, stock: 20, min_stock: 5, unit: 'Pcs' }
];

let transactions = [
    { id: 1, ref: 'TRX-IN-20261028-001', date: '2026-10-28 09:30', type: 'in', product_name: 'Laptop Asus Zenbook 14 OLED UX3402', sku: 'SKU-LAP-001', qty: 10, unit: 'Unit', user: 'Halimah (Admin)', notes: 'Restock PO Vendor PT Synnex Metrodata' },
    { id: 2, ref: 'TRX-OUT-20261028-002', date: '2026-10-28 14:15', type: 'out', product_name: 'Monitor LG UltraGear 24GN60R', sku: 'SKU-MON-001', qty: 4, unit: 'Unit', user: 'Budi Santoso (Staff)', notes: 'Pengiriman pesanan e-commerce Order #9821' },
    { id: 3, ref: 'TRX-IN-20261029-003', date: '2026-10-29 11:00', type: 'in', product_name: 'Logitech MX Master 3S', sku: 'SKU-ACC-001', qty: 15, unit: 'Pcs', user: 'Halimah (Admin)', notes: 'Restock supplier lokal' },
    { id: 4, ref: 'TRX-OUT-20261030-004', date: '2026-10-30 16:45', type: 'out', product_name: 'Keychron K2 V2', sku: 'SKU-ACC-002', qty: 3, unit: 'Pcs', user: 'Budi Santoso (Staff)', notes: 'Distribusi display toko cabang Surabaya' }
];

function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
}

function renderLayout(title, content, activeNav = 'dashboard', flashMessage = null) {
    return `<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>${title}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">
    <!-- Navbar Top -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="/dashboard" class="flex items-center space-x-2">
                    <div class="w-9 h-9 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-sm font-bold text-lg">
                        I
                    </div>
                    <span class="font-bold text-lg text-slate-800 tracking-tight">Invento<span class="text-indigo-600">Hub</span></span>
                </a>
                <span class="hidden md:inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                    Live Demo UAS (Halimah Sukmawaty)
                </span>
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-semibold text-slate-700">Halimah Sukmawaty</div>
                    <div class="text-xs text-indigo-600 font-medium capitalize">Administrator (WAD05)</div>
                </div>
                <div class="w-9 h-9 bg-indigo-100 border border-indigo-200 rounded-full flex items-center justify-center font-bold text-indigo-700 text-sm">
                    HS
                </div>
                <a href="/login" class="text-xs text-rose-600 hover:text-rose-800 font-semibold border border-rose-200 hover:bg-rose-50 px-3 py-1.5 rounded-md transition">
                    Logout
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full flex-1 flex flex-col md:flex-row gap-6">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 bg-white rounded-xl shadow-xs border border-slate-200 p-4 shrink-0 h-fit space-y-1">
            <div class="px-3 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Menu Utama</div>
            <a href="/dashboard" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition ${activeNav === 'dashboard' ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-600 hover:bg-slate-50'}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="/products" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition ${activeNav === 'products' ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-600 hover:bg-slate-50'}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Katalog Produk
            </a>
            <a href="/categories" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition ${activeNav === 'categories' ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-600 hover:bg-slate-50'}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Kategori Barang
            </a>
            <a href="/transactions" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition ${activeNav === 'transactions' ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-600 hover:bg-slate-50'}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Mutasi Stok
            </a>

            <div class="pt-4 mt-4 border-t border-slate-100">
                <div class="px-3 py-1 text-xs font-semibold text-slate-400 uppercase tracking-wider">Info Sistem</div>
                <div class="px-3 py-2 text-xs text-slate-500 space-y-1">
                    <p><span class="font-medium text-slate-700">CPMK 1:</span> Arsitektur MVC</p>
                    <p><span class="font-medium text-slate-700">CPMK 2-3:</span> MySQL Relasional</p>
                    <p><span class="font-medium text-slate-700">CPMK 4:</span> CSRF/XSS/SQLi Safe</p>
                    <p><span class="font-medium text-slate-700">CPMK 5:</span> Cloud Deployment</p>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 w-full overflow-hidden">
            ${flashMessage ? `
            <div class="mb-4 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
                <span>${flashMessage}</span>
            </div>` : ''}
            ${content}
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-2">
            <div>&copy; 2026 <strong>InventoHub</strong> — Halimah Sukmawaty (WAD05 Universitas Cakrawala)</div>
            <div>Dosen Pengampu: Pak Wahyu Ari | Kaprodi: Pak Dyas</div>
        </div>
    </footer>
</body>
</html>`;
}

const server = http.createServer((req, res) => {
    const parsedUrl = url.parse(req.url, true);
    const pathname = parsedUrl.pathname;

    // Handle Routes
    if (pathname === '/' || pathname === '/dashboard') {
        const totalProducts = products.length;
        const totalCategories = categories.length;
        const totalTransactions = transactions.length;
        const totalAssetValue = products.reduce((acc, p) => acc + (p.stock * p.purchase_price), 0);
        const lowStockProducts = products.filter(p => p.stock <= p.min_stock);

        const html = renderLayout('Dashboard - InventoHub', `
            <div class="space-y-6">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Dashboard Manajemen Gudang</h1>
                    <p class="text-sm text-slate-500">Ringkasan operasional stok, valuasi aset inventaris, dan peringatan barang kritis.</p>
                </div>

                <!-- KPI Metric Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Item Produk</div>
                        <div class="text-2xl font-bold text-slate-800 mt-2">${totalProducts} SKU</div>
                        <div class="text-xs text-indigo-600 font-medium mt-1">Tercatat di sistem</div>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Kategori</div>
                        <div class="text-2xl font-bold text-slate-800 mt-2">${totalCategories} Klasifikasi</div>
                        <div class="text-xs text-slate-500 mt-1">Master kategori aktif</div>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Mutasi Stok</div>
                        <div class="text-2xl font-bold text-slate-800 mt-2">${totalTransactions} Transaksi</div>
                        <div class="text-xs text-emerald-600 font-medium mt-1">Inbound & Outbound</div>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Nilai Aset Gudang</div>
                        <div class="text-xl font-bold text-slate-800 mt-2">${formatRupiah(totalAssetValue)}</div>
                        <div class="text-xs text-slate-500 mt-1">Berdasarkan harga pokok</div>
                    </div>
                </div>

                <!-- Alert Stok Menipis -->
                ${lowStockProducts.length > 0 ? `
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                    <div class="flex items-center space-x-2 text-amber-800 font-bold mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Peringatan Stok Menipis (${lowStockProducts.length} Produk Butuh Restock)</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-amber-900">
                            <thead>
                                <tr class="border-b border-amber-200 font-semibold uppercase">
                                    <th class="py-2">SKU</th>
                                    <th class="py-2">Nama Barang</th>
                                    <th class="py-2 text-center">Sisa Stok</th>
                                    <th class="py-2 text-center">Batas Minimum</th>
                                    <th class="py-2 text-center">Aksi Cepat</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${lowStockProducts.map(p => `
                                    <tr class="border-b border-amber-100/70">
                                        <td class="py-2 font-mono font-medium">${p.sku}</td>
                                        <td class="py-2 font-semibold">${p.name}</td>
                                        <td class="py-2 text-center font-bold text-rose-600">${p.stock} ${p.unit}</td>
                                        <td class="py-2 text-center">${p.min_stock} ${p.unit}</td>
                                        <td class="py-2 text-center">
                                            <a href="/transactions/create?sku=${p.sku}" class="bg-amber-600 text-white px-3 py-1 rounded hover:bg-amber-700 transition">Restock</a>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>` : ''}

                <!-- Recent Transactions Table -->
                <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                    <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                        <h2 class="font-bold text-slate-800">Riwayat Mutasi Stok Terbaru</h2>
                        <a href="/transactions" class="text-xs text-indigo-600 hover:underline font-semibold">Lihat Semua Log &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                                <tr>
                                    <th class="py-3 px-4">No. Ref</th>
                                    <th class="py-3 px-4">Tanggal</th>
                                    <th class="py-3 px-4">Tipe</th>
                                    <th class="py-3 px-4">Produk</th>
                                    <th class="py-3 px-4 text-right">Jumlah</th>
                                    <th class="py-3 px-4">Petugas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                ${transactions.slice(0, 5).map(t => `
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-3 px-4 font-mono text-xs font-medium text-slate-800">${t.ref}</td>
                                        <td class="py-3 px-4 text-xs text-slate-500">${t.date}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded text-xs font-semibold ${t.type === 'in' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'}">
                                                ${t.type === 'in' ? 'MASUK' : 'KELUAR'}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 font-medium text-slate-800">${t.product_name}</td>
                                        <td class="py-3 px-4 text-right font-bold ${t.type === 'in' ? 'text-emerald-600' : 'text-amber-600'}">
                                            ${t.type === 'in' ? '+' : '-'}${t.qty} ${t.unit}
                                        </td>
                                        <td class="py-3 px-4 text-xs">${t.user}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `, 'dashboard');

        res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
        res.end(html);
    } 
    else if (pathname === '/products') {
        const query = parsedUrl.query.search ? parsedUrl.query.search.toLowerCase() : '';
        const catId = parsedUrl.query.category_id ? parseInt(parsedUrl.query.category_id) : null;

        let filtered = products.filter(p => {
            let matchQuery = !query || p.name.toLowerCase().includes(query) || p.sku.toLowerCase().includes(query);
            let matchCat = !catId || p.category_id === catId;
            return matchQuery && matchCat;
        });

        const html = renderLayout('Katalog Produk - InventoHub', `
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Katalog & Manajemen Inventaris</h1>
                        <p class="text-sm text-slate-500">Kelola master data produk, status kuantitas stok, dan harga barang.</p>
                    </div>
                    <a href="/products/create" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-xs">
                        + Tambah Produk Baru
                    </a>
                </div>

                <!-- Filters -->
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-wrap gap-3 items-center justify-between">
                    <form method="GET" action="/products" class="flex flex-wrap items-center gap-3 w-full">
                        <input type="text" name="search" value="${parsedUrl.query.search || ''}" placeholder="Cari nama barang / SKU..." class="px-3.5 py-2 rounded-lg border border-slate-300 text-sm w-full sm:w-64 focus:ring-2 focus:ring-indigo-500">
                        <select name="category_id" onchange="this.form.submit()" class="px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="">Semua Kategori</option>
                            ${categories.map(c => `<option value="${c.id}" ${catId === c.id ? 'selected' : ''}>${c.name}</option>`).join('')}
                        </select>
                        <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-700 transition">Cari</button>
                        ${(query || catId) ? `<a href="/products" class="text-xs text-rose-600 hover:underline">Reset</a>` : ''}
                    </form>
                </div>

                <!-- Product Table -->
                <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold uppercase text-slate-500">
                                <tr>
                                    <th class="py-3 px-4">SKU</th>
                                    <th class="py-3 px-4">Nama Produk</th>
                                    <th class="py-3 px-4">Kategori</th>
                                    <th class="py-3 px-4 text-right">Harga Beli</th>
                                    <th class="py-3 px-4 text-right">Harga Jual</th>
                                    <th class="py-3 px-4 text-center">Stok Fisik</th>
                                    <th class="py-3 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                ${filtered.map(p => `
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="py-3 px-4 font-mono text-xs font-medium text-indigo-600">${p.sku}</td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">${p.name}</td>
                                        <td class="py-3 px-4 text-xs text-slate-500"><span class="bg-slate-100 px-2 py-1 rounded">${p.category_name}</span></td>
                                        <td class="py-3 px-4 text-right text-xs">${formatRupiah(p.purchase_price)}</td>
                                        <td class="py-3 px-4 text-right font-medium text-slate-800">${formatRupiah(p.selling_price)}</td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold ${p.stock <= p.min_stock ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800'}">
                                                ${p.stock} ${p.unit}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center text-xs space-x-2">
                                            <a href="/products/edit?id=${p.id}" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                                            <a href="/transactions/create?sku=${p.sku}" class="text-emerald-600 hover:text-emerald-800 font-medium">Mutasi</a>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `, 'products');

        res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
        res.end(html);
    }
    else if (pathname === '/categories') {
        const html = renderLayout('Kategori Barang - InventoHub', `
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Manajemen Kategori Barang</h1>
                        <p class="text-sm text-slate-500">Kelola master klasifikasi kategori produk (Hak Akses: Administrator).</p>
                    </div>
                    <a href="/categories/create" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-xs">
                        + Tambah Kategori Baru
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold uppercase text-slate-500">
                            <tr>
                                <th class="py-3 px-4">Kode</th>
                                <th class="py-3 px-4">Nama Kategori</th>
                                <th class="py-3 px-4">Deskripsi</th>
                                <th class="py-3 px-4 text-center">Jumlah Produk</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            ${categories.map(c => `
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-4 font-mono font-medium text-indigo-600 text-xs">${c.code}</td>
                                    <td class="py-3 px-4 font-semibold text-slate-800">${c.name}</td>
                                    <td class="py-3 px-4 text-xs text-slate-500">${c.description}</td>
                                    <td class="py-3 px-4 text-center"><span class="bg-slate-100 px-2 py-0.5 rounded text-xs">${c.count} item</span></td>
                                    <td class="py-3 px-4 text-center text-xs space-x-2">
                                        <a href="/categories/edit?id=${c.id}" class="text-indigo-600 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `, 'categories');

        res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
        res.end(html);
    }
    else if (pathname === '/transactions') {
        const typeFilter = parsedUrl.query.type;
        let filtered = transactions.filter(t => !typeFilter || t.type === typeFilter);

        const html = renderLayout('Mutasi Stok - InventoHub', `
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Riwayat Mutasi & Transaksi Stok</h1>
                        <p class="text-sm text-slate-500">Log pencatatan barang masuk (inbound) dan barang keluar (outbound) gudang.</p>
                    </div>
                    <a href="/transactions/create" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-xs">
                        + Catat Mutasi Stok Baru
                    </a>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex items-center gap-3">
                    <label class="text-xs font-semibold text-slate-500">Filter Tipe:</label>
                    <a href="/transactions" class="text-xs px-3 py-1 rounded-md ${!typeFilter ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700'}">Semua</a>
                    <a href="/transactions?type=in" class="text-xs px-3 py-1 rounded-md ${typeFilter === 'in' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700'}">Barang Masuk</a>
                    <a href="/transactions?type=out" class="text-xs px-3 py-1 rounded-md ${typeFilter === 'out' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-700'}">Barang Keluar</a>
                </div>

                <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold uppercase text-slate-500">
                            <tr>
                                <th class="py-3 px-4">No. Ref</th>
                                <th class="py-3 px-4">Tanggal</th>
                                <th class="py-3 px-4">Tipe Mutasi</th>
                                <th class="py-3 px-4">Produk</th>
                                <th class="py-3 px-4 text-right">Jumlah</th>
                                <th class="py-3 px-4">Petugas</th>
                                <th class="py-3 px-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            ${filtered.map(t => `
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-4 font-mono text-xs font-medium text-slate-800">${t.ref}</td>
                                    <td class="py-3 px-4 text-xs text-slate-500">${t.date}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold ${t.type === 'in' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'}">
                                            ${t.type === 'in' ? 'MASUK' : 'KELUAR'}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-medium text-slate-800">${t.product_name}</td>
                                    <td class="py-3 px-4 text-right font-bold ${t.type === 'in' ? 'text-emerald-600' : 'text-amber-600'}">
                                        ${t.type === 'in' ? '+' : '-'}${t.qty} ${t.unit}
                                    </td>
                                    <td class="py-3 px-4 text-xs">${t.user}</td>
                                    <td class="py-3 px-4 text-xs text-slate-500">${t.notes}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `, 'transactions');

        res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
        res.end(html);
    }
    else if (pathname === '/transactions/create') {
        const html = renderLayout('Catat Mutasi Stok - InventoHub', `
            <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl border border-slate-200 shadow-xs space-y-6">
                <div>
                    <h1 class="text-xl font-bold text-slate-800">Catat Mutasi Stok Barang</h1>
                    <p class="text-xs text-slate-500">Mencatat barang masuk dari supplier atau barang keluar ke pelanggan/cabang.</p>
                </div>
                <form action="/transactions" method="GET" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Referensi / Surat Jalan</label>
                        <input type="text" value="TRX-${Date.now().toString().slice(-6)}" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Produk</label>
                        <select class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm">
                            ${products.map(p => `<option>${p.name} [Stok: ${p.stock} ${p.unit}]</option>`).join('')}
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tipe Mutasi</label>
                            <select class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm">
                                <option>Masuk (Inbound / Tambah Stok)</option>
                                <option>Keluar (Outbound / Kurang Stok)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah</label>
                            <input type="number" value="5" min="1" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Keterangan</label>
                        <textarea class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm" placeholder="Contoh: Penerimaan restock supplier"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <a href="/transactions" class="px-4 py-2 border rounded-lg text-sm text-slate-600 hover:bg-slate-50">Batal</a>
                        <a href="/transactions" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">Simpan Transaksi</a>
                    </div>
                </form>
            </div>
        `, 'transactions');

        res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
        res.end(html);
    }
    else if (pathname === '/login') {
        const html = `<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - InventoHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen px-4 py-12">
    <div class="max-w-md w-full space-y-6">
        <div class="text-center">
            <div class="w-12 h-12 bg-indigo-600 rounded-xl inline-flex items-center justify-center text-white shadow-md font-bold text-2xl mb-3">
                I
            </div>
            <h2 class="text-2xl font-bold text-slate-800">Invento<span class="text-indigo-600">Hub</span></h2>
            <p class="text-xs text-slate-500 mt-1">Sistem Manajemen Inventaris Gudang Terintegrasi</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
            <form action="/dashboard" method="GET" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Pengguna</label>
                    <input type="email" value="admin@inventohub.com" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi</label>
                    <input type="password" value="password123" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 bg-slate-50 -mx-8 -mb-8 p-4 rounded-b-2xl">
                <p class="text-xs font-semibold text-slate-600 mb-1">Kredensial Akun Uji Coba Penguji:</p>
                <div class="text-xs text-slate-500 font-mono space-y-0.5">
                    <div>Admin: <span class="text-indigo-600">admin@inventohub.com</span> / password123</div>
                    <div>Staff: <span class="text-indigo-600">staff@inventohub.com</span> / password123</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>`;
        res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
        res.end(html);
    }
    else {
        res.writeHead(302, { 'Location': '/dashboard' });
        res.end();
    }
});

server.listen(PORT, () => {
    console.log(`================================================================`);
    console.log(` InventoHub Demo Server is running on http://localhost:${PORT}`);
    console.log(` Identitas: Halimah Sukmawaty (WAD05 Universitas Cakrawala)`);
    console.log(`================================================================`);
});

