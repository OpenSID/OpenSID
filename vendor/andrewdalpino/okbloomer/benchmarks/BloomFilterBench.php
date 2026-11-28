<?php

namespace OkBloomer\Benchmarks;

use OkBloomer\BloomFilter;

/**
 * @BeforeMethods({"setUp"})
 */
class BloomFilterBench
{
    private const NUM_TOKENS = 100000;

    private const TOKEN_LENGTH = 25;

    /**
     * @var list<string>
     */
    protected $tokens;

    /**
     * @var \OkBloomer\BloomFilter
     */
    protected $filter;

    /**
     * Generate a token of length k.
     *
     * @param int $k
     * @return string
     */
    private static function generateToken(int $k) : string
    {
        $token = '';

        for ($i = 0; $i < $k; ++$i) {
            $token .= chr(rand(0, 254));
        }

        return $token;
    }

    public function setUp() : void
    {
        $tokens = [];

        for ($i = 0; $i < self::NUM_TOKENS; ++$i) {
            $tokens[] = self::generateToken(self::TOKEN_LENGTH);
        }

        $this->tokens = $tokens;

        $this->filter = new BloomFilter();
    }

    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function insert() : void
    {
        foreach ($this->tokens as $token) {
            $this->filter->insert($token);
        }
    }

    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function existsOrInsert() : void
    {
        foreach ($this->tokens as $token) {
            $this->filter->existsOrInsert($token);
        }
    }
}
