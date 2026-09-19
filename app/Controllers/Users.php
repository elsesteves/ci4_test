<?php

namespace App\Controllers;

class Users extends BaseController
{
    public function index()
    {
        $data = [];
        helper(['form']);

        if($this->request->getMethod() == 'POST') {//Gets HTTP method
            //form submission has happened
            //data validation
            $rules = [                
                "email" => 'required|min_length[6]|max_length[50]|valid_email|trim',
                "password" => 'required|min_length[8]|max_length[255]|validateUser[email, password]|trim',
            ];

            $errors = [
                "password" => [
                    "validateUser" => "The email password pair didn't match",
                ],
            ];

            if(!$this->validate($rules, $errors)) {
                $data['validation'] = $this->validator;
            } else {
                $model = new \App\Models\User();

                $user = $model->where('email', $this->request->getVar('email'))
                                ->first();

                $this->setUserSession($user);
                return redirect()->to('/dashboard');//Redirect to dashboard
            }
        }

        return view('Users/login', $data);
    }

    private function setUserSession(array $user) {
        $data = [
            "id" => $user['id'],
            "firstname" => $user['firstname'],
            "lastname" => $user['lastname'],
            "email" => $user['email'],
            "isLoggedIn" => true,
            "loggedAt" => date('YmdHis'),
        ];

        session()->set($data);
    }

    public function register()
    {
        $data = [];
        helper(['form']);

        if($this->request->getMethod() == 'POST') {//Gets HTTP method
            //form submission has happened
            //data validation
            $rules = [
                "firstname" => 'required|min_length[3]|max_length[50]|trim',
                "lastname" => 'required|min_length[3]|max_length[50]|trim',
                "email" => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]|trim',
                "password" => 'required|min_length[8]|max_length[255]|trim',
                "password_confirm" => 'matches[password]|trim',
            ];

            if(!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                //store user in DB
                $model = new \App\Models\User();

                $newData = [
                    "firstname" => filter_var($this->request->getVar('firstname'), FILTER_SANITIZE_SPECIAL_CHARS),
                    "lastname" => filter_var($this->request->getVar('lastname'), FILTER_SANITIZE_SPECIAL_CHARS),
                    "email" => filter_var($this->request->getVar('email'), FILTER_SANITIZE_SPECIAL_CHARS),
                    "password" => filter_var($this->request->getVar('password'), FILTER_SANITIZE_SPECIAL_CHARS),
                ];
                $model->save($newData);

                $session = session();
                $session->setFlashdata("success", 'Successful Registration');

                return redirect()->to('/login');//Redirect to login
            }
        }

        return view('Users/register', $data);
    }

    public function profile() {
        $data = [];
        helper(['form']);
        
        $model = new \App\Models\User();

        $data['user'] = $model->where('id', session()->get('id'))->first();

        if($this->request->getMethod() == 'POST') {//Gets HTTP method
            //form submission has happened
            //data validation
            $rules = [
                "firstname" => 'required|min_length[3]|max_length[50]|trim',//trim() for sanitization
                "lastname" => 'required|min_length[3]|max_length[50]|trim',
            ];

            $newData = [
                "id" => session()->get('id'),
                "firstname" => filter_var($this->request->getVar('firstname'), FILTER_SANITIZE_SPECIAL_CHARS),
                "lastname" => filter_var($this->request->getVar('lastname'), FILTER_SANITIZE_SPECIAL_CHARS),
            ];

            if($this->request->getPost('password')) {
                //Password change is optional
                $rules = array_merge($rules, [
                    "password" => 'required|min_length[8]|max_length[255]|trim',
                    "password_confirm" => 'matches[password]|trim',
                ]);

                $newData["password"] = filter_var($this->request->getPost('password'), FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                //update user in DB - updates because it checks for the primary key field
                $model->save($newData);
                session()->setFlashdata("success", 'Successful Update');

                return redirect()->to('/profile');//Redirect to itself
            }
        }

        return view('Users/profile', $data);
    }

    public function logout() {
        session()->destroy();

        return redirect()->to('/');
    }

}
