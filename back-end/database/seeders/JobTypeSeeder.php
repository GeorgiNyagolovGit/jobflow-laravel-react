<?php

namespace Database\Seeders;

use App\Models\JobType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobTypeSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Full-time', 'Part-time', 'Contract', 'Internship', 'Temporary', 'Freelance'] as $name) {
            JobType::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }
}
