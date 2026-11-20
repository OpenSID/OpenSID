<?php

$config = new class extends Amp\CodeStyle\Config {
    public function getRules(): array
    {
        $rules = parent::getRules();

        $rules['declare_strict_types'] = false;

        return $rules;
    }
};

$config->getFinder()
    ->in(__DIR__ . '/examples')
    ->in(__DIR__ . '/lib')
    ->in(__DIR__ . '/test');

$config->setCacheFile(__DIR__ . '/.php_cs.cache');

return $config;
