<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'product']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest('transaction_date')->paginate(10)->withQueryString();

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::orderBy('name', 'asc')->get();
        return view('transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reference_number' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:500'],
            'transaction_date' => ['required', 'date'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Validasi transaksi keluar tidak boleh melebihi stok yang tersedia
        if ($validated['type'] === 'out' && $product->stock < $validated['quantity']) {
            return back()->withInput()->with('error', "Stok tidak mencukupi! Stok saat ini: {$product->stock} {$product->unit}, Permintaan: {$validated['quantity']} {$product->unit}.");
        }

        // Database Transaction untuk menjaga konsistensi data (ACID)
        DB::transaction(function () use ($validated, $product) {
            Transaction::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'reference_number' => strip_tags($validated['reference_number']),
                'notes' => strip_tags($validated['notes'] ?? ''),
                'transaction_date' => $validated['transaction_date'],
            ]);

            // Update stok otomatis
            if ($validated['type'] === 'in') {
                $product->increment('stock', $validated['quantity']);
            } else {
                $product->decrement('stock', $validated['quantity']);
            }
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi inventaris berhasil dicatat dan stok telah diperbarui.');
    }
}

