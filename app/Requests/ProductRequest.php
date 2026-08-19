<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user() && auth()->user()->role === 'owner';
    }

    public function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'sku' => 'required|unique:products,sku,' . ($this->product->id ?? null),
            'barcode' => 'nullable|unique:products,barcode,' . ($this->product->id ?? null),
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'cost_price' => 'required|numeric|min:0',
            'consumer_price' => 'required|numeric|min:0',
            'applicator_price' => 'required|numeric|min:0',
            'buyer_price' => 'required|numeric|min:0',
            'calculation_type' => 'required|in:quantity,meter,sheet_meter',
            'minimum_stock' => 'required|numeric|min:0',
        ];
    }
}
