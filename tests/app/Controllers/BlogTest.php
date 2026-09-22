<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class BlogTest extends CIUnitTestCase
{
    // essential trait for testing routes and controllers
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testAccessMyPostsSuccess() : void
    {
        $sessionData = [
            "id" => env("ADMIN_ID"),
            'isLoggedIn' => true,
        ];

        $result = $this->withSession($sessionData)
                        ->get('/blog/my-posts');

        //$data = json_decode($result->getJSON(), true);

        $result->assertStatus(200);
        $result->assertSee('My Posts');
    }

    public function testAccessMyPostsFailNoSession() : void
    {
        //Trying to access "My Posts" without a session, gets redirected
        $result = $this->get('/blog/my-posts');

        $result->assertStatus(302);

        $result->assertRedirectTo('/');
    }
}