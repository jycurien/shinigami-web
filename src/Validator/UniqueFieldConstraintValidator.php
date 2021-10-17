<?php


namespace App\Validator;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueFieldConstraintValidator extends ConstraintValidator
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueFieldConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueFieldConstraint::class);
        }

        if (!class_exists($constraint->entityRepository)) {
            throw new InvalidArgumentException(sprintf('No class %s found for $entityRepository', $constraint->entityRepository));
        }

        $repository = new ($constraint->entityRepository)($this->registry);
        if(!$repository instanceof EntityRepository) {
            throw new InvalidArgumentException(sprintf('You must provide an argument $entityRepository of type %s to %s constraint', EntityRepository::class, get_class($constraint)));
        }

        if (null !== $repository->findOneBy([$this->context->getPropertyName() => $value])) {
            $this->context->addViolation($constraint->message);
        }
    }
}