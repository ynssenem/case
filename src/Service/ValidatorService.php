<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidatorService
{
    protected $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($value): ?ConstraintViolationListInterface
    {
        $errors = $this->validator->validate($value);

        if ($errors->count() > 0) {
            return $errors;
        }

        return null;
    }
}