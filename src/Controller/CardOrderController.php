<?php


namespace App\Controller;


use App\Form\CardOrderType;
use App\Service\Api\ApiClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class CardOrderController extends AbstractController
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
     * @Route({
     *     "en": "/card-orders",
     *     "fr": "/commandes-de-cartes"
     *      },
     *     name="card_order_orders_admin",
     *     methods={"GET"})
     * @Security("user.isValidateContract() and is_granted('ROLE_ADMIN')")
     */
    public function orders()
    {
        $response = $this->apiClient->request('GET','/orders');
        $orders = $response["res"];
        $errorMessage = $response["errorMessage"];

        return $this->render('admin/order/orders.html.twig', [
            'orders' => $orders,
            'errorMessage' => $errorMessage
        ]);
    }

    /**
     * @Route({
     *     "en": "/order-cards",
     *     "fr": "/commander-des-cartes"
     *      },
     *     name="card_order_create_admin",
     *     methods={"GET", "POST"})
     * @Security("user.isValidateContract() and is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $form = $this->createForm(CardOrderType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $body = json_encode(["center" => $data['center']->getCode(), "quantity" => $data['quantity']]);
            $response = $this->apiClient->request('POST','/orders', $body);
            $order = $response["res"];

            if(null != $order) {
                $this->addFlash('success', 'card.order.ok');
                return $this->redirectToRoute("card_order_orders_admin");
            }
        }

        return $this->render('admin/order/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/receive-order", name="card_order_receive_order" ,methods={"PUT"})
     * @Security("user.isValidateContract() and is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return JsonResponse
     */
    public function receiveOrder(Request $request)
    {
        $orderId = json_decode($request->getContent())->orderId;

        $body = json_encode(['received' => true]);

        $res = $this->apiClient->request('PUT','/orders/'.$orderId, $body);

        $statusCode = $res['res'] ? 200 : 500;
        return $this->json($res, $statusCode);
    }
}