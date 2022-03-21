<?php

namespace App\Validator;

class CaesarCipher
{
    public string $cipher = '';

    public int $key = 0;

    public static function cipher($cipher, $key): string
    {
        if (!ctype_alpha($cipher)) {
            return $cipher;
        }
        $offset = ord(ctype_upper($cipher) ? 'A' : 'a');

        return chr(fmod(((ord($cipher) + $key) - $offset), 26) + $offset);
    }

    public static function encipher($input, $key): string
    {
        $output = '';
        $inputArray = str_split($input);

        foreach ($inputArray as $cipher) {
            $output .= CaesarCipher::cipher($cipher, $key);
        }

        return $output;
    }

    public static function decipher($input, $key): string
    {
        return CaesarCipher::encipher($input, 26 - $key);
    }
}
