<?php

namespace App\Repositories\Contracts;

interface JobRepositoryInterface
{
    public function getAll($perPage = 10);

    public function findById($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function search(array $filters);
}