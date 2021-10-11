<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 18/12/2018
 * Time: 10:43
 */

namespace App\Controller;


use App\Entity\Pricing;
//use App\Form\PricingType;
use App\Repository\PricingRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Security("user.isValidateContract() and has_role('ROLE_ADMIN')")
     * @param PricingRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pricings(PricingRepository $repository)
    {
        // TODO
//        $pricings = $repository->findAll();
//
//        return $this->render('admin/pricing/pricings.html.twig', [
//            'pricings' => $pricings
//        ]);
    }

    /**
     * @Route({
     *     "en": "/pricings/{code<\w+>}/update",
     *     "fr": "/tarifs/{code<\w+>}/modifier"
     *      },
     *     name="pricing_update_admin",
     *     methods={"GET","POST"})
     * @Security("user.isValidateContract() and has_role('ROLE_ADMIN')")
     * @param Pricing $pricing
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Pricing $pricing, Request $request)
    {
        // TODO
//        $form = $this->createForm(PricingType::class, $pricing)->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            $this->addFlash('success', 'pricing.update.ok');
//
//            return $this->redirectToRoute('pricing_pricings_admin');
//        }
//
//        return $this->render('admin/pricing/update.html.twig', [
//            'form' => $form->createView(),
//            'pricing' => $pricing
//        ]);
    }
}