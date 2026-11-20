<?php

/**
 * Part of the Joomla Framework String Package
 *
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\String;

use Doctrine\Inflector\InflectorFactory;

/**
 * Joomla Framework String Inflector Class
 *
 * The Inflector transforms words
 *
 * @since  1.0
 * @deprecated  5.0  Use doctrine/inflector package as complete replacement instead.
 */
class Inflector
{
    /**
     * The inflector rules for countability.
     *
     * @var    array
     * @since  2.0.0
     */
    private static $countable = [
        'rules' => [
            'id',
            'hits',
            'clicks',
        ],
    ];

    /**
     * Adds inflection regex rules to the inflector.
     *
     * @param   mixed   $data      A string or an array of strings or regex rules to add.
     * @param   string  $ruleType  The rule type: singular | plural | countable
     *
     * @return  void
     *
     * @since   1.0
     * @throws  \InvalidArgumentException
     */
    private function addRule($data, string $ruleType)
    {
        if (\is_string($data)) {
            $data = [$data];
        } elseif (!\is_array($data)) {
            throw new \InvalidArgumentException('Invalid inflector rule data.');
        } elseif (!\in_array($ruleType, ['singular', 'plural', 'countable'])) {
            throw new \InvalidArgumentException('Unsupported rule type.');
        }

        if ($ruleType === 'countable') {
            foreach ($data as $rule) {
                // Ensure a string is pushed.
                array_push(self::$countable['rules'], (string) $rule);
            }
        } else {
            static::rules($ruleType, $data);
        }
    }

    /**
     * Adds a countable word.
     *
     * @param   mixed  $data  A string or an array of strings to add.
     *
     * @return  $this
     *
     * @since   1.0
     */
    public function addCountableRule($data)
    {
        $this->addRule($data, 'countable');

        return $this;
    }

    /**
     * Checks if a word is countable.
     *
     * @param   string  $word  The string input.
     *
     * @return  boolean  True if word is countable, false otherwise.
     *
     * @since   1.0
     */
    public function isCountable($word)
    {
        return \in_array($word, self::$countable['rules']);
    }

    /**
     * Checks if a word is in a plural form.
     *
     * @param   string  $word  The string input.
     *
     * @return  boolean  True if word is plural, false if not.
     *
     * @since   1.0
     */
    public function isPlural($word)
    {
        return static::pluralize(static::singularize($word)) === $word;
    }

    /**
     * Checks if a word is in a singular form.
     *
     * @param   string  $word  The string input.
     *
     * @return  boolean  True if word is singular, false if not.
     *
     * @since   1.0
     */
    public function isSingular($word)
    {
        return static::singularize($word) === $word;
    }

    /**
     * Proxy for Inflector::tableize()
     */
    public static function tableize(string $word): string
    {
        $inflector = InflectorFactory::create()->build();

        return $inflector->tableize($word);
    }

    /**
     * Proxy for Inflector::classify()
     */
    public static function classify(string $word): string
    {
        $inflector = InflectorFactory::create()->build();

        return $inflector->classify($word);
    }

    /**
     * Proxy for Inflector::camelize()
     */
    public static function camelize(string $word): string
    {
        $inflector = InflectorFactory::create()->build();

        return $inflector->camelize($word);
    }

    /**
     * Proxy for Inflector::ucwords()
     */
    public static function ucwords(string $string, string $delimiters = " \n\t\r\0\x0B-"): string
    {
        return ucwords($string, $delimiters);
    }

    /**
     * Empty method to suffice the former interface
     */
    public static function reset(): void
    {
    }

    /**
     * Empty method to suffice the former interface
     */
    public static function rules(string $type, iterable $rules, bool $reset = false): void
    {
    }

    /**
     * Proxy for Inflector::pluralize()
     */
    public static function pluralize(string $word): string
    {
        $inflector = InflectorFactory::create()->build();

        return $inflector->pluralize($word);
    }

    /**
     * Proxy for Inflector::singularize()
     */
    public static function singularize(string $word): string
    {
        $inflector = InflectorFactory::create()->build();

        return $inflector->singularize($word);
    }
}
