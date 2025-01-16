<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Requests\Admin\UploadRequest;
use App\Jobs\Admin\ExcelImportJob;
use App\Models\Category;
use App\Models\Import;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('stock_quantity', 'ASC');

        if (request()->filled('category_id')) {
            $products->where('category_id', request('category_id'));
        }

        if (request()->filled('name')) {
            $products->where('name', 'like', '%' . request('name') . '%');
        }

        if (request()->filled('stock_quantity')) {
            $products->where('stock_quantity', '<=' ,request('stock_quantity'));
        }

        $products = $products->paginate(config('const.default_paginate_number'));

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::whereHas('products')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => Category::all(),
        ]);
    }

    public function store(ProductRequest $request)
    {
        try {
            DB::transaction(function() use ($request) {
                Product::create($request->all());
            });
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('alert.error', 'Failed to create product. ' . $e->getMessage());
        }
        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', [
            'product' => $product,
        ]);
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::whereHas('products')->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            DB::transaction(function() use ($product, $request) {
                $product->update($request->all());
            });
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('alert.error', 'Failed to update product. ' . $e->getMessage());
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        try {
            DB::transaction(function() use ($product) {
                $product->delete();

            });
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('alert.error', 'Failed to update product. ' . $e->getMessage());
        }
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
    * CSV upload
    */
    public function upload(Request $request)
    {
        $update_request = new UploadRequest();
        $validator = Validator::make($request->all(), $update_request->rules(), [], $update_request->attributes());

        if ($validator->fails()) {
            return back()
                ->with('alert.error','CSV upload failed.');
        }

        $import_file = $request->file('import_file');
        $file_name = $import_file->getClientOriginalName();

        $file_path = $import_file->storeAs(config('const.import_csv_file_path'), $file_name);

        dispatch(new ExcelImportJob($file_path))
            ->onQueue('excel_import');

        return redirect()->route('admin.products.index')
            ->with('alert.success','CSV uploaded successfully.');
    }

    /**
    * CSV export
    */
    public function export()
    {
        $file_name = 'product_' . date('Ymd_His') . '.csv';

        $callback = function()
        {
            $csv = fopen('php://output', 'w');

            fputcsv($csv, [
                'category_name',
                'product_name',
                'unit_cost',
                'unit_price',
                'stock_quantity',
                'create_date'
            ]);

            Product::with('category')
                ->orderBy('stock_quantity')
                ->get()
                ->groupBy(function ($product) {
                    return $product->category->name;
                })
                ->map(function ($products, $category_name) use ($csv) {
                    foreach ($products as $product) {
                        fputcsv($csv, [
                            $category_name,
                            $product->name,
                            $product->unit_cost,
                            $product->unit_price,
                            $product->stock_quantity,
                            $product->created_at,
                        ]);
                    }
                });

            fclose($csv);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $file_name . '"',
        ]);
    }
}
