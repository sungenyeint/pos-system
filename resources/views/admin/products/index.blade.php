@extends('admin.layouts.main')
@section('title', 'Product Management')
@section('widgetbar')
<a class="btn btn-outline-primary" href="{{ route('admin.products.export') }}" target="_blank"><i class="ri-chat-download-line align-middle mr-2"></i>CSV Export</a>
<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#import_csv"><i class="ri-chat-upload-line align-middle mr-2"></i>CSV Import</button>
<a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary"><i class="ri-add-line align-middle mr-2"></i>Add</a>
@endsection
@section('content')

<div class="contentbar">
    <div class="row">
        <div class="col-lg-12">
            <form method="GET" class="form-search" action="{{ route('admin.products.index') }}" autocomplete="off">
                <div class="card m-b-30" id="search_box">
                    <div class="card-header collapsed" data-toggle="collapse" data-target="#searchCollapse" aria-expanded="false" style="cursor: pointer;">
                        <h5 class="card-title">Search</h5>
                    </div>
                    <div id="searchCollapse" class="collapse">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="category_id">Category Name</label>
                                        <select id="category_id" name="category_id" class="form-control">
                                            <option value="">ရွေးချယ်ပါ</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @if (request()->category_id == $category->id) selected @endif>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="name">Product Name</label>
                                        <input type="text" id="name" name="name" class="form-control" value="{{ request()->name }}" placeholder="Product Name">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="name">Stock Quantity</label>
                                        <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" value="{{ request()->stock_quantity }}" min="0" placeholder="Stock Quantity">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="button" id="reset" class="btn btn-light form-reset">Reset</button>
                            <button type="submit" id="search" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <h5 class="card-title">product List</h5>
                </div>
                <div class="card-body">
                    @php
                        $total_sale_price = 0;
                        $total_purchase_price = 0;
                        $total_profit = 0;
                    @endphp
                    @forelse ($products as $product)
                        @if ($loop->first)
                        <div class="table-responsive m-b-30">
                            <table id="posts-table" class="table table-striped">
                                <thead class="text-nowrap thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>@sortablelink('category.name', 'Category Name')</th>
                                        <th>@sortablelink('name', 'Product Name')</th>
                                        <th>@sortablelink('stock_quantity', 'Stock Quantity')</th>
                                        <th>@sortablelink('unit_cost', 'Unit Cost')</th>
                                        <th>@sortablelink('unit_price', 'Unit Price')</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                        @endif
                                    <tr>
                                        @php
                                            $total_sale_price += $product->unit_price * $product->stock_quantity;
                                            $total_purchase_price += $product->unit_cost * $product->stock_quantity;
                                        @endphp
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ Str::limit($product->category->name, 20) }}</td>
                                        <td>{{ Str::limit($product->name, 20) }}</td>
                                        <td>{{ $product->stock_quantity }}</td>
                                        <td>{{ number_format($product->unit_cost) }}ကျပ်</td>
                                        <td>{{ number_format($product->unit_price) }}ကျပ်</td>
                                        <td>
                                            <form method="POST" class="form-destroy" action="{{ route('admin.products.destroy', $product->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn btn-outline-success">
                                                    <i class="feather icon-edit mr-2"></i>Edit
                                                </a>
                                                @if ($product->id)
                                                <button type="submit" class="btn btn btn-outline-danger"><i class="feather icon-trash-2 mr-2"></i>Delete</button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                        @if ($loop->last)
                                    <tr class="table-primary" >
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>၀ယ်စျေး</td>
                                        <td>ရောင်းစျေး</td>
                                        <td>အမြတ်</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>စုစုပေါင်း</td>
                                        <td>{{ number_format($total_purchase_price) }}ကျပ်</td>
                                        <td>{{ number_format($total_sale_price) }}ကျပ်</td>
                                        <td>{{ number_format($total_sale_price - $total_purchase_price) }}ကျပ်</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @endif
                    @empty
                        <p>There is no information created.</p>
                    @endforelse

                </div>
                @if ($products->count() > 0)
                <div class="card-footer clearfix">
                    {{ $products->appends(request()->input())->links('pagination::admin') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="import_csv" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">CSV Import</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <input type="file" name="import_file" class="form-control-file" required accept=".csv, .txt">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="import" class="btn btn-primary" type="submit">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
<script>
    $('#import').on('click', () => {
        $.LoadingOverlay("show");
    });
</script>
@endsection
