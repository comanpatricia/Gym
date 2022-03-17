<?php

namespace App\Command;

use App\Entity\Programme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProgrammeImportFromCsvCommand extends Command
{
    protected static $defaultName = 'app:programme-import-csv';

    private int $programmeMinTimeInMinutes;

    private int $programmeMaxTimeInMinutes;

    private EntityManagerInterface $entityManager;

    private ValidatorInterface $validator;

    public function __construct(
        string $programmeMinTimeInMinutes,
        string $programmeMaxTimeInMinutes,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ) {
        $this->programmeMaxTimeInMinutes = (int) $programmeMaxTimeInMinutes;
        $this->programmeMinTimeInMinutes = (int) $programmeMinTimeInMinutes;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);

        echo $this->programmeMinTimeInMinutes . PHP_EOL;
        echo $this->programmeMaxTimeInMinutes . PHP_EOL;

        $handler = fopen('/home/patricia/Gym/src/Command/Programmes.csv', 'r');

        $array = [];

        try {
            $handlerPath = __DIR__ . '/Programmes.csv';
            if (file_exists($handlerPath)) {
                $handler = fopen($handlerPath, 'r');
            }
            $this->importProgrammesFromCsv($handler);
        } catch (InvalidCSVRowException $exception) {
            echo $exception->getMessage();
            $inputOutput->error('Programmes were not imported.');

            return Command::FAILURE;
        } finally {
            fclose($handler);
            $inputOutput->info('Files closed succesfully!');
        }
        $inputOutput->success('Programmes were imported!');

        return Command::SUCCESS;
    }


//        fgetcsv($handler);
//        while (($data = fgetcsv($handler, null, '|')) == 1) {
//            $array[] = $data;
//        }
//
//        foreach ($array as $column) {
//            $name = $column[0];
//            $description = $column[1];
//            $startTime = $column[2];
//            $endTime = $column[3];
//            $isOnline = $column[4];
//
//            $programme = new Programme();
//            $programme->name = $name;
//            $programme->description = $description;
//            $programme->setStartTime($startTime);
//            $programme->setEndTime($endTime);
//
//            $this->entityManager->persist($programme);
//            $this->entityManager->flush();
//
////            throw new InvalidCsvRowException(
////                'There are invalid rows.',
////                0,
////                null,
////                ['Name', 'Description', 'Start time', 'End time', 'Online']
////            );
////        }
//
//            fclose($handler);
//
//            $inputOutput->success('Programmes inserted.');
//            return Command::SUCCESS;
//        }

    public function importProgrammesFromCsv($handler): void
    {
        fgetcsv($handler);
        while (($data = fgetcsv($handler, null, '|')) !== false) {
            if (sizeof($data) < 5) {
                throw new InvalidCSVRowException('There are invalid rows.', 0, null, $data);
            }
            $array[] = $data;
        }
//        $array[] = $data;

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
