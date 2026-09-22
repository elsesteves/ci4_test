<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class Auth extends ResourceController
{
    protected $format = 'json';

    public function login()
    {
        //Receive request data (JSON and POST form fields supported)
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $email    = $this->request->getVar('email');
        $password = $this->request->getVar('password');


        $userModel = new \App\Models\User();
        $user      = $userModel->where('email', $email)->first();

        // 3. Validar o utilizador e a password (usando password_verify do PHP)
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->failUnauthorized('Invalid Credentials. Try again.');
        }

        $payload = [
            'iss' => base_url(), // identifier of the issuer
            'aud' => 'api', // identifier of destination/audience
            'uid' => $user['id'], // user id
        ];

        $jwt = new \App\Libraries\JWT();
        $token = $jwt->generate($payload);

        //send generated token
        return $this->respond([
            'status'  => 200,
            'message' => 'Successfully authenticaded',
            'token'   => $token,
            'expires_in' => 3600
        ]);
    }
}
