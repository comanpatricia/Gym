<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoomFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $room = new Room();
        $room->name = 'first room';
        $room->capacity = '30';

        $manager->persist($room);
        $manager->flush();
    }
}
