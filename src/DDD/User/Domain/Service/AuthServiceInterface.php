<?php

namespace App\DDD\User\Domain\Service;

use App\DDD\User\Domain\Entity\User;

interface AuthServiceInterface
{
    public function createUserToken(User $user): string;

    public function verifyUserCredentials(User $user, string $password): bool;
}
