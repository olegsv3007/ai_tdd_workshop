<?php

namespace App\DDD\Shared\Infrastructure\Constraint;

use App\DDD\User\Domain\Command\UserLoginCommand;
use App\DDD\User\Domain\Repository\UserRepositoryInterface;
use App\DDD\User\Domain\Service\AuthServiceInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VerifyUserCredentialsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuthServiceInterface $authService,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint): void
    {
        assert($constraint instanceof VerifyUserCredentials);
        assert($value instanceof UserLoginCommand);

        if (!$value->email || !$value->password) {
            return;
        }

        $user = $this->userRepository->findByEmail($value->email);

        if ($user && $this->authService->verifyUserCredentials($user, $value->password)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->atPath('email')
            ->setInvalidValue($value->email)
            ->addViolation();

        $this->context->buildViolation($constraint->message)
            ->atPath('password')
            ->setInvalidValue($value->password)
            ->addViolation();
    }
}
