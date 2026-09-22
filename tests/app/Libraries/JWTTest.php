<?php

namespace App\Libraries;

use CodeIgniter\Test\CIUnitTestCase;

class JWTTest extends CIUnitTestCase
{
    private JWT $jwt;
    private string $secret;

    protected function setUp(): void //sort of a __construct is called before each test, useful for initializing properties
    {
        parent::setUp();

        $this->secret = env('JWT_SECRET');

        // Define a variável de ambiente necessária pelo construtor da classe JWT
        $_ENV['JWT_SECRET'] = $this->secret;
        putenv("JWT_SECRET={$this->secret}");

        $this->jwt = new JWT();
    }

    public function testGeneratValidToken(): void
    {
        $payload = ['user_id' => 42, 'role' => 'admin'];
        
        $token = $this->jwt->generate($payload, 3600);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }

    public function testValidateTokenNoBearerSuccess(): void
    {
        $payload = ['user_id' => 42];
        $token = $this->jwt->generate($payload, 3600);

        $decoded = $this->jwt->validate($token);

        $this->assertNotNull($decoded);
        $this->assertEquals(42, $decoded->user_id);
        $this->assertObjectHasProperty('iat', $decoded);
        $this->assertObjectHasProperty('exp', $decoded);
    }

    public function testValidateToken4BearerPrefixSuccess(): void
    {
        $user_id = 100;

        $payload = ['user_id' => $user_id];
        $token = $this->jwt->generate($payload, 3600);
        
        $bearerToken = 'Bearer ' . $token;

        $decoded = $this->jwt->validate($bearerToken);

        $this->assertNotNull($decoded);
        $this->assertEquals($user_id, $decoded->user_id);
    }

    public function testNullReturn4InvalidOrTemperedToken(): void
    {
        $payload = ['user_id' => 1];
        $token = $this->jwt->generate($payload, 3600);
        
        $tokenInvalido = $token . 'tempered';

        $decoded = $this->jwt->validate($tokenInvalido);

        $this->assertNull($decoded);
    }

    public function testNullReturn4ExpiredToken(): void
    {
        $payload = ['user_id' => 1];
        
        // Set negative TTL to simulate an expired token
        $tokenExpirado = $this->jwt->generate($payload, -10);

        $decoded = $this->jwt->validate($tokenExpirado);

        $this->assertNull($decoded);
    }
}