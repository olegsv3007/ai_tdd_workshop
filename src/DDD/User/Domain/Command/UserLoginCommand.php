<?php

namespace App\DDD\User\Domain\Command;

class UserLoginCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}
