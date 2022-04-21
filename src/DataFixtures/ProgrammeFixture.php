<?php

namespace App\DataFixtures;

use App\Entity\Programme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProgrammeFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $programme = new Programme();
        $programme->name = 'Some stuff';
        $programme->description = 'Some stuff related to description';
        $programme->setStartTime(new \DateTime('2022-05-19 08:30:00'));
        $programme->setEndTime(new \DateTime('2022-05-19 10:15:00'));
        $programme->maxParticipants = 30;
        $programme->isOnline = true;

        $manager->persist($programme);
        $manager->flush();
    }
}
