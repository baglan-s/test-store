<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private UserRepository $repository
    ) {}

    
}