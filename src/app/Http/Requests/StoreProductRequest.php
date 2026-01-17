<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product_image' => ['required', 'mimes:jpeg,png'],
            'name' => ['required', 'string'],
            'brand' => ['nullable', 'string', 'max:50'],
            'description' => ['required','max:255'],
            'condition' => ['required', 'in:new,used,damaged,junk'],
            'price' => ['required', 'integer', 'min:0'],
            'categories' => ['required', 'array'],
            'categories.*' => ['required', 'integer'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'product_image.required' => '商品画像をアップロードしてください',
            'product_image.mimes' => '商品画像はjpegまたはpngの形式でアップロードしてください',
            'name.required' => '商品名を入力してください',
            'brand.max' => 'ブランド名は50文字以内で入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',
            'condition.required' => '商品の状態を選択してください',
            'condition.in' => '商品の状態を正しく選択してください',
            'price.required' => '商品価格を入力してください',
            'price.integer' => '商品価格は数字で入力してください',
            'price.min' => '商品価格は0円以上で入力してください',
            'categories.required' => 'カテゴリーを選択してください',
        ];
    }
}
