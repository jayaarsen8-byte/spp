<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user() && in_array(auth()->user()->role, ['owner', 'admin']);
    }

    public function rules()
    {
        return [
            'customer_id' => 'nullable|exists:customers,id',
            'price_type' => 'required|in:consumer,applicator,buyer',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.length' => 'nullable|numeric|min:0',
            'items.*.selling_unit_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,qris,debit,credit_card,e_wallet',
            'payment_amount' => 'required|numeric|min:0',
        ];
    }
}
