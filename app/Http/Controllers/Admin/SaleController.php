<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PurchaseRequest;
use App\Http\Requests\Admin\SaleRequest;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('product')
            ->orderBy('updated_at', 'DESC');

        if (request()->filled('product_id')) {
            $sales->where('product_id', request('product_id'));
        }

        if (request()->filled('sale_date')) {
            $sales->whereBetween('sale_date', [Carbon::parse(request()->sale_date)->startOfDay(), Carbon::parse(request()->sale_date)->endOfDay()]);
        }

        $sales = $sales->paginate(config('const.default_paginate_number'));

        return view('admin.sales.index', [
            'sales' => $sales,
            'products' => Product::whereHas('sales')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.sales.create', [
            'products' => Product::where('stock_quantity', '>', 0)->get(),
        ]);
    }

    public function store(SaleRequest $request)
    {
        try {
            DB::transaction(function() use ($request) {
                foreach ($request->sales as $sale) {
                    $sale['sale_date'] = $request->sale_date;
                    Sale::create($sale);

                    // Update the stock_quantity in the products table
                    $product = Product::find($sale['product_id']);
                    $stock_quantity = $product->stock_quantity - $sale['quantity'];

                    $product->fill([
                        'stock_quantity' => $stock_quantity,
                    ])->save();

                    // Record the transaction in inventory_transactions
                    InventoryTransaction::create([
                        'product_id' => $sale['product_id'],
                        'quantity_change' => $stock_quantity,
                        'reason' => 'sale',
                        'transaction_date' => Carbon::now(),
                    ]);
                }
            });
        } catch (Exception $e) {
            logger()->error('$e->getMessage()', [$e->getMessage()]);
            return back()
                ->withInput()
                ->with('alert.error', 'Failed to create sale. ');
        }
        return redirect()->route('admin.sales.index')
            ->with('alert.success', 'sale created successfully.');
    }

    public function edit(Sale $sale)
    {
        return view('admin.sales.edit', [
            'sale' => $sale,
            'products' => Product::where('stock_quantity', '>', 0)->get(),
        ]);
    }

    public function update(Request $request, Sale $sale)
    {
        try {
            DB::transaction(function() use ($sale, $request) {
                $before_quantity = $sale->quantity;
                $sale->update($request->all());

                // Update the stock_quantity in the products table
                $product = Product::find($request->product_id);
                $stock_quantity = $product->stock_quantity - $before_quantity - $request->quantity;

                $product->fill([
                    'stock_quantity' => $stock_quantity,
                ])->save();

            });
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('alert.error', 'Failed to update sale. ');
        }

        return redirect()->route('admin.sales.index')
            ->with('alert.success', 'Product sale successfully.');
    }

    public function destroy(Sale $sale)
    {
        try {
            DB::transaction(function() use ($sale) {
                $sale->delete();

            });
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('alert.error', 'Failed to update sale. ');
        }
        return redirect()->route('admin.sales.index')
            ->with('alert.success', 'sale deleted successfully.');
    }
}
