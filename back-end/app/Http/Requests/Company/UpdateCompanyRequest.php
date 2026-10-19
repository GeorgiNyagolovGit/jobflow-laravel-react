<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        $company = $this->route('company');
        $companyId = is_object($company) ? $company->id : $company;

        return [
            'name' => 'sometimes|required|string|max:255',
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('companies', 'slug')->ignore($companyId),
            ],
            'logo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'industry' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
        ];
    }
}