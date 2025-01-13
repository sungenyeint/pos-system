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
    private $import_id;
    private $row_num = 2;

    public function __construct($import)
    {
        $this->import_id = $import->id;
    }

    public function import($file_name)
    {
        $file = new \SplFileObject(storage_path('app/' . $file_name));

        $file->setFlags(
            \SplFileObject::READ_CSV |
            \SplFileObject::READ_AHEAD |
            \SplFileObject::SKIP_EMPTY
        );

        foreach ($file as $i => $line) {
            $this->row_num = $i + 1;

            // ヘッダー行スキップ
            if ($i < 1) {
                continue;
            }

            logger()->info('Line info', [
                'row_num' => $this->row_num,
                'import_id' => $this->import_id,
            ]);

            try {
                $category = Category::where('name', $line[0])->first();

                if ($category === null) {
                    throw new ArrayException(['代理店データが存在しません。']);
                }

                // インポートデータをセット
                $import_data = [
                    'category_id' => $category->id,
                    'name' => $line[1],
                    'unit_cost' => $line[2],
                    'unit_price' => $line[3],
                    'stock_quantity' => $line[4],
                ];

                logger()->info('$import_data', $import_data);

                // バリデーション
                $this->validation($import_data);

                // データ保存
                $this->storeImportDetail(true);
                $product = Product::create($import_data);
                logger()->info('Create $product', $product->toArray());

            } catch (ArrayException $ae) {
                logger()->info('$ae', [$ae->getCode(), $ae->getMessages()]);
                $this->storeImportDetail(false, $ae->getMessages());

            } catch (Exception $e) {
                logger()->error('$e', [$e->getCode(), $e->getMessage()]);
                $this->storeImportDetail(false, [$e->getMessage()]);
            }
        }
    }

    private function storeImportDetail(bool $result, array $messages = null)
    {
        return ImportDetail::create([
            'import_id' => $this->import_id,
            'line_number' => $this->row_num,
            'result' => $result,
            'messages' => $messages,
        ]);
    }

    private function validation(array $data)
    {
        $product_request = new ProductRequest();
        // $rules = $product_request->rules($product_id);
        $validator = Validator::make($data, [], [], $product_request->attributes());

        if ($validator->fails()) {
            throw new Exception(json_encode($validator->messages()->all()));
        }
    }
}
