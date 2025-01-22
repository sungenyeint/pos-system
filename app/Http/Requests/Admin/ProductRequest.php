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
    public function rules(int $unit_cost = null): array
    {
        if ($this->has('unit_cost')) {
            $unit_cost = $this->unit_cost;
        }
        return [
            'category_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $category = Category::find($value);
                    if (is_null($category)) {
                        return $fail('The selected ' . $attribute . ' is invalid.');
                    }
                },
            ],
            'name' => [
                'required',
                'max:' . config('const.default_text_maxlength'),
                Rule::unique('products', 'name')->ignore($this->product),
            ],
            'unit_cost' => [
                'required',
                'numeric',
                'min:1',
            ],
            'unit_price' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) use ($unit_cost) {
                    if ($value <= $unit_cost) {
                        $fail("The $attribute must be greater than unit_cost.");
                    }
                },
            ],
            'stock_quantity' => [
                'required',
                'numeric',
                'min:0',
            ]
        ];
    }
}
