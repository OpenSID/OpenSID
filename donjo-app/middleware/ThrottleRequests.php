<?php

use Illuminate\Http\Request;
use OpenSID\MiddlewareInterface;
use Illuminate\Support\Facades\RateLimiter;
use App\Events\TooManyRequests;

class ThrottleRequests implements MiddlewareInterface
{
    /**
     * Indicates if the rate limiter keys should be hashed.
     */
    protected static $shouldHashKeys = true;

    /**
     * {@inheritdoc}
     */
    public function run($args)
    {
        $request      = request();
        $key          = $this->resolveRequestSignature($request);
        $maxAttempts  = 60;
        $decaySeconds = 60;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);
            
            event(new TooManyRequests($request, $retryAfter, $maxAttempts, $decaySeconds));
            
            show_error("Too Many Requests. Please try again later in {$retryAfter} seconds.", 429);
        }

        RateLimiter::hit($key, $decaySeconds);
    }

    /**
     * Resolve request signature
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function resolveRequestSignature(Request $request)
    {
        // Jika user login, gunakan user ID
        if ($user = $request->user()) {
            return $this->formatIdentifier($user->getAuthIdentifier());
        }
        
        // Jika tidak login, gunakan host + IP
        if ($host = $request->getHost()) {
            return $this->formatIdentifier("{$host}|{$request->ip()}");
        }

        // Fallback: cuma IP
        return $this->formatIdentifier($request->ip());
    }

    /**
     * Format identifier dengan hashing
     *
     * @param  string  $value
     * @return string
     */
    private function formatIdentifier($value)
    {
        return self::$shouldHashKeys ? sha1($value) : $value;
    }

    /**
     * Disable hashing jika diperlukan.
     *
     * @param  bool  $shouldHashKeys
     * @return void
     */
    public static function shouldHashKeys(bool $shouldHashKeys = true)
    {
        self::$shouldHashKeys = $shouldHashKeys;
    }
}