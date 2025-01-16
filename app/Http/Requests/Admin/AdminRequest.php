<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends BaseFormRequest
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
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('admins', 'email')->ignore($this->admin),
            ],
            'password' => [
                'regex:' . config('const.password_regex'),
                function ($attribute, $value, $fail) {
                    if ($this->request->get('email') == $value) {
                        return $fail('ကျေးဇူးပြု၍ သင့်အီးမေးလ်လိပ်စာနှင့် မကိုက်ညီပါ။');
                    }
                },
            ],
        ];
    }
}
