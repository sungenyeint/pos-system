<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaleStoreRequest;
use App\Http\Requests\Admin\SaleUpdateRequest;
use App\Models\Category;
use App\Models\InventoryTransaction;
use App\Models\PriceChangeHistory;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['product', 'product.category'])->sortable(['sale_date', 'desc']);

        if (request()->filled('category_id')) {
            $sales->whereHas('product', function ($query) {
                $query->where('category_id', request()->category_id);
            });
        }

        if (request()->filled('product_id')) {
            $sales->where('product_id', request('product_id'));
        }

        if (request()->filled('sale_date')) {
            $sales->whereBetween('sale_date', [Carbon::parse(request()->sale_date)->startOfDay(), Carbon::parse(request()->sale_date)->endOfDay()]);
        }

        if (request()->filled('sale_month')) {
            $sales->whereMonth('sale_date', request()->sale_month);
        }

        $sales = $sales->paginate(config('const.default_paginate_number'));

        return view('admin.sales.index', [
            'sales' => $sales,
            'products' => Product::whereHas('sales')->get(),
            'categories' => Category::whereHas('products.sales')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.sales.create', [
            'products' => Product::where('stock_quantity', '>', 0)->get(),
        ]);
    }

    public function store(SaleStoreRequest $request)
    {
        $info = '';
        try {
            DB::transaction(function() use ($request, &$info) {
                foreach ($request->sales as $sale) {
                    $sale['sale_date'] = $request->sale_date;
                    Sale::create($sale);

                    // Update the stock_quantity in the products table
                    $product = Product::find($sale['product_id']);
                    $stock_quantity = $product->stock_quantity - $sale['quantity'];

                    if ($stock_quantity <= 2) {
                        $info .= $product->name . ' stock အရေတွက် ' . $stock_quantity . ' သာကျန်ပါသည်။<br>';
                    }

                    if ($stock_quantity < 0) {
                        throw new Exception('stock quantity should not minus value.');
                    }

                    $update_data['stock_quantity'] = $stock_quantity;

                    if ($product->unit_price != $sale['total_price'] / $sale['quantity']) {
                        $update_data['unit_price'] = $sale['total_price'] / $sale['quantity'];

                        // add price_change_history table
                        PriceChangeHistory::create([
                            'product_id' => $sale['product_id'],
                            'price_change' => ($sale['total_price'] / $sale['quantity']) - $product->unit_price,
                            'status' => 'sale',
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
                ->with('alert.error', 'Failed to create sale.');
        }

        return redirect()->route('admin.sales.index')
            ->with('alert.success', 'sale created successfully.')
            ->with('alert.warning', $info);
    }

    public function edit(Sale $sale)
    {
        return view('admin.sales.edit', [
            'sale' => $sale,
            'products' => Product::all()
                ->filter(function ($product) use($sale) {
                    return $product->id === $sale->product->id || $product->stock_quantity > 0;
                }),
        ]);
    }

    public function update(SaleUpdateRequest $request, Sale $sale)
    {
        $info = '';
        try {
            DB::transaction(function() use ($sale, $request, &$info) {
                $before_quantity = $sale->quantity;
                $sale->update($request->all());

                // Update the stock_quantity in the products table
                $product = Product::find($request->product_id);
                $stock_quantity = $product->stock_quantity + $before_quantity - $request->quantity;

                if ($stock_quantity <= 2) {
                    $info = $product->name . ' stock အရေတွက် ' . $stock_quantity . ' သာကျန်ပါသည်။<br>';
                }

                if ($stock_quantity < 0) {
                    throw new Exception('stock quantity should not minus value.');
                }

                $update_data['stock_quantity'] = $stock_quantity;

                if ($product->unit_price != $sale['total_price'] / $sale['quantity']) {
                    $update_data['unit_price'] = $sale['total_price'] / $sale['quantity'];

                    // add price_change_history table
                    PriceChangeHistory::create([
                        'product_id' => $sale['product_id'],
                        'price_change' => ($sale['total_price'] / $sale['quantity']) - $product->unit_price,
                        'status' => 'sale',
                        'change_date' => Carbon::now(),
                    ]);
                }

                $product->fill($update_data)->save();
            });
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('alert.error', 'Failed to update sale. ');
        }

        return redirect()->route('admin.sales.index')
            ->with('alert.success', 'Product sale successfully.')
            ->with('alert.warning', $info);
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

    public function report()
    {
        $file_name = Carbon::create()->month((int) request()->month ?? Carbon::now()->month)->format('F') . '_sale_' . date('Ymd') . '.csv';

        $callback = function()
        {
            $csv = fopen('php://output', 'w');

            // Add UTF-8 BOM for proper rendering in some applications (e.g., Excel)
            fprintf($csv, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($csv, [
                '#',
                'sale_date',
                'category_name',
                'product_name',
                'quantity',
                'total_price',
                'total_profit',
            ]);

            $total_price = 0;
            $total_profit = 0;
            Sale::with(['product', 'product.category'])
                ->whereMonth('sale_date', request()->month)
                ->orderBy('sale_date')
                ->get()
                ->map(function ($sale, $key) use ($csv, &$total_price, &$total_profit) {
                    $profit = $sale->total_price - ($sale->quantity * $sale->product->unit_cost);

                    $total_price += $sale->total_price;
                    $total_profit += $profit;

                    fputcsv($csv, [
                        $key + 1,
                        Carbon::parse($sale->sale_date)->format('Y-m-d H:i'),
                        $sale->product->category->name,
                        $sale->product->name,
                        $sale->quantity,
                        number_format($sale->total_price) . 'ကျပ်',
                        number_format($profit) . 'ကျပ်',
                    ]);
                });

            fputcsv($csv, [
                '',
                '',
                '',
                '',
                'Total',
                number_format($total_price) . 'ကျပ်',
                number_format($total_profit) . 'ကျပ်',
            ]);

            fclose($csv);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $file_name . '"',
        ]);
    }
}
