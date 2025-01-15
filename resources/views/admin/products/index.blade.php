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
                                        <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" value="{{ request()->stock_quantity }}" min="0">
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

                    @forelse ($products as $product)
                        @if ($loop->first)
                        <div class="table-responsive m-b-30">
                            <table id="posts-table" class="table">
                                <thead class="text-nowrap">
                                    <tr>
                                        <th>No.</th>
                                        <th>Category Name</th>
                                        <th>Product Name</th>
                                        <th>Stock Quantity</th>
                                        <th>Unit Cost</th>
                                        <th>Unit Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                        @endif
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ Str::limit($product->category->name, 20) }}</td>
                                        <td>{{ Str::limit($product->name, 20) }}</td>
                                        <td>{{ $product->stock_quantity }}</td>
                                        <td>{{ number_format($product->unit_cost) }}</td>
                                        <td>{{ number_format($product->unit_price) }}</td>
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
                                    <tr class="table-info">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Total</td>
                                        <td>{{ number_format($products->sum('unit_cost')) }}</td>
                                        <td>{{ number_format($products->sum('unit_price')) }}</td>
                                        <td></td>
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
            <form action="{{ route('admin.products.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <input type="file" name="import_file" class="form-control-file" required accept=".csv, .txt">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Import</button>
            </form>
        </div>
    </div>
</div>
@endsection
