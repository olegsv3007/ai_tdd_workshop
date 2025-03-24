<?php

namespace App\Tests\Validation;

use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Abstract test case for validation tests
 * Provides helper methods to assert field validation errors
 */
abstract class ValidationTestCase extends FunctionalTestCase
{
    protected ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = self::getService(ValidatorInterface::class);
    }

    /**
     * Assert that a field has a validation error with the given message
     *
     * @param ConstraintViolationListInterface $errors The validation errors
     * @param string $fieldName The field name to check
     * @param string $expectedMessage The expected error message
     */
    protected static function assertFieldInvalid(
        ConstraintViolationListInterface $errors,
        string $fieldName,
        string $expectedMessage
    ): void {
        $fieldErrors = self::getErrorsForField($errors, $fieldName);
        
        self::assertNotEmpty(
            $fieldErrors,
            sprintf('Expected field "%s" to have validation errors, but none found', $fieldName)
        );
        
        $errorMessages = array_map(
            fn (ConstraintViolationInterface $error) => $error->getMessage(),
            $fieldErrors
        );
        
        self::assertContains(
            $expectedMessage,
            $errorMessages,
            sprintf(
                'Expected validation error message "%s" for field "%s", got: %s',
                $expectedMessage,
                $fieldName,
                implode(', ', $errorMessages)
            )
        );
    }
    
    /**
     * Assert that a field has no validation errors
     *
     * @param ConstraintViolationListInterface $errors The validation errors
     * @param string $fieldName The field name to check
     */
    protected static function assertFieldValid(
        ConstraintViolationListInterface $errors,
        string $fieldName
    ): void {
        $fieldErrors = self::getErrorsForField($errors, $fieldName);
        
        self::assertEmpty(
            $fieldErrors,
            sprintf(
                'Expected field "%s" to be valid, but found errors: %s',
                $fieldName,
                implode(
                    ', ',
                    array_map(
                        fn (ConstraintViolationInterface $error) => $error->getMessage(),
                        $fieldErrors
                    )
                )
            )
        );
    }
    
    /**
     * Get all errors for a specific field
     *
     * @param ConstraintViolationListInterface $errors The validation errors
     * @param string $fieldName The field name to filter by
     * @return array<ConstraintViolationInterface> The errors for the field
     */
    private static function getErrorsForField(
        ConstraintViolationListInterface $errors,
        string $fieldName
    ): array {
        $fieldErrors = [];
        
        /** @var ConstraintViolationInterface $error */
        foreach ($errors as $error) {
            if ($error->getPropertyPath() === $fieldName) {
                $fieldErrors[] = $error;
            }
        }
        
        return $fieldErrors;
    }
    
    /**
     * Assert that there are no validation errors at all
     *
     * @param ConstraintViolationListInterface $errors The validation errors
     */
    protected static function assertNoValidationErrors(
        ConstraintViolationListInterface $errors
    ): void {
        $errorCount = count($errors);
        
        self::assertEquals(
            0,
            $errorCount,
            sprintf(
                'Expected no validation errors, but found %d: %s',
                $errorCount,
                implode(
                    ', ',
                    array_map(
                        fn (ConstraintViolationInterface $error) => sprintf(
                            '"%s": %s',
                            $error->getPropertyPath(),
                            $error->getMessage()
                        ),
                        iterator_to_array($errors)
                    )
                )
            )
        );
    }
}
