<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;      //---------------
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()           //---------------------
    {
        return [
            'CATEGORY_NAME' => ['required', 'string', 'max:100'],
            'CATEGORY_DESC' => ['nullable', 'string', 'max:255'],
            'FILE_NAME' => ['mimes:png,jpg', 'max:30720'], // 30MB = 30 * 1024 KB = 30720 KB
        ];
    }

    public function messages()
    {
        return [
            'CATEGORY_NAME.required' => 'กรุณาระบุชื่อหมวดหมู่ MOC', // ปรับแต่งข้อความ
        ];
    }
}
