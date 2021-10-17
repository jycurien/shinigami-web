<?php


namespace App\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueFieldConstraint extends Constraint
{
    public $entityRepository;
    public $message = 'not.unique.field';

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}