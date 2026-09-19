<?php

namespace App\Libraries;

use Exception;

/**
 * This class is responsible for handling JSON Web Tokens (JWT)
 */
class JWT
{
    private string $secret;
    private string $algorithm;

    public function __construct()
    {
        $this->secret    = env('JWT_SECRET');
        $this->algorithm = 'HS256';
    }

    /**
     * Generates a new JWT
     */
    public function generate(array $payload, int $ttl = 3600): string
    {
        $iat = time();
        $payload['iat'] = $iat;
        $payload['exp'] = $iat + $ttl;

        return \Firebase\JWT\JWT::encode($payload, $this->secret, $this->algorithm);
    }

    /**
     * return whether the token is valid or not
     */
    public function validate(string $token): ?object
    {
        try {
            // Extracts the Bearer prefix
            if (preg_match('/Bearer\s(\S+)/', $token, $matches)) {
                $token = $matches[1];
            }

            return \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($this->secret, $this->algorithm));
        } catch (Exception $e) {
            // expired token, invalid signature, ...
            return null;
        }
    }
}
