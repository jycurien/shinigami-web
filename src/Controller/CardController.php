<?php


namespace App\Controller;


use App\Form\CardNumberType;
use App\Form\CreateNumericCardType;
use App\Handler\Card\CardHandler;
use App\Handler\User\UserHandler;
use App\Service\Api\ApiClient;
use App\Service\QrCodeGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CardController extends AbstractController
{

    /**
     * @var ApiClient
     */
    private $apiClient;

    public function __construct(ApiClient $apiClient)
    {

        $this->apiClient = $apiClient;
    }

    /**
     * Display most recent fidelity cards
     * @Route({
     *     "en": "/admin/fidelity-cards",
     *     "fr": "/admin/cartes-de-fidelite"
     *      },
     *     name="cards_admin",
     *     methods={"GET"})
     * @Security("user.isValidateContract() and is_granted('ROLE_STAFF')")
     * @return Response
     */
    public function cards(): Response
    {
        $response = $this->apiClient->request('GET','/cards');

        return $this->render('admin/card/cards.html.twig', [
            'cards' => $response["res"],
            'errorMessage' => $response["errorMessage"]
        ]);
    }

    /**
     * Create a new numeric fidelity card
     * @Route({
     *     "en": "/admin/create-fidelity-card",
     *     "fr": "/admin/creer-une-carte-de-fidelite"
     *      },
     *     name="card_create_admin",
     *     methods={"GET", "POST"})
     * @Security("user.isValidateContract() and is_granted('ROLE_STAFF')")
     * @param null|Request $request
     * @param CardHandler $cardHandler
     * @param QrCodeGenerator $qrCodeGenerator
     * @return Response
     */
    public function create(Request $request, CardHandler $cardHandler, QrCodeGenerator $qrCodeGenerator): Response
    {
        $form = $this->createForm(CreateNumericCardType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $centerCode = $form->getData()['center']->getCode();
            $body = json_encode(["center" => $centerCode]);
            $card = null;
            try {
                $response = $this->apiClient->request('POST','/cards', $body);
                $card = $response["res"];
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage() );
                return $this->redirectToRoute("card_create_admin");
            }

            if(null != $card) {
                // Generate the QR code
                $qrCodeGenerator->generate($cardHandler->formatCardNumber($card));

                $this->addFlash('success', 'card.created.ok' );
                return $this->redirectToRoute("cards_admin");
            }
        }

        return $this->render('admin/card/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Send email with numeric card
     * @Route("/admin/send-numeric-card-by-email", name="card_send_numeric_card_by_email_admin")
     * @Security("user.isValidateContract() and is_granted('ROLE_STAFF')")
     * @param Request $request
     * @param MailerInterface $mailer
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     * @throws TransportExceptionInterface
     */
    public function sendNumericCardByEmail(Request $request, MailerInterface $mailer, TranslatorInterface $translator): RedirectResponse
    {
        $cardNumber = str_replace(' ', '', $request->request->get('cardNumber'));

        $email = new TemplatedEmail();
        $email->from($this->getParameter('from_email_address'));
        $email->to($request->request->get('email'));
        $email->subject($translator->trans('card_activation_email_title', [], 'app'));
        $email->htmlTemplate('email/card/numeric_card.html.twig');
        $email->context([
            'cardNumber' => $cardNumber,
            'web_url' => $this->getParameter('web_url')
        ]);

        $mailer->send($email);

        $this->addFlash('success', 'l\'email a ??t?? envoy??' );

        return $this->redirectToRoute("cards_admin");
    }

    /**
     * Activate a fidelity card
     * @Route({
     *     "en": "/activate-card",
     *     "fr": "/activer-une-carte-de-fidelite"
     *      },
     *     name="card_activate_card",
     *     methods={"GET", "POST"})
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @param UserHandler $userHandler
     * @param CardHandler $cardHandler
     * @return Response
     */
    public function activateCard(Request $request, UserHandler $userHandler, CardHandler $cardHandler)
    {
        $options = [
            'label' => 'input_card_number',
            'labelSubmit' => 'activate'
        ];

        $form = $this->createForm(CardNumberType::class, $options)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $number = $form->getData()['number'];

            $card = $cardHandler->updateActivationDateCard($number);

            if(null != $card) {
                $userHandler->updateCardNumbersUser($this->getUser(), $number);
            }
        }

        return $this->render("card/activate-card.html.twig",[
            'form' =>$form->createView(),
        ]);
    }
}