<?php

namespace App\DDD\User\Application\Service;

use App\DDD\User\Domain\Service\AuthTokenGeneratorInterface;
use Exception;

class AuthTokenGenerator implements AuthTokenGeneratorInterface
{
    private const ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const TOKEN_LENGTH = 32;

    /**
     * @throws Exception
     */
    public function generate(): string
    {
        return sprintf(
            '%s%s',
            $this->getRandomStringShuffle(self::TOKEN_LENGTH / 2),
            $this->getRandomStringRand(self::TOKEN_LENGTH / 2),
        );
    }

    /**
     * @throws Exception
     */
    private function getRandomStringRand(int $length): string
    {
        $stringLength = strlen(self::ALPHABET);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= self::ALPHABET[random_int(0, $stringLength - 1)];
        }

        return $randomString;
    }

    private function getRandomStringShuffle(int $length): string
    {
        $stringLength = strlen(self::ALPHABET);
        $string = str_repeat(self::ALPHABET, ceil($length / $stringLength));
        $shuffledString = str_shuffle($string);

        return substr($shuffledString, 1, $length);
    }
}
