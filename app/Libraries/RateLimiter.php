<?php

namespace App\Libraries;

/**
 * Rate Limiter Library
 * 
 * Provides brute-force protection by limiting login attempts
 * based on IP address and username combinations.
 */
class RateLimiter
{
    protected $session;
    protected $maxAttempts = 5;
    protected $decayMinutes = 5;

    public function __construct()
    {
        $this->session = session();
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
        $data = $this->session->get($key);

        if (!$data) {
            return 0;
        }

        // Check if decay time has passed
        if (time() > $data['reset_at']) {
            $this->clear($key);
            return 0;
        }

        return $data['attempts'];
    }

    /**
     * Increment the counter for a given key
     * 
     * @param string $key
     * @return int New attempt count
     */
    public function hit(string $key): int
    {
        $data = $this->session->get($key);

        if (!$data) {
            $data = [
                'attempts' => 0,
                'reset_at' => time() + ($this->decayMinutes * 60)
            ];
        }

        $data['attempts']++;
        $this->session->set($key, $data);

        return $data['attempts'];
    }

    /**
     * Get the number of seconds until attempts are reset
     * 
     * @param string $key
     * @return int
     */
    public function availableIn(string $key): int
    {
        $data = $this->session->get($key);

        if (!$data) {
            return 0;
        }

        $remaining = $data['reset_at'] - time();
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
        $this->session->remove($key);
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
