<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PurchaseStoreRequest;
use App\Http\Requests\Admin\PurchaseUpdateRequest;
use App\Models\Category;
use App\Models\PriceChangeHistory;
use App\Models\Product;
use App\Models\Purchase;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['product', 'product.category'])->sortable(['purchase_date' => 'desc']);

        if (request()->filled('product_id')) {
            $purchases->where('product_id', request('product_id'));
        }

        if (request()->filled('purchase_month')) {
            $purchases->whereMonth('purchase_date', request()->purchase_month);
        }

        $purchases = $purchases->paginate(config('const.default_paginate_number'));

        return view('admin.purchases.index', [
            'purchases' => $purchases,
            'products' => Product::whereHas('purchases')->get(),
            'categories' => Category::whereHas('products.purchases')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.purchases.create', [
            'products' => Product::all(),
        ]);
    }

    public function store(PurchaseStoreRequest $request)
    {
        try {
            DB::transaction(function() use ($request) {
                foreach ($request->purchases as $purchase) {
                    $purchase['purchase_date'] = $request->purchase_date;
                    Purchase::create($purchase);

                    // Update the stock_quantity in the products table
                    $product = Product::find($purchase['product_id']);
                    $stock_quantity = $product->stock_quantity + $purchase['quantity'];

                    $update_data['stock_quantity'] = $stock_quantity;

                    if ($product->unit_cost != $purchase['total_cost'] / $purchase['quantity']) {
                        $update_data['unit_cost'] = $purchase['total_cost'] / $purchase['quantity'];

                        // add price_change_history table
                        PriceChangeHistory::create([
                            'product_id' => $purchase['product_id'],
                            'price_change' => ($purchase['total_cost'] / $purchase['quantity']) - $product->unit_cost,
                            'status' => 'purchase',
                            'change_date' => Carbon::now(),
                        ]);
                    }

                    $product->fill($update_data)->save();

                }
            });
        } catch (Exception $e) {
            logger()->error('$e->getMessage()', [$e->getMessage()]);
            return back()
                ->withInput()
                ->with('alert.error', 'Failed to create purchase. ');
        }
        return redirect()->route('admin.purchases.index')
            ->with('alert.success', 'purchase created successfully.');
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

    public function update(PurchaseUpdateRequest $request, Purchase $purchase)
    {
        try {
            DB::transaction(function() use ($purchase, $request) {
                $before_quantity = $purchase->quantity;
                $purchase->update($request->all());

                // Update the stock_quantity in the products table
                $product = Product::find($request->product_id);
                $stock_quantity = $product->stock_quantity - $before_quantity + $request->quantity;

                $update_data['stock_quantity'] = $stock_quantity;

                if ($product->unit_cost != $purchase['total_cost'] / $purchase['quantity']) {
                    $update_data['unit_cost'] = $purchase['total_cost'] / $purchase['quantity'];

                    // add price_change_history table
                    PriceChangeHistory::create([
                        'product_id' => $purchase['product_id'],
                        'price_change' => ($purchase['total_cost'] / $purchase['quantity']) - $product->unit_cost,
                        'status' => 'purchase',
                        'change_date' => Carbon::now(),
                    ]);
                }

                $product->fill($update_data)->save();
            });
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('alert.error', 'Failed to update purchase. ');
        }

        return redirect()->route('admin.purchases.index')
            ->with('alert.success', 'Product purchase successfully.');
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
            ->with('alert.success', 'purchase deleted successfully.');
    }

    public function report()
    {
        $file_name = Carbon::create()->month((int) request()->month ?? Carbon::now()->month)->format('F') . '_purchase_' . date('Ymd') . '.csv';

        $callback = function()
        {
            $csv = fopen('php://output', 'w');

            // Add UTF-8 BOM for proper rendering in some applications (e.g., Excel)
            fprintf($csv, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($csv, [
                '#',
                'purchase_date',
                'category_name',
                'product_name',
                'quantity',
                'total_cost',
            ]);

            $total_cost = 0;
            Purchase::with(['product', 'product.category'])
                ->whereMonth('purchase_date', request()->month)
                ->orderBy('purchase_date')
                ->get()
                ->map(function ($purchase, $key) use ($csv, &$total_cost) {
                    $total_cost += $purchase->total_cost;

                    fputcsv($csv, [
                        $key + 1,
                        $purchase->purchase_date,
                        $purchase->product->category->name,
                        $purchase->product->name,
                        $purchase->quantity,
                        number_format($purchase->total_cost) . 'ကျပ်',
                    ]);
                });

            fputcsv($csv, [
                '',
                '',
                '',
                '',
                'Total',
                number_format($total_cost) . 'ကျပ်',
            ]);

            fclose($csv);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $file_name . '"',
        ]);
    }
}
