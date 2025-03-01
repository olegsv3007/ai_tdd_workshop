<?php

namespace App\DDD\Shared\Domain\Service;

interface UuidGeneratorInterface
{
    public function generate(): string;
}
