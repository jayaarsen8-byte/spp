<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user() && auth()->user()->role === 'owner';
    }

    public function rules()
    {
        return [
            'name' => 'required|unique:units,name,' . ($this->unit->id ?? null),
            'abbreviation' => 'required|unique:units,abbreviation,' . ($this->unit->id ?? null),
            'description' => 'nullable|string',
        ];
    }
}
