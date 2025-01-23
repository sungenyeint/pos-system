<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\View\View;

class HomeController extends Controller
{
    private DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function home(): View
    {
        $currentYear = Carbon::now()->year;

        return view('admin.home', [
            'sale_data' => $this->dashboardService->getSalesDataForYear($currentYear),
            'purchase_data' => $this->dashboardService->getPurchaseDataForYear($currentYear),
            'products' => $this->dashboardService->getLowStockProducts(),
            'price_change_histories' => $this->dashboardService->getPriceChangeHistories(),
        ]);
    }
}
