<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function getAll($perPage = 10)
    {
        return User::latest()->paginate($perPage);
    }

    public function findById($id)
    {
        return User::findOrFail($id);
    }

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);

        $user->update($data);

        return $user->fresh();
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        return $user->delete();
    }
}