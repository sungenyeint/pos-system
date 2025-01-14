<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;
use App\Models\Category;
use Illuminate\Validation\Rule;

class ProductRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(string $product_id = null): array
    {
        if ($this->has('product_id')) {
            $product_id = $this->product_id;
        }
        return [
            'category_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $category = Category::find($value);
                    if (is_null($category)) {
                        return $fail('選択された' . $attribute . 'は正しくありません。');
                    }
                },
            ],
            'name' => [
                'required',
                'max:' . config('const.default_text_maxlength'),
                Rule::unique('products', 'name')->ignore($product_id),
            ],
            'unit_cost' => [
                'required',
                'min:1',
                // 'decimal:0,2',
                // 'max:9999999999',
            ],
            'unit_price' => [
                'required',
                'min:1',
                // 'decimal:0,2',
                // 'max:9999999999',
            ],
            'stock_quantity' => [
                'required',
                'integer',
                // 'min:1',
            ]
        ];
    }
}
