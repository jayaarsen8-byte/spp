<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user() && auth()->user()->role === 'owner';
    }

    public function rules()
    {
        return [
            'name' => 'required|unique:categories,name,' . ($this->category->id ?? null),
            'description' => 'nullable|string',
        ];
    }
}
