<?php

namespace App\Http\Controllers\Admin;

use AppExceptions\ArrayException;
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
        } catch (Exception $e) {
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
        } catch (Exception $e) {
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
        } catch (Exception $e) {
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
        // Validate upload request
        $uploadRequest = new UploadRequest();
        $validator = Validator::make(
            $request->all(),
            $uploadRequest->rules(),
            [],
            $uploadRequest->attributes()
        );

        if ($validator->fails()) {
            return back()->with('alert.error', 'CSV upload failed - Invalid file');
        }

        try {
            // Get and setup CSV file
            $importFile = $request->file('import_file');
            $file = new \SplFileObject($importFile);
            $file->setFlags(
                \SplFileObject::READ_CSV |
                \SplFileObject::READ_AHEAD |
                \SplFileObject::SKIP_EMPTY |
                \SplFileObject::DROP_NEW_LINE
            );

            $errors = [];
            $success_count = 0;
            // Process CSV rows
            foreach ($file as $index => $line) {
                $rowNum = $index + 1;

                // Skip header row
                if ($index === 0) {
                    continue;
                }

                try {
                    // Validate row data
                    if (count($line) < 5) {
                        throw new Exception('Invalid number of columns');
                    }

                    // Find category
                    $category = Category::where('name', trim($line[0]))->first();
                    if (!$category) {
                        throw new Exception("Category '{$line[0]}' not found");
                    }

                    // Prepare import data
                    $importData = [
                        'category_id' => $category->id,
                        'name' => trim($line[1]),
                        'unit_cost' => (float)$line[2],
                        'unit_price' => (float)$line[3],
                        'stock_quantity' => (int)$line[4],
                    ];

                    // Validate product data
                    $this->validation($importData, $importData['unit_cost']);

                    // Create product
                    Product::create($importData);

                    $success_count += 1;

                } catch (ArrayException $ae) {
                    $errors[] = "Row {$rowNum}: " . implode(', ', $ae->getMessages());
                    logger()->warning('CSV import validation error', [
                        'row' => $rowNum,
                        'errors' => $ae->getMessages()
                    ]);
                } catch (Exception $e) {
                    $errors[] = "Row {$rowNum}: {$e->getMessage()}";
                    logger()->error('CSV import error', [
                        'row' => $rowNum,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            if (!empty($errors) && $success_count > 0) {
                return back()
                    ->with('alert.success', $success_count . ' product created successfully!')
                    ->with('alert.error', implode('<br>', $errors));
            } else {
                return back()->with('alert.error', implode('<br>', $errors));
            }

            return redirect()
                ->route('admin.products.index')
                ->with('alert.success', 'CSV imported successfully');

        } catch (Exception $e) {
            DB::rollBack();
            logger()->error('CSV import failed', ['error' => $e->getMessage()]);
            return back()->with('alert.error', 'CSV import failed - ' . $e->getMessage());
        }
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
     * Export products to CSV file
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export()
    {
        $file_name = 'product_' . now()->format('Ymd_His') . '.csv';

        $callback = function() {
            try {
                $csv = fopen('php://output', 'w');
                if ($csv === false) {
                    throw new Exception('Failed to open output stream');
                }

                // Add UTF-8 BOM for proper rendering in some applications (e.g., Excel)
                fprintf($csv, chr(0xEF).chr(0xBB).chr(0xBF));

                // Write CSV header
                $headers = [
                    'category_name',
                    'product_name',
                    'unit_cost',
                    'unit_price',
                    'stock_quantity',
                    'create_date'
                ];
                if (fputcsv($csv, $headers) === false) {
                    throw new Exception('Failed to write CSV headers');
                }

                // Query and write product data
                Product::with('category')
                    ->orderBy('stock_quantity')
                    ->chunk(1000, function($products) use ($csv) {
                        foreach ($products->groupBy('category.name') as $category_name => $categoryProducts) {
                            foreach ($categoryProducts as $product) {
                                $row = [
                                    $category_name,
                                    $product->name,
                                    $product->unit_cost,
                                    $product->unit_price,
                                    $product->stock_quantity,
                                    $product->created_at->format('Y-m-d H:i:s'),
                                ];
                                if (fputcsv($csv, $row) === false) {
                                    throw new Exception('Failed to write product data');
                                }
                            }
                        }
                    });

            } catch (Exception $e) {
                logger()->error('CSV export failed', [
                    'error' => $e->getMessage()
                ]);
                throw $e;
            } finally {
                if (isset($csv) && is_resource($csv)) {
                    fclose($csv);
                }
            }
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $file_name . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
