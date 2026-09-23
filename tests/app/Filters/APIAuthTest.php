<?php

namespace App\Filters;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FilterTestTrait;

use App\Libraries\JWT;

class APIAuthTest extends CIUnitTestCase
{
    use FilterTestTrait;

    protected JWT $jwt;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jwt = new JWT();
    }

    public function testBlopPostUpdateValidToken(): void 
    {
        $payload = [
            'iss' => base_url(), // identifier of the issuer
            'aud' => 'api', // identifier of destination/audience
            'uid' => env('ADMIN_ID'),
        ];
        $token = $this->jwt->generate($payload, 3600);

        $this->request->getUri()->setPath('api/blog/22');
        // Definir o Método para POST
        $this->request = $this->request->withMethod('POST');

        // Adicionar Headers
        $this->request->setHeader('X-Requested-With', 'XMLHttpRequest');
        $this->request->setHeader('Authorization', $token);

        $data = [
            "title" => "1914 translation by H. Rackham",
            "body" => "But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful.\r\n Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it?\r\n But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?",
        ];

        // setup body
        // setup for post fields
        /*
        $this->request->setGlobal('post', $data);
        */

        // raw Body / JSON payload (for API routes):
        $this->request->setBody(json_encode($data));
        $this->request->setHeader('Content-Type', 'application/json');

        $filterCaller = $this->getFilterCaller('App\Filters\APIAuth', 'before');
        $result = $filterCaller(null);

        $this->assertNotContains("invalid or expired token", $data);
    }

    public function testBlopPostUpdateInvalidToken(): void 
    {
        $payload = [
            'iss' => base_url(), // identifier of the issuer
            'aud' => 'api', // identifier of destination/audience
            'uid' => env('ADMIN_ID'),
        ];
        $token = $this->jwt->generate($payload, 3600).'_invalid';

        $this->request->getUri()->setPath('api/blog/22');
        // Definir o Método para POST
        $this->request = $this->request->withMethod('POST');

        // Adicionar Headers
        $this->request->setHeader('X-Requested-With', 'XMLHttpRequest');
        $this->request->setHeader('Authorization', $token);

        $data = [
            "title" => "1914 translation by H. Rackham",
            "body" => "But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful.\r\n Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it?\r\n But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?",
        ];

        // setup body
        // setup for post fields
        /*
        $this->request->setGlobal('post', $data);
        */

        // raw Body / JSON payload (for API routes):
        $this->request->setBody(json_encode($data));
        $this->request->setHeader('Content-Type', 'application/json');

        $filterCaller = $this->getFilterCaller('App\Filters\APIAuth', 'before');
        $result = $filterCaller(null);

        $data = json_decode($result->getJSON(), true);

        $this->assertContains("invalid or expired token", $data);
    }

}