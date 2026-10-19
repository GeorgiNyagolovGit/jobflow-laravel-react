<?php

namespace App\Http\Requests\Job;

use Illuminate\Foundation\Http\FormRequest;

class JobFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'search' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'job_type_id' => 'nullable|integer|exists:job_types,id',
            'location' => 'nullable|string|max:255',
            'is_remote' => 'nullable|boolean',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|gte:salary_min',
            'sort' => 'nullable|in:latest,oldest,salary_asc,salary_desc',
            'per_page' => 'nullable|integer|min:1|max:50',
        ];
    }
}