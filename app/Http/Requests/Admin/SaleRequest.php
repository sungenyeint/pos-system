<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;
use App\Models\Product;

class SaleRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sale_date' => [
                'required',
                'date_format:Y-m-d H:i',
            ],
            'sales.*.product_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $product = Product::find($value);
                    if ($product === null) {
                        return $fail('選択された' . $attribute . 'は正しくありません。');
                    }
                },
            ],
            'sales.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'sales.*.total_price' => [
                'required',
                'integer',
                'min:1',
                // 'decimal:0,2',
                // 'max:9999999999.99',
            ],
        ];
    }
}
