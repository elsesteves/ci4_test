<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class UsersCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        //If it's a users page, redirect to the second segment (/users/profile -> /profile)
        $uri = service('uri');
        if(in_array($uri->getSegment(1), ["users"])) {
            $segment = $uri->getSegment(2);
            if($segment == '') {
                $segment = '/';
            }
            return redirect()->to($segment);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}