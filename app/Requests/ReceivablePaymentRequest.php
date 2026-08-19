<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceivablePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user() && in_array(auth()->user()->role, ['owner', 'admin']);
    }

    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:cash,transfer,qris,debit,credit_card,e_wallet',
            'note' => 'nullable|string',
        ];
    }
}
