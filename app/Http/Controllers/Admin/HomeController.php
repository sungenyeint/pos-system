<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceChangeHistory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home()
    {
        $currentYear = Carbon::now()->year;

        $sale_data = Sale::with(['product'])
            ->whereYear('sale_date', $currentYear)
            ->orderBy('sale_date')
            ->get()
            ->groupBy(function ($sale) {
                return Carbon::parse($sale['sale_date'])->format('n');
            })
            ->mapWithKeys(function ($items, $key) {
                $data = [];
                $data['total_price'] = 0;
                $data['total_profit'] = 0;

                foreach ($items as $item) {
                    $data['product'][$item->quantity] = $item->product->name;
                    $data['total_price'] += $item->total_price;
                    $data['total_profit'] += ($item->product->unit_price - $item->product->unit_cost) * $item->quantity;
                }
                return [$key => $data];
            })
            ->toArray();

        $purchase_data = Purchase::selectRaw('MONTH(purchase_date) as month, SUM(total_cost) as total_cost')
            ->whereYear('purchase_date', $currentYear)
            ->groupBy(DB::raw('MONTH(purchase_date)'))
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item, $key) {
                return [$item['month'] => $item['total_cost']];
            })
            ->toArray();

        $products = Product::with('category')
            ->where('stock_quantity', '<=', 2)
            ->orderBy('stock_quantity', 'ASC')
            ->get();

        $price_change_histories = PriceChangeHistory::with(['product', 'product.category'])
            ->orderBy('change_date', 'DESC')
            ->get()
            ->unique(function ($item) {
                return $item['product_id'].$item['status'];
            })
            ->groupBy('product_id');

        return view('admin.home', [
            'sale_data' => $sale_data,
            'purchase_data' => $purchase_data,
            'products' => $products,
            'price_change_histories' => $price_change_histories,
        ]);
    }
}
