<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProgrammeImportFromCsvCommand extends Command
{
    private int $programmeMinTimeInMinutes;
    private int $programmeMaxTimeInMinutes;

    protected static $defaultName = 'app:programme-import-csv';

    public function __construct(string $programmeMinTimeInMinutes, string $programmeMaxTimeInMinutes)
    {
        $this->programmeMaxTimeInMinutes = (int) $programmeMaxTimeInMinutes;
        $this->programmeMinTimeInMinutes = (int) $programmeMinTimeInMinutes;

        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        echo $this->programmeMinTimeInMinutes;
        echo $this->programmeMaxTimeInMinutes;

        return Command::SUCCESS;
    }
}
