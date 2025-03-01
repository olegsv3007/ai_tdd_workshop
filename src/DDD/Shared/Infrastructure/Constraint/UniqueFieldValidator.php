<?php

namespace App\DDD\Shared\Infrastructure\Constraint;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueFieldValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PropertyAccessorInterface $propertyAccessor,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint): void
    {
        assert($constraint instanceof UniqueField);

        $repository = $this->entityManager->getRepository($constraint->entityClass);
        $currentValue = $this->propertyAccessor->getValue($value, $constraint->formValuePath);

        $existingEntity = $repository->findOneBy([$constraint->entityField => $currentValue]);

        if (!$existingEntity) {
            return;
        }

        if (isset($constraint->idFieldName)) {
            $idOfUpdatedEntity = $this->propertyAccessor->getValue($value, $constraint->idFieldName);
            $updatedEntity = $repository->find($idOfUpdatedEntity);

            if ($existingEntity->id === $updatedEntity?->id) {
                return;
            }
        }

        $this->context->buildViolation($constraint->message)
            ->atPath($constraint->formValuePath)
            ->setParameter('{{ field }}', $constraint->fieldName ?? $constraint->formValuePath)
            ->setInvalidValue($this->propertyAccessor->getValue($value, $constraint->formValuePath))
            ->addViolation();
    }
}
