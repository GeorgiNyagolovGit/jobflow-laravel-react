<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            'PHP', 'Laravel', 'JavaScript', 'TypeScript', 'React', 'Vue.js',
            'Node.js', 'Python', 'Java', 'C#', '.NET', 'SQL', 'PostgreSQL',
            'MySQL', 'MongoDB', 'Redis', 'Docker', 'Kubernetes', 'AWS', 'Azure',
            'Linux', 'Git', 'CI/CD', 'Terraform', 'Cybersecurity', 'Power BI',
            'Tableau', 'Excel', 'Machine Learning', 'Data Analysis', 'Figma',
            'UI/UX Design', 'Adobe Creative Suite', 'Product Management',
            'Project Management', 'Agile', 'Scrum', 'SEO', 'Google Ads',
            'Content Marketing', 'Social Media', 'Sales', 'CRM', 'Negotiation',
            'Customer Service', 'Technical Support', 'Accounting', 'Financial Analysis',
            'Recruitment', 'HR Administration', 'AutoCAD', 'Quality Assurance',
            'Supply Chain', 'Teaching', 'Training', 'English', 'German',
            'Communication', 'Leadership', 'Problem Solving',
        ];

        foreach ($skills as $name) {
            Skill::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }
}
