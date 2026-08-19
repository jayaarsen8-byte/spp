<?php

namespace App\Http\Controllers;

use App\AppSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:owner');
    }

    public function index()
    {
        $settings = [
            'shop_name' => AppSetting::get('shop_name', 'Saudara Plafon PVC Meteseh'),
            'shop_address' => AppSetting::get('shop_address', ''),
            'shop_phone' => AppSetting::get('shop_phone', ''),
            'invoice_prefix' => AppSetting::get('invoice_prefix', 'INV-'),
            'default_payment_method' => AppSetting::get('default_payment_method', 'cash'),
            'minimum_stock_default' => AppSetting::get('minimum_stock_default', 10),
            'receipt_footer' => AppSetting::get('receipt_footer', ''),
        ];

        return view('settings.index', ['settings' => $settings]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'nullable|string',
            'shop_phone' => 'nullable|string|max:20',
            'invoice_prefix' => 'required|string|max:10',
            'minimum_stock_default' => 'required|numeric|min:0',
            'receipt_footer' => 'nullable|string',
        ]);

        foreach ($request->all() as $key => $value) {
            if ($key !== '_token') {
                AppSetting::set($key, $value);
            }
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}
