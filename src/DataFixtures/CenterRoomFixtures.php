<?php


namespace App\DataFixtures;


use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CenterRoomFixtures extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            // Room
            $room = new Room();
            $room->setName('Room '.$i);
            $room->setCapacity(rand(20, 30));
            $room->setSurface(rand(400, 600));
            $room->setPicture(rand(1,2).'.jpg');
            if ($i === 0 || $i === 1) {
                $center = $this->getReference('center0');
            } elseif ($i === 2 || $i === 3) {
                $center = $this->getReference('center1');
            } else {
                $center = $this->getReference('center2');
            }

            $room->setCenter($center);

            $this->addReference("room".$i, $room);
            $manager->persist($room);
        }

        $manager->flush();
    }
}