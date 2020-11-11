<?php

namespace App\Service;

class SecurityService 
{
    

    public function passwordIsValid (string $password, array $user) 
    {
        if ($user['password'] === md5($password)) {
            return true;
        }
        return false;
    }
}