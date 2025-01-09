<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;

class CategoryRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:' . config('const.default_text_maxlength'),
            ]
        ];
    }
}
