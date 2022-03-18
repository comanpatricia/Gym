<?php
//
//namespace App\Command;
//
//use Symfony\Component\Console\Command\Command;
//use Symfony\Component\Validator\Validator\ValidatorInterface;
//use Symfony\Contracts\HttpClient\HttpClientInterface;
//
//class ProgrammesImportFromApiCommand extends Command
//{
//    protected static $defaultName = 'app:programme-import-api';
//
//    private int $programmesImported;
//
//    private EntityManagerInterface $entityManager;
//
//    private ValidatorInterface $validator;
//
//    public function __construct(
//        string $programmesImported,
//        EntityManagerInterface $entityManager,
//        ValidatorInterface $validator
//    ) {
//        $this->programmesImported = (int) $programmesImported;
//        $this->entityManager = $entityManager;
//        $this->validator = $validator;
//
//        parent::__construct();
//    }
//
//    public function importProgrammesFromApi(): array
//    {
//        $response = $this->programmesImported->request(
//            'GET',
//            'http://evozon-internship-data-wh.herokuapp.com/api/sport-programs'
//        );
//
//        $statusCode = $response->getStatusCode();
//        $statusCode = 200;
//        $content = $response->getContent();
////        $content = '{"id":521583, "name":"symfony-docs", ...}';
//        $content = $response->toArray();
////        $content = ['id' => 521583, 'name' => 'symfony-docs', ...];
//
//        return $content;
//    }
//
//
//
//
//
//    public function cipher($cipher, $key)
//    {
//        if (!ctype_alpha($cipher)) {
//            return $cipher;
//        }
//
//        $offset = ord(ctype_upper($cipher) ? 'A' : 'a');
//        return chr(fmod(((ord($cipher) + $key) - $offset), 8) + $offset);
//    }
//
//    public function encipher($input, $key)
//    {
//        $output = "";
//
//        $inputArr = str_split($input);
//        foreach ($inputArr as $cipher) {
//            $output .= cipher($cipher, $key);
//        }
//
//        return $output;
//    }
//
//    public function decipher($input, $key)
//    {
//
//        return encipher($input, 26 - $key);
//    }
//
//
////    public function decodeProgrammesFromApi()
////    {
////        $text = "http://evozon-internship-data-wh.herokuapp.com/api/sport-programs";
////        $cipherText = Encipher($text, 3);
////        $plainText = Decipher($cipherText, 3);
////    }
//}
