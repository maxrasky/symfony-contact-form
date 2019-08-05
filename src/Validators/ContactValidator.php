<?php

namespace App\Validators;

use App\Entity\Contact;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use UnexpectedValueException;

/**
 * Class ContactValidator
 * @package App\Validators
 */
class ContactValidator
{
    use ValidatorTrait;

    const EMAIL_LENGTH = 255;

    const MESSAGE_LENGTH = 1000;

    public function validate($value)
    {
        if (!$value instanceof Contact) {
            throw new UnexpectedValueException($value);
        }

        $errors = $this->validateEmail($value->getEmail());
        if (count($errors)) {
            return $this->getViolationMessages($errors);
        }

        $errors = $this->validateMessage($value->getMessage());
        if (count($errors)) {
            return $this->getViolationMessages($errors);
        }

        return [];
    }

    private function validateEmail($email)
    {
        return $this->validator->validate($email, [
            new NotBlank(),
            new Email(),
            new LessThanOrEqual(static::EMAIL_LENGTH),
        ]);
    }

    private function validateMessage($message)
    {
        return $this->validator->validate($message, [
            new NotBlank(),
            new LessThanOrEqual(static::MESSAGE_LENGTH),
        ]);
    }
}
