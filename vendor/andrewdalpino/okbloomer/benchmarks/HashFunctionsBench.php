<?php

namespace OkBloomer\Benchmarks;

/**
 * @BeforeMethods({"setUp"})
 */
class HashFunctionsBench
{
    private const NUM_TOKENS = 10000;

    private const TOKEN_LENGTH = 25;

    /**
     * @var list<string>
     */
    protected $tokens;

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
    }

    /**
     * @Subject
     * @revs(50)
     * @Iterations(5)
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function adler32() : void
    {
        foreach ($this->tokens as $token) {
            $digest = hash('adler32', $token);
        }
    }

    /**
     * @Subject
     * @revs(50)
     * @Iterations(5)
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function crc32() : void
    {
        foreach ($this->tokens as $token) {
            $digest = crc32($token);
        }
    }

    /**
     * @Subject
     * @revs(50)
     * @Iterations(5)
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function crc32b() : void
    {
        foreach ($this->tokens as $token) {
            $digest = hash('crc32b', $token);
        }
    }

    /**
     * @Subject
     * @revs(50)
     * @Iterations(5)
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function crc32c() : void
    {
        foreach ($this->tokens as $token) {
            $digest = hash('crc32c', $token);
        }
    }

    /**
     * @Subject
     * @revs(50)
     * @Iterations(5)
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function fnv132() : void
    {
        foreach ($this->tokens as $token) {
            $digest = hash('fnv132', $token);
        }
    }

    /**
     * @Subject
     * @revs(50)
     * @Iterations(5)
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function fnv1a32() : void
    {
        foreach ($this->tokens as $token) {
            $digest = hash('fnv1a32', $token);
        }
    }

    /**
     * @Subject
     * @revs(50)
     * @Iterations(5)
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function fnv164() : void
    {
        foreach ($this->tokens as $token) {
            $digest = hash('fnv164', $token);
        }
    }

    /**
     * @Subject
     * @revs(50)
     * @Iterations(5)
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function fnv1a64() : void
    {
        foreach ($this->tokens as $token) {
            $digest = hash('fnv1a64', $token);
        }
    }
}
