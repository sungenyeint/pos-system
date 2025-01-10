<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;
use App\Models\Product;

class PurchaseRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $product = Product::find($value);
                    if ($product === null) {
                        return $fail('選択された' . $attribute . 'は正しくありません。');
                    }
                },
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'total_cost' => [
                'required',
                'decimal:0,2',
                'max:9999999999.99',
            ],
            'purchase_date' => [
                'required',
                'date_format:Y-m-d H:i',
            ]
        ];
    }
}
