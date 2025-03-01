<?php

namespace App\DDD\Shared\Infrastructure\Service;

use App\DDD\Shared\Domain\Service\UserPasswordHasherInterface as AppUserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserPasswordHasher implements AppUserPasswordHasherInterface, UserPasswordHasherInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function hashPassword(
        PasswordAuthenticatedUserInterface $user,
        #[\SensitiveParameter]
        string $plainPassword,
    ): string {
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }

    public function isPasswordValid(
        PasswordAuthenticatedUserInterface $user,
        #[\SensitiveParameter]
        string $plainPassword,
    ): bool {
        return $this->passwordHasher->isPasswordValid($user, $plainPassword);
    }

    public function needsRehash(PasswordAuthenticatedUserInterface $user): bool
    {
        return $this->passwordHasher->needsRehash($user);
    }
}
