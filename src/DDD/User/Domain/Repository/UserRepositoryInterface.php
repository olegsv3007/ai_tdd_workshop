<?php

namespace App\DDD\User\Domain\Repository;

use App\DDD\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function findByToken(string $token): ?User;

    public function store(User $user): void;

    public function update(User $user): void;
}
