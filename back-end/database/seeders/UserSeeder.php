<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');
        $roles = Role::query()->pluck('id', 'name');

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@jobapp.test'],
            [
                'name' => 'Job App Admin',
                'password' => $password,
                'is_active' => true,
            ],
        );
        $admin->forceFill(['email_verified_at' => now()])->save();
        $admin->roles()->sync([$roles['admin']]);

        for ($index = 1; $index <= 20; $index++) {
            $employer = User::query()->updateOrCreate(
                ['email' => sprintf('employer%02d@jobapp.test', $index)],
                [
                    'name' => sprintf('Employer %02d', $index),
                    'password' => $password,
                    'phone' => sprintf('+359 88 800 %04d', $index),
                    'is_active' => true,
                ],
            );
            $employer->forceFill(['email_verified_at' => now()])->save();
            $employer->roles()->sync([$roles['employer']]);
        }

        for ($index = 1; $index <= 10; $index++) {
            $candidate = User::query()->updateOrCreate(
                ['email' => sprintf('candidate%02d@jobapp.test', $index)],
                [
                    'name' => sprintf('Candidate %02d', $index),
                    'password' => $password,
                    'bio' => 'Motivated professional open to new career opportunities.',
                    'is_active' => true,
                ],
            );
            $candidate->forceFill(['email_verified_at' => now()])->save();
            $candidate->roles()->sync([$roles['candidate']]);
        }
    }
}
