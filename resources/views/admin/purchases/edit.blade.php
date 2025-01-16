@extends('admin.layouts.main')
@section('title', 'Purchase Management | Update')
@section('styles')
    <link href="{{ asset('/assets/admin/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="contentbar">
    <form class="form-store" action="{{ route('admin.purchases.update', $purchase->id) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-5">
                    <div class="card-header">
                        <h5 class="card-title">Purchase Registration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="product_id">Product<span class="required">*</span></label>
                                    <select id="product_id" name="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                                        <option value="">選択してください。</option>
                                        @foreach ($products as $product)
                                            <option data-cost="{{ $product->unit_cost }}" value="{{ $product->id }}" @if (old('product_id', $purchase->product_id) == $product->id) selected @endif>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div id="product_id-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="quantity" class="form-label">Quantity<span class="required">*</span></label>
                                    <input type="number" id="quantity" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $purchase->quantity) }}" maxlength="{{ config('const.default_text_maxlength') }}" placeholder="Quantity" required>
                                    @error('quantity')
                                    <div id="quantity-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="total_cost" class="form-label">Total Cost<span class="required">*</span></label>
                                    <input type="number" id="total_cost" name="total_cost" class="form-control @error('total_cost') is-invalid @enderror" value="{{ old('total_cost', $purchase->total_cost) }}" maxlength="{{ config('const.default_text_maxlength') }}" placeholder="Total Cost" required>
                                    @error('total_cost')
                                    <div id="total_cost-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="purchase_date" class="form-label">Purchase Date<span class="required">*</span></label>
                                    <input type="text" id="purchase_date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ \Carbon\Carbon::parse(old('purchase_date', $purchase->purchase_date))->format('Y-m-d H:i') }}" maxlength="{{ config('const.default_text_maxlength') }}" placeholder="YYYY-MM-DD HH:ii" required>
                                    @error('purchase_date')
                                    <div id="purchase_date-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('admin.purchases.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
@section('js')
<script src="{{ asset('/assets/admin/plugins/datepicker/datepicker.min.js') }}"></script>
<script src="{{ asset('/assets/admin/plugins/datepicker/i18n/datepicker.en.js') }}"></script>
<script>
    $('#purchase_date').datepicker({
        language: 'en',
        dateFormat: 'yyyy-mm-dd',
        timeFormat: 'hh:ii',
        timepicker: true,
        autoClose: true,
    });

    $('#product_id').on('change', () => {
        calculateTotalCost();
    });
    $('#quantity').on('keyup', () => {
        calculateTotalCost();
    });

    const calculateTotalCost = () => {
        if ($('#product_id').val() && $('#quantity').val()) {
            $('#total_cost').val($('#product_id option:selected').attr('data-cost') * $('#quantity').val());
        } else {
            $('#total_cost').val(null);
        }
    };
</script>
@endsection
