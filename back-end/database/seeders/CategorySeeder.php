<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Software Development' => 'Web, mobile, backend and systems engineering roles.',
            'Data & Analytics' => 'Data science, analytics and business intelligence roles.',
            'DevOps & Cloud' => 'Cloud infrastructure, platform engineering and reliability roles.',
            'Cybersecurity' => 'Information security, risk and compliance roles.',
            'Design & Creative' => 'Product design, visual design and creative roles.',
            'Product & Project Management' => 'Product, project and programme leadership roles.',
            'Marketing & Communications' => 'Digital marketing, content and communications roles.',
            'Sales & Business Development' => 'Sales, partnerships and account management roles.',
            'Customer Support' => 'Customer care, success and technical support roles.',
            'Finance & Accounting' => 'Finance, audit, banking and accounting roles.',
            'Human Resources' => 'Recruitment, people operations and learning roles.',
            'Engineering & Manufacturing' => 'Industrial, mechanical and production roles.',
            'Healthcare' => 'Medical, pharmaceutical and healthcare operations roles.',
            'Education & Training' => 'Teaching, instructional design and training roles.',
            'Administration & Operations' => 'Office administration, logistics and operations roles.',
        ];

        foreach ($categories as $name => $description) {
            Category::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => $description],
            );
        }
    }
}
