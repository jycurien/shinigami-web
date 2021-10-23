<?php


namespace App\Handler\User;


use App\Dto\UserRegistrationDto;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Service\User\VerifyEmailHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class RegistrationHandler
{
    /**
     * @var UserFactory
     */
    private $userFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var VerifyEmailHelper
     */
    private $verifyEmailHelper;
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(UserFactory $userFactory, EntityManagerInterface $entityManager, VerifyEmailHelper $verifyEmailHelper, MailerInterface $mailer)
    {
        $this->userFactory = $userFactory;
        $this->entityManager = $entityManager;
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->mailer = $mailer;
    }

    /**
     * @param UserRegistrationDto $userDto
     * @return User
     * @throws TransportExceptionInterface
     */
    public function handle(UserRegistrationDto $userDto): User
    {
        $user = $this->userFactory->createFromRegistrationDto($userDto);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'security_registration_confirmation',
            $user->getId(),
            $user->getEmail()
        );

        // TODO
//        $email = new TemplatedEmail();
//        $email->from('send@example.com');
//        $email->to($user->getEmail());
//        $email->htmlTemplate('email/registration/confirmation_email.html.twig');
//                $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);

//        $this->mailer->send($email);
        return $user;
    }
}