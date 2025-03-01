<?php

namespace App\DDD\Shared\Infrastructure\Constraint;

use Attribute;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class UniqueField extends Constraint
{
    public string $message = 'This {{ field }} is already used.';

    /**
     * @inheritDoc
     */
    #[HasNamedArguments]
    public function __construct(
        public string $entityClass,
        public string $entityField,
        public string $formValuePath,
        public ?string $idFieldName = null,
        public ?string $fieldName = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct([], $groups, $payload);
    }

    public function validatedBy(): string
    {
        return UniqueFieldValidator::class;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
