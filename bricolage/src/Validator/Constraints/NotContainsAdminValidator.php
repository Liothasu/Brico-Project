<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotContainsAdminValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (stripos($value, 'admin') !== false) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ username }}', $value)
                ->addViolation();
        }
    }
}