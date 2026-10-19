<?php

namespace App\Services;

use App\Models\Job;

class SearchService
{
    public function search(array $filters)
    {
        $query = Job::with([
            'company',
            'category',
            'jobType',
            'skills',
        ])->where('status', 'published');

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['job_type_id'])) {
            $query->where('job_type_id', $filters['job_type_id']);
        }

        if (!empty($filters['location'])) {
            $query->where(
                'location',
                'like',
                '%' . $filters['location'] . '%'
            );
        }

        if (isset($filters['is_remote'])) {
            $query->where('is_remote', $filters['is_remote']);
        }

        if (isset($filters['salary_min'])) {
            $query->where('salary_min', '>=', $filters['salary_min']);
        }

        if (isset($filters['salary_max'])) {
            $query->where('salary_max', '<=', $filters['salary_max']);
        }

        $sort = isset($filters['sort'])
            ? $filters['sort']
            : 'latest';

        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;

            case 'salary_asc':
                $query->orderBy('salary_min', 'asc');
                break;

            case 'salary_desc':
                $query->orderBy('salary_max', 'desc');
                break;

            default:
                $query->latest();
                break;
        }

        $perPage = isset($filters['per_page'])
            ? (int) $filters['per_page']
            : 10;

        return $query->paginate($perPage);
    }
}