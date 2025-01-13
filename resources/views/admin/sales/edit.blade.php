@extends('admin.layouts.main')
@section('title', 'Sale Management | Update')
@section('styles')
    <link href="{{ asset('/assets/admin/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="contentbar">
    <form class="form-store" action="{{ route('admin.sales.update', $sale->id) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-5">
                    <div class="card-header">
                        <h5 class="card-title">Sale Registration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="product_id">Product<span class="required">*</span></label>
                                    <select id="product_id" name="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                                        <option value="">選択してください。</option>
                                        @foreach ($products as $product)
                                            <option data-price="{{ $product->unit_price }}" data-stock-quantity="{{ $product->stock_quantity }}" value="{{ $product->id }}" @if (old('product_id', $sale->product_id) == $product->id) selected @endif>
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
                                    <input type="number" id="quantity" name="quantity" min="1" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $sale->quantity) }}" placeholder="Quantity" required>
                                    @error('quantity')
                                    <div id="quantity-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="total_price" class="form-label">Total Price<span class="required">*</span></label>
                                    <input type="number" id="total_price" name="total_price" class="form-control @error('total_price') is-invalid @enderror" value="{{ old('total_price', $sale->total_price) }}" placeholder="Total Price" required>
                                    @error('total_price')
                                    <div id="total_price-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="sale_date" class="form-label">Sale Date<span class="required">*</span></label>
                                    <input type="text" id="sale_date" name="sale_date" class="form-control @error('sale_date') is-invalid @enderror" value="{{ \Carbon\Carbon::parse(old('sale_date', $sale->sale_date))->format('Y-m-d H:i') }}" placeholder="YYYY-MM-DD HH:ii" required>
                                    @error('sale_date')
                                    <div id="sale_date-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('admin.sales.index') }}" class="btn btn-light">Cancel</a>
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
    $('#sale_date').datepicker({
        language: 'en',
        dateFormat: 'yyyy-mm-dd',
        timeFormat: 'hh:ii',
        timepicker: true,
        autoClose: true,
    });

    $(() => {
        $('#quantity').attr('max', $('#product_id option:selected').attr('data-stock-quantity'));
    });

    $('#product_id').on('change', () => {
        $('#quantity').attr('max', $('#product_id option:selected').attr('data-stock-quantity'));

        calculateTotalCost();
    });
    $('#quantity').on('keyup', () => {
        calculateTotalCost();
    });

    const calculateTotalCost = () => {
        if ($('#product_id').val() && $('#quantity').val()) {
            $('#total_price').val($('#product_id option:selected').attr('data-price') * $('#quantity').val());
        } else {
            $('#total_price').val(null);
        }
    };
</script>
@endsection
