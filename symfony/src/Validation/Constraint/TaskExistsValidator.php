<?php
namespace App\Validation\Constraint;

use App\Domain\Repository\TaskRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TaskExistsValidator extends ConstraintValidator
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof TaskExists) {
            throw new UnexpectedTypeException($constraint, TaskExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_int($value)) {
            return;
        }

        $task = $this->taskRepository->findById($value);

        if (null === $task) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
