<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            ['NovaStack Labs', 'Software', '51-200', 'Sofia'],
            ['Black Sea Digital', 'Information Technology', '11-50', 'Varna'],
            ['Thracian Analytics', 'Data & Analytics', '51-200', 'Plovdiv'],
            ['Balkan Cloud Systems', 'Cloud Services', '201-500', 'Sofia'],
            ['SecureWave Europe', 'Cybersecurity', '51-200', 'Sofia'],
            ['PixelCraft Studio', 'Design', '11-50', 'Plovdiv'],
            ['BrightPath Products', 'SaaS', '51-200', 'Sofia'],
            ['Green Horizon Energy', 'Renewable Energy', '201-500', 'Burgas'],
            ['Danube Commerce', 'E-commerce', '51-200', 'Ruse'],
            ['ConnectCare Services', 'Customer Experience', '201-500', 'Varna'],
            ['Vitosha Finance', 'Financial Services', '201-500', 'Sofia'],
            ['PeopleFirst Bulgaria', 'Human Resources', '11-50', 'Sofia'],
            ['PrecisionWorks', 'Manufacturing', '501-1000', 'Stara Zagora'],
            ['MedicaNova', 'Healthcare', '201-500', 'Pleven'],
            ['LearnSphere Academy', 'Education', '51-200', 'Sofia'],
            ['RapidRoute Logistics', 'Logistics', '201-500', 'Plovdiv'],
            ['CodeBridge Solutions', 'Software Consulting', '51-200', 'Sofia'],
            ['BluePeak Media', 'Marketing', '11-50', 'Varna'],
            ['UrbanNest Group', 'Real Estate', '51-200', 'Sofia'],
            ['FutureMobility', 'Automotive', '201-500', 'Sofia'],
        ];

        foreach ($companies as $index => [$name, $industry, $size, $location]) {
            $owner = User::query()
                ->where('email', sprintf('employer%02d@jobapp.test', $index + 1))
                ->firstOrFail();

            Company::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'user_id' => $owner->id,
                    'name' => $name,
                    'description' => sprintf(
                        '%s is a growing Bulgarian employer focused on quality, innovation and long-term professional development.',
                        $name,
                    ),
                    'website' => 'https://'.Str::slug($name).'.example.com',
                    'industry' => $industry,
                    'size' => $size,
                    'location' => $location,
                    'status' => 'approved',
                ],
            );
        }
    }
}
