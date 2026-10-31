<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Pengguna (Admin & Staff)
        $admin = User::create([
            'name' => 'Halimah Sukmawaty (Admin)',
            'email' => 'admin@inventohub.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        $staff = User::create([
            'name' => 'Budi Santoso (Staff Gudang)',
            'email' => 'staff@inventohub.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'phone' => '081298765432',
        ]);

        // 2. Kategori Produk
        $catElec = Category::create([
            'code' => 'ELEC',
            'name' => 'Elektronik & Komputer',
            'description' => 'Perangkat komputer, laptop, monitor, dan periferal perkantoran.',
        ]);

        $catAcc = Category::create([
            'code' => 'ACC',
            'name' => 'Aksesoris & Kabel',
            'description' => 'Kabel data, adapter, hub USB, mouse, keyboard.',
        ]);

        $catNet = Category::create([
            'code' => 'NETW',
            'name' => 'Jaringan & Networking',
            'description' => 'Router, switch manageable, access point, kabel UTP.',
        ]);

        $catPrn = Category::create([
            'code' => 'PRN',
            'name' => 'Perangkat Cetak',
            'description' => 'Printer barcode, printer thermal kasir, toner, pita printer.',
        ]);

        // 3. Produk Inventaris
        $p1 = Product::create([
            'category_id' => $catElec->id,
            'sku' => 'SKU-LAP-001',
            'name' => 'Laptop Asus Zenbook 14 OLED UX3402',
            'purchase_price' => 14500000,
            'selling_price' => 16999000,
            'stock' => 12,
            'min_stock' => 5,
            'unit' => 'Unit',
        ]);

        $p2 = Product::create([
            'category_id' => $catElec->id,
            'sku' => 'SKU-LAP-002',
            'name' => 'Lenovo ThinkPad E14 Gen 5 AMD',
            'purchase_price' => 11200000,
            'selling_price' => 13250000,
            'stock' => 4,
            'min_stock' => 5,
            'unit' => 'Unit',
        ]);

        $p3 = Product::create([
            'category_id' => $catElec->id,
            'sku' => 'SKU-MON-001',
            'name' => 'Monitor LG UltraGear 24GN60R 144Hz',
            'purchase_price' => 1850000,
            'selling_price' => 2199000,
            'stock' => 18,
            'min_stock' => 5,
            'unit' => 'Unit',
        ]);

        $p4 = Product::create([
            'category_id' => $catAcc->id,
            'sku' => 'SKU-ACC-001',
            'name' => 'Logitech MX Master 3S Wireless Mouse',
            'purchase_price' => 1250000,
            'selling_price' => 1549000,
            'stock' => 25,
            'min_stock' => 8,
            'unit' => 'Pcs',
        ]);

        $p5 = Product::create([
            'category_id' => $catAcc->id,
            'sku' => 'SKU-ACC-002',
            'name' => 'Keychron K2 V2 Wireless Mechanical Keyboard',
            'purchase_price' => 1050000,
            'selling_price' => 1299000,
            'stock' => 2,
            'min_stock' => 5,
            'unit' => 'Pcs',
        ]);

        $p6 = Product::create([
            'category_id' => $catNet->id,
            'sku' => 'SKU-NET-001',
            'name' => 'MikroTik RouterBOARD RB750Gr3 (hEX)',
            'purchase_price' => 720000,
            'selling_price' => 890000,
            'stock' => 15,
            'min_stock' => 5,
            'unit' => 'Unit',
        ]);

        // 4. Transaksi Mutasi Stok
        Transaction::create([
            'user_id' => $admin->id,
            'product_id' => $p1->id,
            'type' => 'in',
            'quantity' => 10,
            'reference_number' => 'TRX-IN-20261028-001',
            'notes' => 'Penerimaan PO Supplier Vendor PT Synnex',
            'transaction_date' => '2026-10-28 09:30:00',
        ]);

        Transaction::create([
            'user_id' => $staff->id,
            'product_id' => $p3->id,
            'type' => 'out',
            'quantity' => 4,
            'reference_number' => 'TRX-OUT-20261028-002',
            'notes' => 'Pengiriman barang pesanan toko cabang Surabaya',
            'transaction_date' => '2026-10-28 14:15:00',
        ]);

        Transaction::create([
            'user_id' => $admin->id,
            'product_id' => $p4->id,
            'type' => 'in',
            'quantity' => 15,
            'reference_number' => 'TRX-IN-20261029-003',
            'notes' => 'Restock inventaris mouse kantor',
            'transaction_date' => '2026-10-29 11:00:00',
        ]);
    }
}

