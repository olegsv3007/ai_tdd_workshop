<?php

namespace App\DDD\User\Domain\Command;

class UserRegistrationCommand
{
    public readonly string $email;

    public readonly string $password;

    public function __construct(
        string $email,
        string $password,
    ) {
        $this->email = $email;
        $this->password = $password;
    }
}
