<?php

namespace App\DDD\Shared\Infrastructure\Constraint;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class VerifyUserCredentials extends Constraint
{
    public string $message = 'Invalid credentials.';

    public function validatedBy(): string
    {
        return VerifyUserCredentialsValidator::class;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
