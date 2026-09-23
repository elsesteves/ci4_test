<?php

namespace App\Filters;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FilterTestTrait;

class AuthTest extends CIUnitTestCase
{
    use FilterTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testBeforeRedirectWhenNotLoggedIn() : void
    {
        session()->destroy();
        
        $this->request->getUri()->setPath('blog/my-posts');

        $filterCaller = $this->getFilterCaller('App\Filters\Auth', 'before');
        $result = $filterCaller(null);

        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $result);
        $this->assertEquals(site_url('login'), $result->getHeaderLine('Location'));
    }
    
    public function testBeforeAllowsAccessWhenLoggedIn(): void
    {
        session()->set([
            "user_id"       => env('ADMIN_ID'),
            'isLoggedIn'    => true,
        ]);

        $this->request->getUri()->setPath('blog/my-posts');

        $filterCaller = $this->getFilterCaller('App\Filters\Auth', 'before');
        $result = $filterCaller(null);

        $this->assertNotInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $result);
    }

}