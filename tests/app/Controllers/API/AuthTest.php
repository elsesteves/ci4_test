<?php

namespace App\Controllers\API;

use App\Libraries\JWT;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class AuthTest extends CIUnitTestCase
{
    // essential trait for testing routes and controllers
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testReturnSuccessfulLogin() : void
    {
        $data = [
            "email" => env('ADMIN_EMAIL'),
            "password" => env('ADMIN_PASSWD'),
        ];

        $result = $this->withHeaders(['Content-Type' => 'application/json',])
                        ->withBody(json_encode($data))
                        ->post('api/login');

        // Debug the actual result msg
        //fwrite(STDERR, print_r($result->getJSON(), true));

        $result->assertStatus(200);

        //Extracts the output JSON into a var
        $data = json_decode($result->getJSON(), true);

        $result->assertJsonFragment([
            "status" => "200",
            'message' => 'Successfully authenticaded'
        ]);

        $this->assertArrayHasKey('token', $data);
        $this->assertNotEmpty($data['token']);
        $this->assertIsString($data['token']);

        $this->assertArrayHasKey('expires_in', $data);
        $this->assertIsNumeric($data['expires_in']);
    }


    public function testInvalidEmail() : void
    {
        $data = [
            "email" => "chofqfvnovfghvn",
            "password" => env('ADMIN_PASSWD'),
        ];

        $result = $this->withHeaders(['Content-Type' => 'application/json',])
                        ->withBody(json_encode($data))
                        ->post('api/login');

        // Debug the actual result msg
        //fwrite(STDERR, print_r($result->getJSON(), true));

        $result->assertStatus(400);

        //Extracts the output JSON into a var
        $data = json_decode($result->getJSON(), true);

        $result->assertJsonFragment([
            "status" => "400",
            "error" => "400",
        ]);

        $this->assertArrayHasKey('messages', $data);
        $this->assertIsArray($data['messages']);
        $this->assertArrayHasKey('email', $data['messages']);
        $this->assertNotEmpty($data['messages']['email']);
        $this->assertIsString($data['messages']['email']);
    }

    public function testInvalidPassword() : void
    {
        $data = [
            "email" => env('ADMIN_EMAIL'),
            "password" => "dcjw",
        ];

        $result = $this->withHeaders(['Content-Type' => 'application/json',])
                        ->withBody(json_encode($data))
                        ->post('api/login');

        $result->assertStatus(400);

        //Extracts the output JSON into a var
        $data = json_decode($result->getJSON(), true);

        $result->assertJsonFragment([
            "status" => "400",
            "error" => "400",
        ]);

        $this->assertArrayHasKey('messages', $data);
        $this->assertIsArray($data['messages']);
        $this->assertArrayHasKey('password', $data['messages']);
        $this->assertNotEmpty($data['messages']['password']);
        $this->assertIsString($data['messages']['password']);
    }

    public function testWrongPassword() : void
    {
        $data = [
            "email" => env('ADMIN_EMAIL'),
            "password" => 'admin123',
        ];

        $result = $this->withHeaders(['Content-Type' => 'application/json',])
                        ->withBody(json_encode($data))
                        ->post('api/login');

        $result->assertStatus(401);

        //Extracts the output JSON into a var
        $data = json_decode($result->getJSON(), true);

        $result->assertJsonFragment([
            "status" => "401",
            "error" => "401",
            'messages' => [
                "error" => "Invalid Credentials. Try again.",
            ],
        ]);
    }
}