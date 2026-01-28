<?php

namespace App\Core\Modules\Auth\Libraries;

/**
 * Rate Limiter Library
 * 
 * Provides brute-force protection by limiting login attempts
 * based on IP address and username combinations.
 */
class RateLimiter
{
    protected $cache;
    protected $maxAttempts = 5;
    protected $decayMinutes = 5;

    public function __construct()
    {
        $this->cache = \Config\Services::cache();
    }

    /**
     * Check if the given key has too many attempts
     * 
     * @param string $key Unique identifier (e.g., "login:username:192.168.1.1")
     * @return bool
     */
    public function tooManyAttempts(string $key): bool
    {
        $attempts = $this->attempts($key);
        return $attempts >= $this->maxAttempts;
    }

    /**
     * Get the number of attempts for the given key
     * 
     * @param string $key
     * @return int
     */
    public function attempts(string $key): int
    {
        $attempts = $this->cache->get($key);
        return $attempts ? (int) $attempts : 0;
    }

    /**
     * Increment the counter for a given key
     * 
     * @param string $key
     * @return int New attempt count
     */
    public function hit(string $key): int
    {
        $attempts = $this->attempts($key) + 1;
        $this->cache->save($key, $attempts, $this->decayMinutes * 60);
        return $attempts;
    }

    /**
     * Get the number of seconds until attempts are reset
     * 
     * @param string $key
     * @return int
     */
    public function availableIn(string $key): int
    {
        $ttl = $this->cache->getMetadata($key)['expire'] ?? 0;
        $remaining = $ttl - time();
        return max(0, $remaining);
    }

    /**
     * Clear the attempts for the given key
     * 
     * @param string $key
     * @return void
     */
    public function clear(string $key): void
    {
        $this->cache->delete($key);
    }

    /**
     * Get a rate limiter key for login attempts
     * 
     * @param string $username
     * @param string $ip
     * @return string
     */
    public static function loginKey(string $username, string $ip): string
    {
        return 'login_attempt:' . md5($username . ':' . $ip);
    }
}
