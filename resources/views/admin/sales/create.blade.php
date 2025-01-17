@extends('admin.layouts.main')
@section('title', 'Sale Management | Create')
@section('styles')
<link href="{{ asset('/assets/admin/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="contentbar">
    <form class="form-store" action="{{ route('admin.sales.store') }}" method="POST" autocomplete="off">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-5">
                    <div class="card-header">
                        <h5 class="card-title">Sale Registration</h5>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="sale_date" class="form-label">Sale Date<span class="required">*</span></label>
                                    <input type="text" id="sale_date" name="sale_date" class="form-control @error('sale_date') is-invalid @enderror" value="{{ old('sale_date') }}" placeholder="YYYY-MM-DD HH:ii" required>
                                    @error('sale_date')
                                    <div id="sale_date-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="text-right m-3">
                            <button type="button" class="btn btn-primary add_sale">Add Card</button>
                        </div>
                        <div class="sales">
                            <div id="sale_$count" class="card base_sale m-b-30 d-none">
                                <div class="card-header">
                                    <h5 class="card-title">Sale <i class="fa fa-minus-square m-l-30 remove_sale d-none"></i></h5>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="sales_$count_product_id">Product<span class="required">*</span></label>
                                                <select id="sales_$count_product_id" class="form-control product_id" name="sales[$count][product_id]" disabled required>
                                                    <option value="">ရွေးချယ်ပါ</option>
                                                    @foreach ($products as $product)
                                                    <option data-price="{{ $product->unit_price }}" data-cost="{{ $product->unit_cost }}" data-stock-quantity="{{ $product->stock_quantity }}" value="{{ $product->id }}">
                                                        {{ $product->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="sales _$count_quantity" class="form-label">Quantity<span class="required">*</span></label>
                                                <input type="number" min="1" id="sales _$count_quantity" class="quantity form-control" name="sales[$count][quantity]" placeholder="Quantity" disabled required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="sales_$count_total_price" class="form-label">Total Price<span class="required">*</span></label>
                                                <input type="number" min="1" id="sales_$count_total_price" class="total_price form-control" name="sales[$count][total_price]" placeholder="Total Price" disabled required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="sales_$count_profit" class="form-label">Profit</label>
                                                <input type="number" id="sales_$count_profit" class="profit form-control" name="sales[$count][profit]" placeholder="Profit" disabled readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @foreach (old('sales', []) as $old)
                            <div id="sale_{{ $loop->index }}" class="card base_sale m-b-30 d-none">
                                <div class="card-header">
                                    <h5 class="card-title">Sale <i class="fa fa-minus-square m-l-30 remove_sale {{ $loop->index > 0 ? '' : 'd-none' }}"></i></h5>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="sales_{{ $loop->index }}_product_id">Product<span class="required">*</span></label>
                                                <select id="sales_{{ $loop->index }}_product_id" name="sales[{{ $loop->index }}]['product_id']" class="product_id form-control @error('sales_' . $loop->index . '_product_id') is-invalid @enderror" required>
                                                    <option value="">ရွေးချယ်ပါ</option>
                                                    @foreach ($products as $product)
                                                    <option data-price="{{ $product->unit_price }}" data-cost="{{ $product->unit_cost }}" data-stock-quantity="{{ $product->stock_quantity }}" value="{{ $product->id }}" @if ($old->product_id == $product->id) selected @endif>
                                                        {{ $product->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('sales_' . $loop->index . '_product_id')
                                                <div id="sales_{{ $loop->index }}_product_id-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="sales_{{ $loop->index }}_quantity" class="form-label">Quantity<span class="required">*</span></label>
                                                <input type="number" min="1" id="sales_{{ $loop->index }}_quantity" name="sale[{{ $loop->index }}]['quantity']" class="quantity form-control @error('sales_' . $loop->index . '_quantity') is-invalid @enderror" value="{{ $old->quantity }}" placeholder="Quantity" required>
                                                @error('sales_' . $loop->index . '_quantity')
                                                <div id="sales_{{ $loop->index }}_quantity-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="sales_{{ $loop->index }}_total_price" class="form-label">Total Price<span class="required">*</span></label>
                                                <input type="number" min="1" id="sales_{{ $loop->index }}_total_price" name="sale[{{ $loop->index }}]['total_price']" class="total_price form-control @error('sales_' . $loop->index . '_total_price') is-invalid @enderror" value="{{ $old->total_price }}" placeholder="Total Price" required>
                                                @error('sales_' . $loop->index . '_total_price')
                                                <div id="sales_{{ $loop->index }}_total_price-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="sales_{{ $loop->index }}_profit" class="form-label">Profit</label>
                                                <input type="number" id="sales_{{ $loop->index }}_profit" name="sale[{{ $loop->index }}]['profit']" class="profit form-control @error('sales_' . $loop->index . '_profit') is-invalid @enderror" value="{{ $old->profit }}" placeholder="Profit" readonly>
                                                @error('sales_' . $loop->index . '_profit')
                                                <div id="sales_{{ $loop->index }}_profit-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <a href="{{ route('admin.sales.index') }}" class="btn btn-light">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('admin.sales.index') }}" class="btn btn-light">Cancel</a>
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
    $('#sale_date').datepicker({
        language: 'en',
        dateFormat: 'yyyy-mm-dd',
        timeFormat: 'hh:ii',
        timepicker: true,
        autoClose: true,
    });

    let count = $('.sale').length;

    const addSale = () => {
        const clone = $('.base_sale').clone(true);
        clone.removeClass('base_sale d-none').addClass('sale');
        clone.find(':input').prop('disabled', false);
        if (count > 0) {
            clone.find('.remove_sale').removeClass('d-none');
        }

        const replace = clone.prop('outerHTML').replace(/\$count/g, count);
        const node = $.parseHTML(replace);
        $(node).find('span.select2').remove();
        $(node).appendTo('.sales');

        $('.sale:last').find('select.product_id').select2();

        count++;
    }

    const isInit = {{ json_encode(old('_token', null) === null) }};
    if (isInit) {
        addSale();
    } else {
        $('.product_id').select2();
    }

    $('.add_sale').on('click', () => {
        addSale();
    });

    $(document).on('click', '.remove_sale', (e) => {
        $(e.currentTarget).closest('.sale').remove();
    });


    $(document).on('change', '.product_id', function (e) {
        let target = $(e.currentTarget).closest('.sale');

        target.find('.quantity').attr('max', target.find('.product_id option:selected').attr('data-stock-quantity'));

        calculateTotalCost(e);
    });

    $(document).on('keyup', '.quantity', function (e) {
        calculateTotalCost(e);
    });

    $(document).on('keyup', '.total_price', function (e) {
        let target = $(e.currentTarget).closest('.sale');

        target.find('.profit').val(((target.find('.total_price').val() / target.find('.quantity').val()) - target.find('.product_id option:selected').attr('data-cost') * target.find('.quantity').val()));
    });

    const calculateTotalCost = (e) => {
        let target = $(e.currentTarget).closest('.sale');

        if (target.find('.product_id').val() && target.find('.quantity').val()) {
            const sale_price = target.find('.product_id option:selected').attr('data-price') * target.find('.quantity').val();
            target.find('.total_price').val(sale_price);

            target.find('.profit').val(sale_price - (target.find('.product_id option:selected').attr('data-cost') * target.find('.quantity').val()));
        } else {
            target.find('.total_price').val(null);
            target.find('.profit').val(null);
        }
    };
</script>
@endsection
