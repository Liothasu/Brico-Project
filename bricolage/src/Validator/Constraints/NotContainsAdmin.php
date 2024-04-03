<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotContainsAdmin extends Constraint
{
    public $message = 'This username cannot be used.';
}