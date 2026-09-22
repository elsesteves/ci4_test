<?php

namespace App\Controllers\API;

use App\Libraries\JWT;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class UserTest extends CIUnitTestCase
{
    // essential trait for testing routes and controllers
    use FeatureTestTrait;

    private JWT $jwt;

    protected function setUp(): void
    {
        parent::setUp();

        
        $this->jwt = new JWT();
    }

    public function testReturnValidUserProfile() : void
    {
        $jwtPayload = [
            'uid' => 1,
            
            'iss' => base_url(), // identifier of the issuer
            'aud' => 'api', // identifier of destination/audience
        ];        
        $token = $this->jwt->generate($jwtPayload, 3600);

        $result = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get('api/user/profile');

        // Debug the actual result msg
        //fwrite(STDERR, print_r($result->getJSON(), true));

        $result->assertStatus(200);

        //Extracts the output JSON into a var
        $data = json_decode($result->getJSON(), true);
        $this->assertValidUserProfileContract($data);   
    }

    public function testReturnValidUserProfile4BrowserSession() : void
    {
       $sessionData = [
            "id" => 1,
            'isLoggedIn' => true,
        ];

        $result = $this->withHeaders([])
                        ->withSession($sessionData)
                        ->get('api/user/profile');

        // Debug the actual result msg
        //fwrite(STDERR, print_r($result->getJSON(), true));

        $result->assertStatus(200);

        //Extracts the output JSON into a var
        $data = json_decode($result->getJSON(), true);
        $this->assertValidUserProfileContract($data);        
    }

    private function assertValidUserProfileContract(array $data): void
    {
        $this->assertArrayHasKey('id', $data);
        $this->assertIsNumeric($data['id']);

        $this->assertArrayHasKey('firstname', $data);
        $this->assertNotEmpty($data['firstname']);

        $this->assertArrayHasKey('lastname', $data);
        $this->assertNotEmpty($data['lastname']);

        $regexTimestamp = '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/';

        $this->assertArrayHasKey('created_at', $data);
        $this->assertMatchesRegularExpression($regexTimestamp, $data['created_at']);

        $this->assertArrayHasKey('updated_at', $data);
        $this->assertMatchesRegularExpression($regexTimestamp, $data['updated_at']);
    }

    public function testUserProfileNoLoggedInSession() : void
    {
        $session = [
            "id" => 1,
        ];

        $result = $this->withSession($session)
                        ->get('api/user/profile');

        $result->assertStatus(401);
        
        $result->assertJsonFragment([
            'error' => 'Missing authentication token'
        ]);
    }

    public function testUserProfileNoIDSession() : void
    {
        $session = [
            "isLoggedIn" => true
        ];

        $result = $this->withSession($session)
                        ->get('api/user/profile');

        $result->assertStatus(401);
        
        $result->assertJsonFragment([
            'error' => 'Missing authentication token'
        ]);
    }

    public function testUserProfileExpiredToken() : void
    {
        $jwtPayload = ['uid' => 1];        
        $token = $this->jwt->generate($jwtPayload, -10);


        $result = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get('api/user/profile');

        $result->assertStatus(401);
        
        $result->assertJsonFragment([
            'error' => 'invalid or expired token'
        ]);
    }
    

    public function testUserProfileNoToken() : void
    {
        $jwtPayload = ['uid' => 1];        
        $token = $this->jwt->generate($jwtPayload, -10);


        $result = $this->withHeaders([])->get('api/user/profile');

        $result->assertStatus(401);
        
        $result->assertJsonFragment([
            'error' => 'Missing authentication token'
        ]);
    }
}