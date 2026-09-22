<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\Services;

class APIAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authenticationHeader  = $request->getServer('HTTP_AUTHORIZATION');

        if (empty($authenticationHeader)) {
        }

        // Try to validate a bearer token as JWT
        if (!empty($authenticationHeader)) {
            $jwt = new \App\Libraries\JWT();
            $decoded = $jwt->validate($authenticationHeader);

            if (!$decoded) {
                return Services::response()
                    ->setJSON(['error' => 'invalid or expired token'])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }

            if($decoded->iss != base_url()) {//issuer differs from us
                return Services::response()
                    ->setJSON(['error' => 'invalid or expired token'])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }
            
            // Inject the user id in request
            $request->user_id = $decoded->uid;
            return;
        }


        // Fallback to native session check
        if (session()->get('isLoggedIn')) {
            //If is using native session check for CSRF token, when method isn't GET
            if($request->getMethod() != 'GET') {
                $csrf = new \CodeIgniter\Filters\CSRF();
                $csrfOutput = $csrf->before($request, $arguments);
                if(!is_null($csrfOutput)) {
                    return $csrfOutput;
                }
            }

            // Inject the user id from session into request
            $request->user_id = session()->get('id'); 
            return;
        }

        
        return Services::response()
            ->setJSON(['error' => 'Missing authentication token'])
            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //No need to implement nothing here
    }
}
