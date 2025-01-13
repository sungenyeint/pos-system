<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PurchaseRequest;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Purchase;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('product')
            ->orderBy('updated_at', 'DESC');

        if (request()->filled('product_id')) {
            $purchases->where('product_id', request('product_id'));
        }

        if (request()->filled('purchase_month')) {
            $from = Carbon::parse(request()->purchase_month . ' ' . now()->year)->startOfDay();
            $to = Carbon::parse(request()->purchase_month . ' ' . now()->year)->endOfMonth()->endOfDay();
            $purchases->whereBetween('purchase_date', [$from, $to]);
        }

        $purchases = $purchases->paginate(config('const.default_paginate_number'));

        return view('admin.purchases.index', [
            'purchases' => $purchases,
            'products' => Product::whereHas('purchases')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.purchases.create', [
            'products' => Product::all(),
        ]);
    }

    public function store(PurchaseRequest $request)
    {
        try {
            DB::transaction(function() use ($request) {
                $purchase = Purchase::create($request->all());

                // Update the stock_quantity in the products table
                $product = Product::find($request->product_id);
                $stock_quantity = $product->stock_quantity + $request->quantity;

                $product->fill([
                    'stock_quantity' => $stock_quantity,
                ])->save();

                // Record the transaction in inventory_transactions
                InventoryTransaction::create([
                    'product_id' => $request->product_id,
                    'quantity_change' => $stock_quantity,
                    'reason' => 'purchase',
                    'transaction_date' => Carbon::now(),
                ]);
            });
        } catch (Exception $e) {
            logger()->error('$e->getMessage()', [$e->getMessage()]);
            return back()
                ->withInput()
                ->with('alert.error', 'Failed to create purchase. ');
        }
        return redirect()->route('admin.purchases.index')
            ->with('success', 'purchase created successfully.');
    }

    public function show(Purchase $purchase)
    {
        return view('admin.purchases.show', [
            'purchase' => $purchase,
        ]);
    }

    public function edit(Purchase $purchase)
    {
        return view('admin.purchases.edit', [
            'purchase' => $purchase,
            'products' => Product::all(),
        ]);
    }

    public function update(PurchaseRequest $request, Purchase $purchase)
    {
        try {
            DB::transaction(function() use ($purchase, $request) {
                $before_quantity = $purchase->quantity;
                $purchase->update($request->all());

                // Update the stock_quantity in the products table
                $product = Product::find($request->product_id);
                $stock_quantity = $product->stock_quantity - $before_quantity + $request->quantity;

                $product->fill([
                    'stock_quantity' => $stock_quantity,
                ])->save();

                // Record the transaction in inventory_transactions
                // InvendoryTransaction::create([
                //     'product_id' => $request->product_id,
                //     'quantity_change' => $stock_quantity,
                //     'reason' => 'purchase',
                //     'purchase_date' => Carbon::now(),
                // ]);
            });
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('alert.error', 'Failed to update purchase. ');
        }

        return redirect()->route('admin.purchases.index')
            ->with('success', 'Product purchase successfully.');
    }

    public function destroy(Purchase $purchase)
    {
        try {
            DB::transaction(function() use ($purchase) {
                $purchase->delete();

            });
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('alert.error', 'Failed to update purchase. ');
        }
        return redirect()->route('admin.purchases.index')
            ->with('success', 'purchase deleted successfully.');
    }
}
