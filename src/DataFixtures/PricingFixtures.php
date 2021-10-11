<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 18/12/2018
 * Time: 10:34
 */

namespace App\DataFixtures;


use App\Entity\Pricing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PricingFixtures extends fixture
{
    public function load(ObjectManager $manager)
    {
        // Standard Pricing
        $pricing1 = new Pricing();
        $pricing1->setCode('standard');
        $pricing1->setAmount(30);
        $pricing1->setDescription('Tarif standard.');
        $manager->persist($pricing1);

        // Fidelity Pricing
        $pricing2 = new Pricing();
        $pricing2->setCode('fidelity');
        $pricing2->setAmount(0);
        $pricing2->setDescription('Offre réservée aux porteurs de la carte de fidélité !');
        $pricing2->setNumberOfGames(10);
        $manager->persist($pricing2);

        // Group Pricing
        $pricing3 = new Pricing();
        $pricing3->setCode('group');
        $pricing3->setAmount(25);
        $pricing3->setDescription('Tarif réduit pour les groupes.');
        $pricing3->setNumberOfPlayers(12);
        $manager->persist($pricing3);

        $manager->flush();
    }
}