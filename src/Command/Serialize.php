<?php

namespace App\Command;

use App\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Serialize extends Command
{
//    protected static $defaultName = 'app:serialize';
//
//    protected function execute(InputInterface $input, OutputInterface $output)
//    {
//        $encoders = [new XmlEncoder(), new JsonEncoder()];
//        $normalizers = [new ObjectNormalizer()];
//
//        $serializer = new Serializer($normalizers, $encoders);
//
//        $user = new User();
//        $user->firstName = 'Rares';
//        $user->lastName = 'Moldovan';
//        $user->email = 'raresmldvn31@gmail.com';
//
//        $serializedUser = $serializer->serialize($user, 'json');
//
//        return 0;
//    }
}
