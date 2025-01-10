@extends('admin.layouts.main')
@section('title', 'Product Management | Create')
@section('content')

<div class="contentbar">
    <form class="form-store" action="{{ route('admin.products.store') }}" method="POST" autocomplete="off">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-5">
                    <div class="card-header">
                        <h5 class="card-title">Product Registration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="category_id">Category<span class="required">*</span></label>
                                    <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                        <option value="">選択してください。</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @if (old('category_id') == $category->id) selected @endif>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div id="category_id-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Name<span class="required">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" maxlength="{{ config('const.default_text_maxlength') }}" placeholder="name" required>
                                    @error('name')
                                    <div id="name-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="unit_cost" class="form-label">Unit Cost<span class="required">*</span></label>
                                    <input type="number" id="unit_cost" name="unit_cost" class="form-control @error('unit_cost') is-invalid @enderror" value="{{ old('unit_cost') }}" maxlength="{{ config('const.default_text_maxlength') }}" placeholder="Unit Cost" required>
                                    @error('unit_cost')
                                    <div id="unit_cost-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="unit_price" class="form-label">Unit Price<span class="required">*</span></label>
                                    <input type="number" id="unit_price" name="unit_price" class="form-control @error('unit_price') is-invalid @enderror" value="{{ old('unit_price') }}" maxlength="{{ config('const.default_text_maxlength') }}" placeholder="Unit Price" required>
                                    @error('unit_price')
                                    <div id="unit_price-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="quantity" class="form-label">Stock Quantity<span class="required">*</span></label>
                                    <input type="number" id="stock_quantity" name="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity') }}" maxlength="{{ config('const.default_text_maxlength') }}" placeholder="Stock Quantity" required>
                                    @error('stock_quantity')
                                    <div id="stock_quantity-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
