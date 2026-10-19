<?php

namespace App\Repositories;

use App\Models\Job;
use App\Repositories\Contracts\JobRepositoryInterface;

class JobRepository implements JobRepositoryInterface
{
    public function getAll($perPage = 10)
    {
        return Job::with([
            'company',
            'category',
            'jobType',
            'skills',
        ])
            ->latest()
            ->paginate($perPage);
    }

    public function findById($id)
    {
        return Job::with([
            'company',
            'category',
            'jobType',
            'skills',
        ])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Job::create($data);
    }

    public function update($id, array $data)
    {
        $job = Job::findOrFail($id);

        $job->update($data);

        return $job->fresh();
    }

    public function delete($id)
    {
        $job = Job::findOrFail($id);

        return $job->delete();
    }

    public function search(array $filters)
    {
        $query = Job::with([
            'company',
            'category',
            'jobType',
            'skills',
        ]);

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
            $query->where('salary_max', '>=', $filters['salary_min']);
        }

        if (isset($filters['salary_max'])) {
            $query->where('salary_min', '<=', $filters['salary_max']);
        }

        $sort = isset($filters['sort']) ? $filters['sort'] : 'latest';

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

        return $query->where('status', 'published')->paginate($perPage);
    }
}