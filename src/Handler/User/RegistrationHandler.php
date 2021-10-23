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
     * @param string $fromEmailAddress
     * @return User
     * @throws TransportExceptionInterface
     */
    public function handle(UserRegistrationDto $userDto, string $fromEmailAddress): User
    {
        $user = $this->userFactory->createFromRegistrationDto($userDto);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'security_registration_confirmation',
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );

        $email = new TemplatedEmail();
        $email->from($fromEmailAddress);
        $email->to($user->getEmail());
        $email->subject('Validation de votre compte Shinigami Laser'); // TODO translate
        $email->htmlTemplate('email/registration/confirmation_email.html.twig');
        $email->context([
            'username' => $user->getUsername(),
            'signedUrl' => $signatureComponents->getSignedUrl(),
            'linkExpiresAt' => $signatureComponents->getExpiresAt()
        ]);

        $this->mailer->send($email);
        return $user;
    }
}