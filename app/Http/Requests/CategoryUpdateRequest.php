<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'CATEGORY_NAME' => ['required', 'string', 'max:100'],
            'CATEGORY_DESC' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'CATEGORY_NAME.required' => 'กรุณาระบุชื่อหมวดหมู่ MOC', // ปรับแต่งข้อความ
        ];
    }
}
