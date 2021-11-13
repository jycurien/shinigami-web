<?php


namespace App\Handler\Pricing;


use App\Dto\PricingDto;
use App\Entity\Pricing;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class PricingHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(EntityManagerInterface $entityManager, FlashBagInterface $flashBag)
    {
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
    }

    public function handle(PricingDto $pricingDto, Pricing $pricing): Pricing
    {
        if (null === $pricing) {
            $pricing = new Pricing();
        }
        $pricing->setAmount($pricingDto->amount);
        $pricing->setCode($pricingDto->code);
        $pricing->setDescription($pricingDto->description);
        $pricing->setNumberOfGames($pricingDto->numberOfGames);
        $pricing->setNumberOfPlayers($pricingDto->numberOfPlayers);

        $this->flashBag->add('success', 'pricing.update.ok');

        $this->entityManager->persist($pricing);
        $this->entityManager->flush();

        return $pricing;
    }
}