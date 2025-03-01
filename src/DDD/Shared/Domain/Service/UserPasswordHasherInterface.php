<?php

namespace App\DDD\Shared\Domain\Service;

use App\DDD\User\Domain\Entity\User;

interface UserPasswordHasherInterface
{
    public function hashPassword(User $user, #[\SensitiveParameter] string $plainPassword): string;

    public function isPasswordValid(User $user, #[\SensitiveParameter] string $plainPassword): bool;

    public function needsRehash(User $user): bool;
}
