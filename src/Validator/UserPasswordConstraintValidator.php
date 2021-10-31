<?php


namespace App\Validator;


use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UserPasswordConstraintValidator extends ConstraintValidator
{
    private $tokenStorage;
    private $passwordHasher;

    public function __construct(TokenStorageInterface $tokenStorage, UserPasswordHasherInterface $passwordHasher)
    {
        $this->tokenStorage = $tokenStorage;
        $this->passwordHasher = $passwordHasher;
    }

    public function validate($password, Constraint $constraint)
    {
        if (!$constraint instanceof UserPasswordConstraint) {
            throw new UnexpectedTypeException($constraint, UserPasswordConstraint::class);
        }

        if (null === $password || '' === $password) {
            $this->context->addViolation($constraint->message);

            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user instanceof UserInterface) {
            throw new ConstraintDefinitionException('The User object must implement the UserInterface interface.');
        }

        if (null === $user->getPassword() || !$this->passwordHasher->isPasswordValid($user, $password)) {
            $this->context->addViolation($constraint->message);
        }
    }
}