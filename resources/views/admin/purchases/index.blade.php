@extends('admin.layouts.main')
@section('title', 'Purchase Management')
@section('widgetbar')
<a href="{{ route('admin.purchases.create') }}" class="btn btn-outline-primary"><i class="ri-add-line align-middle mr-2"></i>Add</a>
@endsection
@section('content')

<div class="contentbar">
    <div class="row">
        <div class="col-lg-12">
            <form method="GET" class="form-search" action="{{ route('admin.purchases.index') }}" autocomplete="off">
                <div class="card m-b-30" id="search_box">
                    <div class="card-header collapsed" data-toggle="collapse" data-target="#searchCollapse" aria-expanded="false" style="cursor: pointer;">
                        <h5 class="card-title">Search</h5>
                    </div>
                    <div id="searchCollapse" class="collapse">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="category_id">Category</label>
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
                                        <label for="product_id">Product</label>
                                        <select id="product_id" name="product_id" class="form-control">
                                            <option value="">ရွေးချယ်ပါ</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" @if (request()->product_id == $product->id) selected @endif>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="purchase_month">Purchase Month</label>
                                        <select id="purchase_month" name="purchase_month" class="form-control">
                                            <option value="">ရွေးချယ်ပါ</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ \Carbon\Carbon::create()->month($i)->format('F') }}" @if (request()->purchase_month == \Carbon\Carbon::create()->month($i)->format('F')) selected @endif>
                                                    {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                                </option>
                                            @endfor
                                        </select>
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
                    <h5 class="card-title">Purchase List</h5>
                </div>
                <div class="card-body">

                    @forelse ($purchases as $purchase)
                        @if ($loop->first)
                        <div class="table-responsive m-b-30">
                            <table id="posts-table" class="table table-striped">
                                <thead class="text-nowrap thead-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Product</th>
                                        <th>Purchase Date</th>
                                        <th>Quantity</th>
                                        <th>Total Cost</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                        @endif
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ Str::limit($purchase->product->name, 20) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d H:i') }}</td>
                                        <td>{{ $purchase->quantity }}</td>
                                        <td>{{ number_format($purchase->total_cost) }}ကျပ်</td>
                                        <td>
                                            <form method="POST" class="form-destroy" action="{{ route('admin.purchases.destroy', $purchase->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('admin.purchases.edit', $purchase->id) }}" class="btn btn btn-outline-success">
                                                    <i class="feather icon-edit mr-2"></i>Edit
                                                </a>
                                                @if ($purchase->id)
                                                <button type="submit" class="btn btn btn-outline-danger"><i class="feather icon-trash-2 mr-2"></i>Delete</button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                        @if ($loop->last)
                                    <tr class="table-primary">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>စုစုပေါင်း</td>
                                        <td>{{ number_format($purchases->sum('total_cost')) }}ကျပ်</td>
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
                @if ($purchases->count() > 0)
                <div class="card-footer clearfix">
                    {{ $purchases->appends(request()->input())->links('pagination::admin') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    $('#product_id').select2();
</script>
@endsection
