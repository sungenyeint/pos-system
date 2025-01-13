<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home()
    {
        $currentYear = Carbon::now()->year;

        $sale_data = Sale::selectRaw('MONTH(sale_date) as month, SUM(total_price) as total_price')
            ->whereYear('sale_date', $currentYear)
            ->groupBy(DB::raw('MONTH(sale_date)'))
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item['month'] => $item['total_price']];
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

        return view('admin.home', [
            'sale_data' => $sale_data,
            'purchase_data' => $purchase_data
        ]);
    }
}
