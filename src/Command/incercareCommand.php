<?php

namespace App\Command;

use App\Repository\RoomRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class incercareCommand extends Command
{
    protected static $defaultName = 'app:incerc';

    private RoomRepository $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $this->roomRepository->getAllRooms();

        $output->write($this->roomRepository->getAllRooms());
    }
}
