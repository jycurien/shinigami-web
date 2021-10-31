<?php


namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UserPasswordConstraint extends Constraint
{
    public $message = 'This value should be the user\'s current password.';

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}