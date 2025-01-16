<?php

namespace App\Imports;

use App\Exceptions\ArrayException;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\ImportDetail;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Validator;

class ProductImport
{
    private $file;
    private $row_num = 2;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function import()
    {
        $file = new \SplFileObject(storage_path('app/' . $this->file));

        $file->setFlags(
            \SplFileObject::READ_CSV |
            \SplFileObject::READ_AHEAD |
            \SplFileObject::SKIP_EMPTY
        );

        foreach ($file as $i => $line) {
            $this->row_num = $i + 1;
            logger()->info('$line', $line);

            if ($i < 1) {
                continue;
            }

            logger()->info('Line info', [
                'row_num' => $this->row_num,
            ]);

            try {
                $category = Category::where('name', $line[0])->first();

                if ($category === null) {
                    throw new ArrayException(['代理店データが存在しません。']);
                }

                $product = Product::where('name', $line[0])->first();

                $import_data = [
                    'category_id' => $category->id,
                    'name' => $line[1],
                    'unit_cost' => $line[2],
                    'unit_price' => $line[3],
                    'stock_quantity' => $line[4],
                ];

                logger()->info('$import_data', $import_data);

                $this->validation($import_data, $product->id ?? null);

                $product = Product::create($import_data);
                logger()->info('Create $product', $product->toArray());

            } catch (ArrayException $ae) {
                logger()->info('$ae', [$ae->getCode(), $ae->getMessages()]);

            } catch (Exception $e) {
                logger()->error('$e', [$e->getCode(), $e->getMessage()]);
            }
        }
    }

    private function validation(array $data, string $product_id = null)
    {
        $product_request = new ProductRequest();
        $rules = $product_request->rules(product_id: $product_id);
        $validator = Validator::make($data, $rules, [], $product_request->attributes());

        if ($validator->fails()) {
            throw new Exception(json_encode($validator->messages()->all()));
        }
    }
}
