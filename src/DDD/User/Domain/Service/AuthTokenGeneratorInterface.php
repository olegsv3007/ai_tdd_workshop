<?php

namespace App\DDD\User\Domain\Service;

interface AuthTokenGeneratorInterface
{
    public function generate(): string;
}
