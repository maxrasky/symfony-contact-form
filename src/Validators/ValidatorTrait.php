<?php

namespace App\Validators;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Trait ValidatorTrait
 * @package App\Validators
 */
trait ValidatorTrait
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    protected function getViolationMessages(ConstraintViolationListInterface $violations)
    {
        $result = [];

        foreach ($violations as $violation) {
            $result[] = $violation->getMessage();
        }

        return $result;
    }
}
