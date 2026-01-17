<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RateLimit implements FilterInterface
{
    protected $cache;
    protected int $maxAttempts = 5;
    protected int $decayMinutes = 1;

    public function __construct()
    {
        $this->cache = \Config\Services::cache();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $ip = $request->getIPAddress();
        $key = 'rate_limit_' . md5($ip . $request->getUri());
        
        $attempts = $this->cache->get($key) ?? 0;

        if ($attempts >= $this->maxAttempts) {
            return service('response')
                ->setStatusCode(429)
                ->setJSON([
                    'success' => false,
                    'message' => 'Çok fazla istek gönderdiniz. Lütfen ' . $this->decayMinutes . ' dakika bekleyin.'
                ]);
        }

        $this->cache->save($key, $attempts + 1, $this->decayMinutes * 60);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // After işlemi yok
    }
}