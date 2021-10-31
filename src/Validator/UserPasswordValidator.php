<?php


namespace App\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UserPasswordValidator extends ConstraintValidator
{

    /**
     * @inheritDoc
     */
    public function validate($password, Constraint $constraint)
    {
        if (!$constraint instanceof UserPasswordConstraint) {
            throw new UnexpectedTypeException($constraint, UserPasswordConstraint::class);
        }

        // TODO
    }
}