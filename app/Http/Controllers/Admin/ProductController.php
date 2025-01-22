<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ArrayException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Requests\Admin\UploadRequest;
use App\Models\Category;
use App\Models\PriceChangeHistory;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->sortable(['updated_at' => 'desc']);

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
                if ($product->unit_cost != $request->unit_cost) {
                    // add price_change_history table
                    PriceChangeHistory::create([
                        'product_id' => $product->id,
                        'price_change' => $request->unit_cost - $product->unit_cost,
                        'status' => 'purchase',
                        'change_date' => Carbon::now(),
                    ]);
                }

                if ($product->unit_price != $request->unit_price) {
                    // add price_change_history table
                    PriceChangeHistory::create([
                        'product_id' => $product->id,
                        'price_change' => $request->unit_price - $product->unit_price,
                        'status' => 'sale',
                        'change_date' => Carbon::now(),
                    ]);
                }

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
    * CSV import
    */
    public function import(Request $request)
    {
        $update_request = new UploadRequest();
        $validator = Validator::make($request->all(), $update_request->rules(), [], $update_request->attributes());

        if ($validator->fails()) {
            return back()
                ->with('alert.error','CSV upload failed.');
        }

        $import_file = $request->file('import_file');

        $file = new \SplFileObject($import_file);

        $file->setFlags(
            \SplFileObject::READ_CSV |
            \SplFileObject::READ_AHEAD |
            \SplFileObject::SKIP_EMPTY
        );

        $row_num = 2;

        $errors = '';
        foreach ($file as $i => $line) {
            $row_num = $i + 1;
            logger()->info('$line', $line);

            if ($i < 1) {
                continue;
            }

            logger()->info('Line info', [
                'row_num' => $row_num,
            ]);

            try {
                $category = Category::where('name', $line[0])->first();

                if ($category === null) {
                    throw new Exception('Category data does not exist.');
                }

                $import_data = [
                    'category_id' => $category->id,
                    'name' => $line[1],
                    'unit_cost' => $line[2],
                    'unit_price' => $line[3],
                    'stock_quantity' => $line[4],
                ];

                logger()->info('$import_data', $import_data);

                $this->validation($import_data, $import_data['unit_cost']);

                $product = Product::create($import_data);
                logger()->info('Create $product', $product->toArray());

            } catch (ArrayException $ae) {
                $errors .= $row_num . ' : ' . implode(',', $ae->getMessages()) . '<br>';
                logger()->info('$ie', [$ae->getCode(), $ae->getMessages()]);

            } catch (Exception $e) {
                $errors .= $row_num . ' : ' . $e->getMessage() . '<br>';
                logger()->error('$e', [$e->getCode(), $e->getMessage()]);
            }
        }

        if ($errors) {
            return back()
                ->with('alert.error', $errors);
        }

        return redirect()->route('admin.products.index')
            ->with('alert.success','CSV uploaded successfully.');
    }

    private function validation(array $data, int $unit_cost = null)
    {
        $product_request = new ProductRequest();
        $rules = $product_request->rules(unit_cost: $unit_cost);
        $validator = Validator::make($data, $rules, [], []);

        if ($validator->fails()) {
            throw new ArrayException($validator->messages()->all());
        }
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

            // Add UTF-8 BOM for proper rendering in some applications (e.g., Excel)
            fprintf($csv, chr(0xEF).chr(0xBB).chr(0xBF));

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
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $file_name . '"',
        ]);
    }
}
