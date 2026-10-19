<?php

namespace App\Repositories\Contracts;

interface CompanyRepositoryInterface
{
    public function getAll($perPage = 10);

    public function findById($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function getJobs($companyId, $perPage = 10);
}