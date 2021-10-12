<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Center;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class CenterFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Get the input data for centers from yaml file
        $centers = Yaml::parseFile(__DIR__.'/centersData.yaml');

        // loop on 3 centers and set data
        for ($i = 0; $i < 3; $i++) {
            // Center
            $center = New Center();
            $center->setName($centers['name'][$i]);
            $center->setCode(124 + $i);
            $center->setPicture((124 + $i).'.jpg');
            $center->setDescription($centers['description'][$i]);

            $address = new Address();
            $address->setAddress($centers['address'][$i]);
            $address->setCity($centers['city'][$i]);
            $address->setZipCode($centers['zipCode'][$i]);

            $center->setAddress($address);

            $this->addReference("center".$i, $center);

            $manager->persist($center);
        }

        $manager->flush();
    }
}
