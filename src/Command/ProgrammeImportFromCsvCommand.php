<?php

namespace App\Command;

use App\Entity\Programme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProgrammeImportFromCsvCommand extends Command
{
    protected static $defaultName = 'app:programme-import-csv';

    private int $programmeMinTimeInMinutes;

    private int $programmeMaxTimeInMinutes;

    private EntityManagerInterface $entityManager;

    public function __construct(
        string $programmeMinTimeInMinutes,
        string $programmeMaxTimeInMinutes,
        EntityManagerInterface $entityManager
    ) {
        $this->programmeMaxTimeInMinutes = (int) $programmeMaxTimeInMinutes;
        $this->programmeMinTimeInMinutes = (int) $programmeMinTimeInMinutes;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);

        echo $this->programmeMinTimeInMinutes . PHP_EOL;
        echo $this->programmeMaxTimeInMinutes . PHP_EOL;

        try {
            $handlerPath = __DIR__ . '/Programmes.csv';
            if (file_exists($handlerPath)) {
                $handler = fopen('/home/patricia/Gym/src/Command/Programmes.csv', 'r');
            }
            $this->importProgrammesFromCsv($handler);
        } catch (InvalidCSVRowException $exception) {
            echo $exception->getMessage();
            $inputOutput->error('Programmes were not imported.');

            return Command::FAILURE;
        } finally {
            fclose($handler);
            $inputOutput->info('Files closed successfully!');
        }
        $inputOutput->success('Programmes were imported!');

        return Command::SUCCESS;
    }

    public function importProgrammesFromCsv($handler): void
    {
        $array = [];
        fgetcsv($handler);
        while (($data = fgetcsv($handler, null, '|')) !== false) {
            if (sizeof($data) < 5) {
                throw new InvalidCsvRowException('There are invalid rows.', 0, null, $data);
            }
            $array[] = $data;
        }

        foreach ($array as $column) {
            $name = $column[0];
            $description = $column[1];
            $startTime = date_create_from_format('d.m.Y H:i', $column[2]);
            $endTime = date_create_from_format('d.m.Y H:i', $column[3]);
            $isOnline = $column[4];

            $programme = new Programme();
            $programme->name = $name;
            $programme->description = $description;
            $programme->setStartTime($startTime);
            $programme->setEndTime($endTime);
            $programme->isOnline = $isOnline;

            $this->entityManager->persist($programme);
            $this->entityManager->flush();
        }
    }
}
