<?php

namespace App\Command;

use App\Entity\Programme;
use App\Repository\RoomRepository;
use App\Validator\CaesarCipher;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProgrammeImportFromApiCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected static $defaultName = 'app:programme-import-api';

    private EntityManagerInterface $entityManager;

    private HttpClientInterface $client;

    private ValidatorInterface $validator;

    private RoomRepository $roomRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        HttpClientInterface $client,
        ValidatorInterface $validator,
        RoomRepository $roomRepository
    ) {
        $this->entityManager = $entityManager;
        $this->client = $client;
        $this->validator = $validator;
        $this->roomRepository = $roomRepository;

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
            $startTime = \DateTime::createFromFormat('d.m.Y H:i', $row['startDate']);
            $endTime = \DateTime::createFromFormat('d.m.Y H:i', $row['endDate']);
            $isOnline = \filter_var($row['isOnline'], FILTER_VALIDATE_BOOLEAN);
            $maxParticipants = (int) $row['maxParticipants'];

            $programme = new Programme();
            $programme->name = $name;
            $programme->description = $description;
            $programme->setStartTime($startTime);
            $programme->setEndTime($endTime);
            $programme->isOnline = $isOnline;
            $programme->maxParticipants = $maxParticipants;

            $violationList = $this->validator->validate($programme);
            if ($violationList->count() > 0) {
                $message = 'Not able to import programme';
                $this->logger->warning($message);

                throw new \Exception($message);
            }

            $this->roomRepository->findOne();

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

        return $content['data'];
    }
}
