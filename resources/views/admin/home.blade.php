@extends('admin.layouts.main')
@section('title', 'Dashboard')

@section('content')
    <div class="contentbar">
        <!-- Start row -->
        <div class="row">
            <!-- Start col -->
            <div class="col-lg-12 col-xl-4">
                <div class="card m-b-30">
                    <div class="card-header">
                        <h3 class="card-title mb-0">အ၀ယ်စရင်း</h3>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h4>{{ $purchase_data ? number_format($purchase_data[\Carbon\Carbon::now()->month]) : 0 }}ကျပ်</h4>
                            </div>
                            <div class="col-6 text-right">
                                @php
                                    $now_month = \Carbon\Carbon::now()->month + 1;
                                    $purchase_current_month = isset($purchase_data[$now_month]) ? $purchase_data[$now_month] : 0;
                                    $purchase_previous_month = isset($purchase_data[$now_month - 1]) ? $purchase_data[$now_month - 1] : 0;

                                    $difference = 0;
                                    if ($purchase_previous_month && $purchase_current_month < $purchase_previous_month) {
                                        $difference = ($purchase_current_month / $purchase_previous_month) * 100;
                                    } else if ($purchase_current_month && $purchase_previous_month < $purchase_current_month) {
                                        $difference = ($purchase_previous_month / $purchase_current_month) * 100;
                                    }
                                @endphp
                                <p class="mb-0"><i class="{{ ($purchase_current_month > $purchase_previous_month) ? 'ri-arrow-right-up-line text-success' : 'ri-arrow-right-down-line text-danger' }} align-middle font-18 mr-1"></i>{{ ceil($difference) }}%</p>
                                <p class="mb-0">ယခုလ</p>
                            </div>
                        </div>
                        <div id="apex-line-chart1"></div>
                    </div>
                </div>
            </div>
            <!-- End col -->
            <!-- Start col -->
            <div class="col-lg-12 col-xl-4">
                <div class="card m-b-30">
                    <div class="card-header">
                        <h3 class="card-title mb-0">အရောင်းစရင်း</h3>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h4>{{ $sale_data ? number_format($sale_data[\Carbon\Carbon::now()->month]['total_price']) : 0 }}ကျပ်</h4>
                            </div>
                            <div class="col-6 text-right">
                                @php
                                    $now_month = \Carbon\Carbon::now()->month + 1;
                                    $sale_current_month = isset($sale_data[$now_month]['total_price']) ? $sale_data[$now_month]['total_price'] : 0;
                                    $sale_previous_month = isset($sale_data[$now_month - 1]['total_price']) ? $sale_data[$now_month - 1]['total_price'] : 0;

                                    $difference = 0;
                                    if ($sale_previous_month && $sale_current_month < $sale_previous_month) {
                                        $difference = ($sale_current_month / $sale_previous_month) * 100;
                                    } else if ($sale_current_month && $sale_previous_month < $sale_current_month) {
                                        $difference = ($sale_previous_month / $sale_current_month) * 100;
                                    }
                                @endphp
                                <p class="mb-0"><i class="{{ ($sale_current_month > $sale_previous_month) ? 'ri-arrow-right-up-line text-success' : 'ri-arrow-right-down-line text-danger' }} align-middle font-18 mr-1"></i>{{ ceil($difference) }}%</p>
                                <p class="mb-0">ယခုလ</p>
                            </div>
                        </div>
                        <div id="apex-line-chart2"></div>
                    </div>
                </div>
            </div>
            <!-- End col -->
            <!-- Start col -->
            <div class="col-lg-12 col-xl-4">
                <div class="card m-b-30">
                    <div class="card-header">
                        <h3 class="card-title mb-0">အမြတ်စရင်း</h3>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h4>{{ $sale_data ? number_format($sale_data[\Carbon\Carbon::now()->month]['total_profit']) : 0 }}ကျပ်</h4>
                            </div>
                            <div class="col-6 text-right">
                                @php
                                    $now_month = \Carbon\Carbon::now()->month + 1;
                                    $sale_current_month = isset($sale_data[$now_month]['total_profit']) ? $sale_data[$now_month]['total_profit'] : 0;
                                    $sale_previous_month = isset($sale_data[$now_month - 1]['total_profit']) ? $sale_data[$now_month - 1]['total_profit'] : 0;

                                    $difference = 0;
                                    if ($sale_previous_month && $sale_current_month < $sale_previous_month) {
                                        $difference = ($sale_current_month / $sale_previous_month) * 100;
                                    } else if ($sale_current_month && $sale_previous_month < $sale_current_month) {
                                        $difference = ($sale_previous_month / $sale_current_month) * 100;
                                    }
                                @endphp
                                <p class="mb-0"><i class="{{ ($sale_current_month > $sale_previous_month) ? 'ri-arrow-right-up-line text-success' : 'ri-arrow-right-down-line text-danger' }} align-middle font-18 mr-1"></i>{{ ceil($difference) }}%</p>
                                <p class="mb-0">ယခုလ</p>
                            </div>
                        </div>
                        <div id="apex-line-chart3"></div>
                    </div>
                </div>
            </div>
            <!-- End col -->
        </div>
        <!-- End row -->
        <!-- Start row -->
        <div class="row">
            <!-- Start col -->
            <div class="col-lg-12 col-xl-12">
                <div class="card m-b-30">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Stock ကုန်ခါနီး ပစွည်းများ</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="card-body">

                            @forelse ($products as $product)
                                @if ($loop->first)
                                <div class="table-responsive m-b-30">
                                    <table id="posts-table" class="table table-hover">
                                        <thead class="text-nowrap thead-dark">
                                            <tr>
                                                <th>No.</th>
                                                <th>Category Name</th>
                                                <th>Product Name</th>
                                                <th>Stock Quantity</th>
                                                <th>Unit Cost</th>
                                                <th>Unit Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                @endif
                                            <tr class="{{$product->stock_quantity === 0 ? 'table-danger' : ''}}">
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ Str::limit($product->category->name, 20) }}</td>
                                                <td>{{ Str::limit($product->name, 20) }}</td>
                                                <td>{{ $product->stock_quantity }}</td>
                                                <td>{{ number_format($product->unit_cost) }}ကျပ်</td>
                                                <td>{{ number_format($product->unit_price) }}ကျပ်</td>
                                            </tr>
                                @if ($loop->last)
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            @empty
                                <p>There is no information.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <!-- End col -->
        </div>
        <!-- End row -->
    </div>
    <!-- End Contentbar -->
@endsection

@section('js')
<!-- Apex js -->
<script src="{{ asset('assets/admin/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/apexcharts/irregular-data-series.js') }}"></script>
<!-- Slick js -->
<script src="{{ asset('assets/admin/plugins/slick/slick.min.js') }}"></script>
<!-- Custom Dashboard js -->
<script>
    const sale_data = @json($sale_data);

    let sales = [];
    let profits = [];

    for (let i = 1; i <= 12; i++) {
        if (!sale_data.hasOwnProperty(i)) {
            console.log('no ', i);
            sales[i] = 0; // Add the missing key with the default value
            profits[i] = 0; // Add the missing key with the default value
        } else {
            console.log('yes ', i);
            sales[i] = sale_data[i]['total_price'];
            profits[i] = sale_data[i]['total_profit'];
        }
    }
console.log(profits);
console.log(sales);
    const purchases = @json($purchase_data);
    for (let i = 1; i <= 12; i++) {
        if (!purchases.hasOwnProperty(i)) {
            purchases[i] = '0'; // Add the missing key with the default value
        }
    }

</script>
<script src="{{ asset('assets/admin/js/custom/custom-dashboard.js') }}"></script>
@endsection
