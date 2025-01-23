<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\{Sale, Purchase, Product, PriceChangeHistory};
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getSalesDataForYear(int $year): array
    {
        return Sale::with(['product'])
            ->whereYear('sale_date', $year)
            ->orderBy('sale_date')
            ->get()
            ->groupBy(fn(Sale $sale) =>  Carbon::parse($sale->sale_date)->format('n'))
            ->mapWithKeys(fn(Collection $items, $month) => [$month => $this->aggregateSalesData($items)])
            ->toArray();
    }

    private function aggregateSalesData(Collection $items): array
    {
        return $items->reduce(function (array $carry, Sale $sale) {
            $carry['total_price'] = ($carry['total_price'] ?? 0) + $sale->total_price;
            $carry['total_profit'] = ($carry['total_profit'] ?? 0) +
                ($sale->product->unit_price - $sale->product->unit_cost) * $sale->quantity;
            return $carry;
        }, []);
    }

    public function getPurchaseDataForYear(int $year): array
    {
        return Purchase::selectRaw('MONTH(purchase_date) as month, SUM(total_cost) as total_cost')
            ->whereYear('purchase_date', $year)
            ->groupBy(DB::raw('MONTH(purchase_date)'))
            ->orderBy('month')
            ->get()
            ->mapWithKeys(fn($purchase) => [$purchase['month'] => $purchase['total_cost']])
            ->toArray();
    }

    public function getLowStockProducts(): Collection
    {
        return Product::with('category')
            ->where('stock_quantity', '<=', 2)
            ->orderBy('stock_quantity', 'ASC')
            ->get();
    }

    public function getPriceChangeHistories(): Collection
    {
        return PriceChangeHistory::with(['product', 'product.category'])
            ->orderBy('change_date', 'DESC')
            ->get()
            ->unique(fn($item) => $item['product_id'].$item['status'])
            ->groupBy('product_id');
    }
}
