<?php

namespace App\Command;

use App\Entity\Programme;
use App\Validator\CaesarCipher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProgrammeImportFromApiCommand extends Command
{
    protected static $defaultName = 'app:programme-import-api';

    private EntityManagerInterface $entityManager;

    private HttpClientInterface $client;

    public function __construct(
        EntityManagerInterface $entityManager,
        HttpClientInterface $client
    ) {
        $this->entityManager = $entityManager;
        $this->client = $client;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);
        $array = $this->importFromApi();

        $importedRows = 0;

        foreach ($array as $row) {
            ++$importedRows;
            $name = CaesarCipher::decipher($row['name'], 8);
            $description = CaesarCipher::decipher($row['description'], 8);
            $startTime = date_create_from_format('d.m.Y H:i', $row['startDate']);
            $endTime = date_create_from_format('d.m.Y H:i', $row['endDate']);
//            $startTime = new \DateTime($row['startTime']);
//            $endTime = new \DateTime($row['endTime']);
            $isOnline = filter_var($row['isOnline'], FILTER_VALIDATE_BOOLEAN);
            $maxParticipants = (int) $row['maxParticipants'];

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

        $inputOutput->success('Were imported ' . $importedRows . ' programmes');

        return Command::SUCCESS;
    }

    public function importFromApi(): array
    {
        $response = $this->client->request('GET', 'https://evozon-internship-data-wh.herokuapp.com/api/sport-programs');
        $content = $response->getContent();
        $content = $response->toArray();
        $data = $content['data'];
        return $data;
    }
}
