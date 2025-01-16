<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;
use App\Models\Product;

class PurchaseStoreRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'purchase_date' => [
                'required',
                'date_format:Y-m-d H:i',
            ],
            'purchases.*.product_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $product = Product::find($value);
                    if ($product === null) {
                        return $fail('The selected ' . $attribute . ' is invalid.');
                    }
                },
            ],
            'purchases.*.quantity' => [
                'required',
                'numeric',
                'min:1',
            ],
            'purchases.*.total_cost' => [
                'required',
                'numeric',
                'min:1',
            ],
        ];
    }
}
