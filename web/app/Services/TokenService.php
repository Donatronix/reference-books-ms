<?php

namespace App\Services;

use App\Models\Token;

class TokenService extends BaseService
{
    public function __construct()
    {
        $this->model = Token::class;
    }
}

