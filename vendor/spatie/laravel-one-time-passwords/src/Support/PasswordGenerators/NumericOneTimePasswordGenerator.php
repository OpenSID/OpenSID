<?php

namespace Spatie\OneTimePasswords\Support\PasswordGenerators;

class NumericOneTimePasswordGenerator extends OneTimePasswordGenerator
{
    public function generate(): string
    {
        $max = (10 ** $this->numberOfCharacters) - 1;

        $randomNumber = random_int(0, $max);

        return str_pad($randomNumber, $this->numberOfCharacters, '0', STR_PAD_LEFT);
    }
}
