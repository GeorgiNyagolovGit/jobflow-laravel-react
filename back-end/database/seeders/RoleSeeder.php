<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Platform administrator'],
            ['name' => 'employer', 'description' => 'Company representative who publishes jobs'],
            ['name' => 'candidate', 'description' => 'Job seeker who applies for jobs'],
        ];

        foreach ($roles as $role) {
            Role::query()->updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
