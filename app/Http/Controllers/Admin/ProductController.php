<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('updated_at', 'DESC');

        if (request()->filled('category_id')) {
            $products->where('category_id', request('category_id'));
        }

        if (request()->filled('name')) {
            $products->where('name', 'like', '%' . request('name') . '%');
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
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create product. ' . $e->getMessage());
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
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update product. ' . $e->getMessage());
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
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update product. ' . $e->getMessage());
        }
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
