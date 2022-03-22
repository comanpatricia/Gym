<?php

namespace App\Command;


use App\Entity\Programme;
use App\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ShowProgrammesCommand extends Command
{
    protected static $defaultName = 'app:programme-import-csv';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $encoders = [new JsonEncoder()];
//        $normalizers = [new ObjectNormalizer()];
//
//        $serializer = new Serializer($normalizers, $encoders);
//
//        $programme = new Programme();
//        $programme->name = 'Dans';
//        $programme->description = 'hai si danseaza cu noi';
//        $programme->startDate = '2022-05-12T10:00:00+02:00';
//        $programme->EndDate = '2022-05-13T10:00:00+02:00';
//        $programme->isOnline = true;
//
////        $user = new User();
//////        $user =
//
//
//        $serializedUser = $serializer->serialize($user, 'json');
//
        return 0;
    }
}
