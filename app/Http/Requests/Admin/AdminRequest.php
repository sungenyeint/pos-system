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
                        return $fail('Eメールアドレスと一致しないようにしてください。');
                    }
                },
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '氏名',
            'email' => 'Eメールアドレス',
            'password' => 'パスワード',
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => '大文字・小文字を含めた半角英数字記号を、6文字以上60文字以内で入力してください。'
        ];
    }
}
