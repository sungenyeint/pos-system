<?php

namespace App\Http\Requests\Admin;

class AdminStoreRequest extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        $rules['password'][] = 'required';

        return $rules;
    }
}
