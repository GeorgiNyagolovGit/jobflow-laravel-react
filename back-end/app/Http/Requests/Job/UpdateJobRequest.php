<?php

namespace App\Http\Requests\Job;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        $job = $this->route('job');
        $jobId = is_object($job) ? $job->id : $job;

        return [
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'job_type_id' => 'sometimes|required|integer|exists:job_types,id',
            'title' => 'sometimes|required|string|max:255',
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('jobs', 'slug')->ignore($jobId),
            ],
            'description' => 'sometimes|required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'is_remote' => 'sometimes|boolean',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|gte:salary_min',
            'salary_currency' => 'sometimes|string|size:3',
            'expires_at' => 'nullable|date|after:today',
            'skills' => 'sometimes|array',
            'skills.*' => 'integer|distinct|exists:skills,id',
        ];
    }
}