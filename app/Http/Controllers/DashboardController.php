<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalTransactions = Transaction::count();

        // Total nilai valuasi stok aset
        $totalStockValue = Product::selectRaw('SUM(stock * purchase_price) as total_val')->value('total_val') ?? 0;

        // Produk dengan stok menipis (dibawah min_stock)
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->get();

        // 5 Transaksi terakhir
        $recentTransactions = Transaction::with(['user', 'product'])
            ->latest('transaction_date')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalCategories',
            'totalTransactions',
            'totalStockValue',
            'lowStockProducts',
            'recentTransactions'
        ));
    }
}
