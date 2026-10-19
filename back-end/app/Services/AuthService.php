<?php

namespace App\Services;

use App\Actions\Auth\LoginUser;
use App\Actions\Auth\RegisterUser;
use App\Actions\Auth\LogoutUser;

class AuthService
{
    protected $loginUser;
    protected $registerUser;
    protected $logoutUser;

    public function __construct(
        LoginUser $loginUser,
        RegisterUser $registerUser,
        LogoutUser $logoutUser
    ) {
        $this->loginUser = $loginUser;
        $this->registerUser = $registerUser;
        $this->logoutUser = $logoutUser;
    }

    public function register(array $data)
    {
        return $this->registerUser->execute($data);
    }

    public function login(array $data)
    {
        return $this->loginUser->execute($data);
    }

    public function logout()
    {
        return $this->logoutUser->execute();
    }
}