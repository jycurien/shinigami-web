<?php


namespace App\Controller;


use App\Form\CreateNumericCardType;
use App\Handler\Card\CardHandler;
use App\Service\Api\ApiClient;
use App\Service\QrCodeGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            $response = $this->apiClient->request('GET','/create-numeric-card/'.$centerCode);
            $card = $response["res"];
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
//
//    /**
//     * Send email with numeric card
//     * @Route("/admin/send-numeric-card-by-email", name="card_send_numeric_card_by_email_admin")
//     * @Security("user.isValidateContract() and has_role('ROLE_STAFF')")
//     * @param Request $request
//     * @param \Swift_Mailer $mailer
//     * @param TranslatorInterface $translator
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse
//     */
//    public function sendNumericCardByEmail(Request $request, \Swift_Mailer $mailer, TranslatorInterface $translator)
//    {
//        $cardNumber = str_replace(' ', '', $request->request->get('cardNumber'));
//
//        $message = (new \Swift_Message($translator->trans('card_activation_email_title', [], 'app')))
//            ->setFrom($this->getParameter('from_email_address'))
//            ->setTo($request->request->get('email'))
//            ->setBody(
//                $this->render(
//                    'email/numeric_card.html.twig', [
//                        'cardNumber' => $cardNumber
//                    ]
//                ),
//                'text/html'
//            )
//        ;
//
//        $mailer->send($message);
//        $this->addFlash('success', 'l\'email a été envoyé' );
//
//        return $this->redirectToRoute("cards_admin");
//    }
//
//    /**
//     * Activate a fidelity card
//     * @Route({
//     *     "en": "/activate-card",
//     *     "fr": "/activer-une-carte-de-fidelite"
//     *      },
//     *     name="card_activate_card",
//     *     methods={"GET", "POST"})
//     * @Security("has_role('ROLE_USER')")
//     * @param Request $request
//     * @param UserHandler $userHandler
//     * @param CardHandler $cardHandler
//     * @return Response
//     */
//    public function activateCard(Request $request, UserHandler $userHandler, CardHandler $cardHandler)
//    {
//        $options = [
//            'label' => 'input_card_number',
//            'labelSubmit' => 'activate'
//        ];
//
//        $form = $this->createForm(NumberCardType::class, $options)->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            // On récupère le numéro de la carte
//            $number = $form->getData()['number'];
//
//            // We handle the update of the card activation date
//            $card = $cardHandler->updateActivationDateCard($number);
//
//            // We update the user CardNumbers Field
//            if(null != $card) {
//                $userHandler->updateCardNumbersUser($number);
//            }
//        }
//
//        return $this->render("card/activate-card.html.twig",[
//            'form' =>$form->createView(),
//        ]);
//    }
}