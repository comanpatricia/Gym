<?php

namespace App\Command;

use App\Command\Exceptions\InvalidCsvRowException;
use App\Command\Exceptions\InvalidPathException;
use App\Entity\Programme;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Log\LoggerAwareTrait;

class ProgrammeImportFromCsvCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

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
            $handlerInvalidRow = __DIR__ . '/InvalidRowsReturned.txt';
            $handlerPath = '/home/patricia/Gym/src/Command/Programmes.csv';
            var_dump($handlerPath);
            $handler = fopen($handlerPath, 'r');
            if (!file_exists($handlerPath)) {
                throw new InvalidPathException('The path in not valid.', 0, null, $handlerPath);
            }
            if (!file_exists($handlerInvalidRow)) {
                throw new InvalidPathException('The path in not valid.', 0, null, $handlerInvalidRow);
            }

            $this->importProgrammesFromCsv($handler, $handlerInvalidRow);
        } catch (InvalidPathException $exception) {
            echo $exception->getMessage();
            $inputOutput->error('Path was not found.');
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
            $isOnline = \filter_var($column[4], FILTER_VALIDATE_BOOLEAN);
            $maxParticipants = $column[5];

            $programme = new Programme();
            $programme->name = $name;
            $programme->description = $description;
            $programme->setStartTime($startTime);
            $programme->setEndTime($endTime);
            $programme->isOnline = $isOnline;
            $programme->maxParticipants = $maxParticipants;

            $this->entityManager->persist($programme);
            $this->entityManager->flush();
        }
    }
}
