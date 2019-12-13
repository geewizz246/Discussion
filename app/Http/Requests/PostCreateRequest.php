<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostCreateRequest extends FormRequest
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
            // 'description' => ['required', 'max:240'],
            'post' => ['required'],
            'attachments.*' => ['file', 'mimes:txt,doc,docx,xls,xlsx,txt,pdf,jpg,jpeg,png,bmp,svg,gif,jfif', 'max:5120'],
        ];
    }
}
