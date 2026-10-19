<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:companies,slug',
            'logo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'industry' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
        ];
    }
}