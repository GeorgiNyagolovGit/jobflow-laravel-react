<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobType;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['PHP Developer', 'software-development', ['php', 'laravel', 'mysql', 'git']],
            ['Frontend React Developer', 'software-development', ['javascript', 'typescript', 'react', 'git']],
            ['Backend Node.js Developer', 'software-development', ['nodejs', 'typescript', 'postgresql', 'redis']],
            ['Mobile Application Developer', 'software-development', ['javascript', 'typescript', 'react', 'git']],
            ['QA Automation Engineer', 'software-development', ['javascript', 'quality-assurance', 'git', 'cicd']],
            ['Data Analyst', 'data-analytics', ['sql', 'excel', 'power-bi', 'data-analysis']],
            ['Data Scientist', 'data-analytics', ['python', 'sql', 'machine-learning', 'data-analysis']],
            ['Business Intelligence Developer', 'data-analytics', ['sql', 'power-bi', 'tableau', 'data-analysis']],
            ['DevOps Engineer', 'devops-cloud', ['docker', 'kubernetes', 'aws', 'cicd']],
            ['Cloud Infrastructure Engineer', 'devops-cloud', ['azure', 'terraform', 'linux', 'docker']],
            ['Cybersecurity Analyst', 'cybersecurity', ['cybersecurity', 'linux', 'problem-solving', 'communication']],
            ['UI/UX Designer', 'design-creative', ['figma', 'uiux-design', 'communication', 'problem-solving']],
            ['Graphic Designer', 'design-creative', ['adobe-creative-suite', 'figma', 'communication']],
            ['Product Manager', 'product-project-management', ['product-management', 'agile', 'leadership', 'communication']],
            ['Project Manager', 'product-project-management', ['project-management', 'scrum', 'leadership', 'communication']],
            ['Digital Marketing Specialist', 'marketing-communications', ['google-ads', 'seo', 'content-marketing', 'social-media']],
            ['Content Marketing Manager', 'marketing-communications', ['content-marketing', 'seo', 'social-media', 'english']],
            ['Business Development Manager', 'sales-business-development', ['sales', 'crm', 'negotiation', 'communication']],
            ['Account Executive', 'sales-business-development', ['sales', 'crm', 'negotiation', 'english']],
            ['Customer Success Specialist', 'customer-support', ['customer-service', 'crm', 'communication', 'english']],
            ['Technical Support Engineer', 'customer-support', ['technical-support', 'linux', 'problem-solving', 'english']],
            ['Financial Analyst', 'finance-accounting', ['financial-analysis', 'excel', 'data-analysis', 'english']],
            ['Accountant', 'finance-accounting', ['accounting', 'excel', 'financial-analysis']],
            ['Talent Acquisition Specialist', 'human-resources', ['recruitment', 'hr-administration', 'communication', 'english']],
            ['Mechanical Design Engineer', 'engineering-manufacturing', ['autocad', 'quality-assurance', 'problem-solving']],
            ['Production Quality Engineer', 'engineering-manufacturing', ['quality-assurance', 'leadership', 'problem-solving']],
            ['Healthcare Operations Coordinator', 'healthcare', ['project-management', 'excel', 'communication']],
            ['Corporate Trainer', 'education-training', ['training', 'teaching', 'communication', 'english']],
            ['Logistics Coordinator', 'administration-operations', ['supply-chain', 'excel', 'communication', 'english']],
            ['Office Administrator', 'administration-operations', ['hr-administration', 'excel', 'communication', 'english']],
        ];

        $levels = [
            ['Junior', 1800, 2800],
            ['Mid-level', 2800, 4300],
            ['Senior', 4300, 6500],
            ['Lead', 6000, 8500],
        ];
        $locations = ['Sofia', 'Plovdiv', 'Varna', 'Burgas', 'Ruse', 'Stara Zagora', 'Pleven', 'Veliko Tarnovo'];
        $categories = Category::query()->pluck('id', 'slug');
        $companies = Company::query()
            ->whereHas('user', fn ($query) => $query->where('email', 'like', 'employer%@jobapp.test'))
            ->orderBy('id')
            ->get();
        $jobTypes = JobType::query()
            ->whereIn('slug', ['full-time', 'part-time', 'contract', 'internship', 'temporary', 'freelance'])
            ->orderBy('id')
            ->get();
        $skills = Skill::query()->pluck('id', 'slug');
        $sequence = 0;

        foreach ($positions as [$baseTitle, $categorySlug, $skillSlugs]) {
            foreach ($levels as [$level, $salaryMin, $salaryMax]) {
                $company = $companies[$sequence % $companies->count()];
                $jobType = $jobTypes[$sequence % $jobTypes->count()];
                $isRemote = $sequence % 3 === 0;
                $title = $level.' '.$baseTitle;
                $slug = Str::slug($title).'-'.str_pad((string) ($sequence + 1), 3, '0', STR_PAD_LEFT);
                $publishedAt = now()->subDays($sequence % 45)->subHours($sequence % 12);

                $job = Job::query()->updateOrCreate(
                    ['slug' => $slug],
                    [
                        'company_id' => $company->id,
                        'category_id' => $categories[$categorySlug],
                        'job_type_id' => $jobType->id,
                        'title' => $title,
                        'description' => sprintf(
                            '%s is looking for a %s to join its team in %s. You will work on meaningful projects in a collaborative environment with clear opportunities for professional growth.',
                            $company->name,
                            $title,
                            $isRemote ? 'a flexible remote setup' : $locations[$sequence % count($locations)],
                        ),
                        'requirements' => "Relevant professional or academic experience.\nStrong problem-solving and communication skills.\nWorking proficiency in English.\nAbility to collaborate effectively in a team.",
                        'responsibilities' => "Deliver high-quality work aligned with team goals.\nCollaborate with colleagues and stakeholders.\nPlan, document and communicate progress.\nContinuously improve processes and professional skills.",
                        'location' => $isRemote ? 'Remote / Bulgaria' : $locations[$sequence % count($locations)],
                        'is_remote' => $isRemote,
                        'salary_min' => $salaryMin,
                        'salary_max' => $salaryMax,
                        'salary_currency' => 'BGN',
                        'status' => 'published',
                        'published_at' => $publishedAt,
                        'expires_at' => $publishedAt->copy()->addDays(90),
                    ],
                );

                $job->skills()->sync(
                    collect($skillSlugs)
                        ->map(fn (string $skillSlug) => $skills[$skillSlug])
                        ->all(),
                );

                $sequence++;
            }
        }
    }
}
