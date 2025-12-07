<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'profile_image' => ['required', 'mimes:jpeg,png', 'max:2048'], // 必須・画像・2MBまで
            'name'          => ['required', 'string', 'max:20'],
            'postal_code'   => ['required', 'string', 'size:8', 'regex:/^\d{3}-\d{4}$/'],
            'address'       => ['required', 'string', 'max:255'],
            'building'      => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'profile_image.required' => 'プロフィール画像は必須です',
            'profile_image.mimes'    => 'プロフィール画像はjpegまたはpng形式でアップロードしてください',
            'profile_image.max'      => 'プロフィール画像は2MB以下にしてください',
            'name.required'          => 'ユーザー名を入力してください',
            'postal_code.required'   => '郵便番号を入力してください',
            'postal_code.size'       => '郵便番号は8文字で入力してください',
            'postal_code.regex'      => '郵便番号はハイフンありの形式で入力してください',
            'address.required'       => '住所を入力してください',
        ];
    }
}