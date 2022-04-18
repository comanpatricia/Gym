<?php

namespace App\DataFixtures;

use App\Entity\Building;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BuildingFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $building = new Building();
        $building->setStartTime(new \DateTime('2022-05-01 09:00:00'));
        $building->setEndTime(new \DateTime('2022-05-01 22:00:00'));

        $manager->persist($building);
        $manager->flush();
    }
}
