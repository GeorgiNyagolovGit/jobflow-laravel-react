<?php

namespace App\Actions\Companies;

use App\Models\Company;

class CreateCompanyAction
{
    public function execute(array $data, $userId)
    {
        $data['user_id'] = $userId;
        $data['status'] = 'pending';

        return Company::create($data);
    }
}