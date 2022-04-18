<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setPlainPassword('Patricia');
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
        $user->email = 'patricia@example.com';
        $user->setRoles(["ROLE_USER"]);
        $user->firstName = 'Patri';
        $user->lastName = 'Coman';
        $user->cnp = '2830420258574';
        $user->setPhoneNumber('0753479397');
        $user->setDeletedAt(null);

        $manager->persist($user);
        $manager->flush();
    }
}
