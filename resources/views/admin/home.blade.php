@extends('admin.layouts.main')
@section('title', 'Dashboard')

@section('content')
    <div class="contentbar">
        <!-- Start row -->
        <div class="row">
            <!-- Start col -->
            <div class="col-lg-12 col-xl-6">
                <div class="card m-b-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Purchase</h5>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h4>{{ $purchase_data ? number_format(max($purchase_data)) : '' }}</h4>
                            </div>
                            <div class="col-6 text-right">
                                @php
                                    $now_month = \Carbon\Carbon::now()->month + 1;
                                    $current_month = isset($purchase_data[$now_month]) ? $purchase_data[$now_month] : 0;
                                    $previous_month = isset($purchase_data[$now_month - 1]) ? $purchase_data[$now_month - 1] : 0;

                                    $difference = 0;
                                    if ($previous_month) {
                                        $difference = ($current_month / $previous_month) * 100;
                                    }
                                @endphp
                                <p class="mb-0"><i class="ri-arrow-right-up-line text-success align-middle font-18 mr-1"></i>{{ $difference }}%</p>
                                <p class="mb-0">This month</p>
                            </div>
                        </div>
                        <div id="apex-line-chart1"></div>
                    </div>
                </div>
            </div>
            <!-- End col -->
            <!-- Start col -->
            <div class="col-lg-12 col-xl-6">
                <div class="card m-b-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Sale</h5>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h4>{{ $sale_data ? number_format(max($sale_data)) : '' }}</h4>
                            </div>
                            <div class="col-6 text-right">
                                <p class="mb-0"><i class="ri-arrow-right-down-line text-danger align-middle font-18 mr-1"></i>15%</p>
                                <p class="mb-0">This week</p>
                            </div>
                        </div>
                        <div id="apex-line-chart2"></div>
                    </div>
                </div>
            </div>
            <!-- End col -->
        </div>
        <!-- End row -->
        <!-- Start row -->
        <div class="row">
            <!-- Start col -->
            <div class="col-lg-12 col-xl-4">
                <div class="card m-b-30">
                    <div class="card-header text-center">
                        <h5 class="card-title mb-0">Project Status</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="apex-circle-chart"></div>
                    </div>
                </div>
            </div>
            <!-- End col -->
            <!-- Start col -->
            <div class="col-lg-12 col-xl-8">
                <div class="card m-b-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Profit & Expenses</h5>
                    </div>
                    <div class="card-body py-0">
                        <div class="row align-items-center">
                            <div class="col-lg-12 col-xl-4">
                                <h4 class="text-muted"><sup>$</sup>59876.00</h4>
                                <p>Current Balance</p>
                                <ul class="list-unstyled my-5">
                                    <li><i class="ri-checkbox-blank-circle-fill text-primary font-10 mr-2"></i>Amount Earned</li>
                                    <li><i class="ri-checkbox-blank-circle-fill text-success font-10 mr-2"></i>Amount Spent</li>
                                </ul>
                                <button type="button" class="btn btn-primary">Export<i class="ri-arrow-right-line align-middle ml-2"></i></button>
                            </div>
                            <div class="col-lg-12 col-xl-8">
                                <div id="apex-horizontal-bar-chart"></div>
                            </div>
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
    const sales = @json($sale_data);
    for (let i = 1; i <= 12; i++) {
        if (!sales.hasOwnProperty(i)) {
            sales[i] = '0'; // Add the missing key with the default value
        }
    }

    const purchases = @json($purchase_data);
    for (let i = 1; i <= 12; i++) {
        if (!purchases.hasOwnProperty(i)) {
            purchases[i] = '0'; // Add the missing key with the default value
        }
    }

</script>
<script src="{{ asset('assets/admin/js/custom/custom-dashboard.js') }}"></script>
@endsection
