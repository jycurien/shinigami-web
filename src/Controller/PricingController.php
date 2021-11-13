<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 18/12/2018
 * Time: 10:43
 */

namespace App\Controller;


use App\Dto\PricingDto;
use App\Entity\Pricing;
//use App\Form\PricingType;
use App\Form\PricingType;
use App\Repository\PricingRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class PricingController extends AbstractController
{
    /**
     * @Route({
     *     "en": "/pricings",
     *     "fr": "/tarifs"
     *      },
     *     name="pricing_pricings_admin",
     *     methods={"GET"})
     * @Security("user.isValidateContract() and is_granted('ROLE_ADMIN')")
     * @param PricingRepository $pricingRepository
     * @return Response
     */
    public function pricings(PricingRepository $pricingRepository): Response
    {
        return $this->render('admin/pricing/pricings.html.twig', [
            'pricings' => $pricingRepository->findAll()
        ]);
    }

    /**
     * @Route({
     *     "en": "/pricings/{code<\w+>}/update",
     *     "fr": "/tarifs/{code<\w+>}/modifier"
     *      },
     *     name="pricing_update_admin",
     *     methods={"GET","POST"})
     * @Security("user.isValidateContract() and is_granted('ROLE_ADMIN')")
     * @param Pricing $pricing
     * @param Request $request
     * @return Response
     */
    public function update(Pricing $pricing, Request $request): Response
    {
        $pricingDto = new PricingDto($pricing);
        $form = $this->createForm(PricingType::class, $pricingDto)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($pricingDto);
//            $this->getDoctrine()->getManager()->flush();

//            $this->addFlash('success', 'pricing.update.ok');
//
//            return $this->redirectToRoute('pricing_pricings_admin');
        }

        return $this->render('admin/pricing/update.html.twig', [
            'form' => $form->createView(),
            'pricing' => $pricing
        ]);
    }
}