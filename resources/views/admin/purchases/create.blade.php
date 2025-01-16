@extends('admin.layouts.main')
@section('title', 'Purchase Management | Create')
@section('styles')
<link href="{{ asset('/assets/admin/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="contentbar">
    <form class="form-store" action="{{ route('admin.purchases.store') }}" method="POST" autocomplete="off">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-5">
                    <div class="card-header">
                        <h5 class="card-title">Purchase Registration</h5>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="purchase_date" class="form-label">Purchase Date<span class="required">*</span></label>
                                    <input type="text" id="purchase_date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date') }}" placeholder="YYYY-MM-DD HH:ii" required>
                                    @error('purchase_date')
                                    <div id="purchase_date-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="text-right m-3">
                            <button type="button" class="btn btn-primary add_purchase">Add Card</button>
                        </div>
                        <div class="purchases">
                            <div id="purchase_$count" class="card base_purchase m-b-30 d-none">
                                <div class="card-header">
                                    <h5 class="card-title">Purchase <i class="fa fa-minus-square m-l-30 remove_purchase d-none"></i></h5>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="purchases_$count_product_id">Product<span class="required">*</span></label>
                                                <select id="purchases_$count_product_id" class="form-control product_id" name="purchases[$count][product_id]" disabled required>
                                                    <option value="">ရွေးချယ်ပါ</option>
                                                    @foreach ($products as $product)
                                                    <option data-cost="{{ $product->unit_cost }}" data-stock-quantity="{{ $product->stock_quantity }}" value="{{ $product->id }}">
                                                        {{ $product->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="purchases _$count_quantity" class="form-label">Quantity<span class="required">*</span></label>
                                                <input type="number" min="1" id="purchases _$count_quantity" class="quantity form-control" name="purchases[$count][quantity]" placeholder="Quantity" disabled required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="purchases_$count_total_cost" class="form-label">Total Cost<span class="required">*</span></label>
                                                <input type="number" min="1" id="purchases_$count_total_cost" class="total_cost form-control" name="purchases[$count][total_cost]" placeholder="Total Cost" disabled required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @foreach (old('purchases', []) as $old)
                            <div id="purchase_{{ $loop->index }}" class="card base_purchase m-b-30 d-none">
                                <div class="card-header">
                                    <h5 class="card-title">Purchase <i class="fa fa-minus-square m-l-30 remove_purchase {{ $loop->index > 0 ? '' : 'd-none' }}"></i></h5>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="purchases_{{ $loop->index }}_product_id">Product<span class="required">*</span></label>
                                                <select id="purchases_{{ $loop->index }}_product_id" name="purchases[{{ $loop->index }}]['product_id']" class="product_id form-control @error('purchases_' . $loop->index . '_product_id') is-invalid @enderror" required>
                                                    <option value="">ရွေးချယ်ပါ</option>
                                                    @foreach ($products as $product)
                                                    <option data-cost="{{ $product->unit_cost }}" data-stock-quantity="{{ $product->stock_quantity }}" value="{{ $product->id }}" @if ($old->product_id == $product->id) selected @endif>
                                                        {{ $product->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('purchases_' . $loop->index . '_product_id')
                                                <div id="purchases_{{ $loop->index }}_product_id-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="purchases_{{ $loop->index }}_quantity" class="form-label">Quantity<span class="required">*</span></label>
                                                <input type="number" min="1" id="purchases_{{ $loop->index }}_quantity" name="sale[{{ $loop->index }}]['quantity']" class="quantity form-control @error('purchases_' . $loop->index . '_quantity') is-invalid @enderror" value="{{ $old->quantity }}" placeholder="Quantity" required>
                                                @error('purchases_' . $loop->index . '_quantity')
                                                <div id="purchases_{{ $loop->index }}_quantity-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="purchases_{{ $loop->index }}_total_cost" class="form-label">Total Cost<span class="required">*</span></label>
                                                <input type="number" min="1" id="purchases_{{ $loop->index }}_total_cost" name="sale[{{ $loop->index }}]['total_cost']" class="total_cost form-control @error('purchases_' . $loop->index . '_total_cost') is-invalid @enderror" value="{{ $old->total_cost }}" placeholder="Total Cost" required>
                                                @error('purchases_' . $loop->index . '_total_cost')
                                                <div id="purchases_{{ $loop->index }}_total_cost-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <a href="{{ route('admin.purchases.index') }}" class="btn btn-light">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('admin.purchases.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
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

    let count = $('.sale').length;

    const addPurchase = () => {
        const clone = $('.base_purchase').clone(true);
        clone.removeClass('base_purchase d-none').addClass('purchase');
        clone.find(':input').prop('disabled', false);
        if (count > 0) {
            clone.find('.remove_purchase').removeClass('d-none');
        }

        const replace = clone.prop('outerHTML').replace(/\$count/g, count);
        const node = $.parseHTML(replace);
        $(node).find('span.select2').remove();
        $(node).appendTo('.purchases');

        $('.purchase:last').find('select.product_id').select2();

        count++;
    }

    const isInit = {{ json_encode(old('_token', null) === null) }};
    if (isInit) {
        addPurchase();
    } else {
        $('.product_id').select2();
    }

    $('.add_purchase').on('click', () => {
        addPurchase();
    });

    $(document).on('click', '.remove_purchase', (e) => {
        $(e.currentTarget).closest('.purchase').remove();
    });


    $(document).on('change', '.product_id', function (e) {
        calculateTotalCost(e);
    });

    $(document).on('keyup', '.quantity', function (e) {
        calculateTotalCost(e);
    });

    const calculateTotalCost = (e) => {
        let target = $(e.currentTarget).closest('.purchase');

        if (target.find('.product_id').val() && target.find('.quantity').val()) {
            target.find('.total_cost').val(target.find('.product_id option:selected').attr('data-cost') * target.find('.quantity').val());

        } else {
            target.find('.total_cost').val(null);
        }
    };
</script>
@endsection
