<?php


namespace App\Event\Subscriber;


use App\Entity\User;
use App\Event\NewEmployeeEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class NewEmployeeSubscriber implements EventSubscriberInterface
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    private $fromEmail;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $parameterBag)
    {
        $this->mailer = $mailer;
        $this->fromEmail = $parameterBag->get('from_email_address');
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            NewEmployeeEvent::NAME => 'onNewEmployeeCreated'
        ];
    }

    /**
     * @param NewEmployeeEvent $event
     * @throws TransportExceptionInterface
     */
    public function onNewEmployeeCreated(NewEmployeeEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        $email = new TemplatedEmail();
        $email->from($this->fromEmail);
        $email->to($user->getEmail());
        $email->subject('Bienvenue chez Shinigami Laser'); // TODO translate
        $email->htmlTemplate('email/employee/new_employee.html.twig');
        $email->context([
            'username' => $user->getUsername()
        ]);

        $this->mailer->send($email);
    }
}