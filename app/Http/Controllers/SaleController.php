<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function create()
    {
        $products = Product::all();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|exists:products,id',
            'quantity'     => 'required|integer|min:1',
            'price'        => 'required|numeric|min:0',
            'discount'     => 'nullable|numeric|min:0',
            'vat'          => 'nullable|numeric|min:0',
            'paid_amount'  => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $product = Product::findOrFail($request->product_id);

            $quantity     = $request->quantity;
            $price        = $request->price;
            $discount     = $request->discount ?? 0;
            $vat          = $request->vat ?? 0;
            $total        = (($quantity * $price) - $discount) + $vat;
            $paid         = $request->paid_amount;
            $due          = $total - $paid;

            // Create Sale
            $sale = Sale::create([
                'product_id'   => $product->id,
                'quantity'     => $quantity,
                'price'        => $price,
                'discount'     => $discount,
                'vat'          => $vat,
                'total'        => $total,
                'paid_amount'  => $paid,
            ]);

            // Reduce stock
            $product->decrement('opening_stock', $quantity);

            // Journal Entries
            $entries = [
                ['type' => 'sales',   'amount' => $quantity * $price],
                ['type' => 'discount','amount' => $discount],
                ['type' => 'vat',     'amount' => $vat],
                ['type' => 'cash',    'amount' => min($paid, $total)],
            ];

            if ($due > 0) {
                $entries[] = ['type' => 'due', 'amount' => $due];
            }

            foreach ($entries as $entry) {
                JournalEntry::create([
                    'sale_id' => $sale->id,
                    'type'    => $entry['type'],
                    'amount'  => $entry['amount'],
                ]);
            }
        });

        return redirect()->route('products.index')->with('success', 'Sale recorded and journal entries created.');
    }
}
