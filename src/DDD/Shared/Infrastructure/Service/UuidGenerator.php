<?php

namespace App\DDD\Shared\Infrastructure\Service;

use App\DDD\Shared\Domain\Service\UuidGeneratorInterface;
use Ramsey\Uuid\Uuid;

class UuidGenerator implements UuidGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
