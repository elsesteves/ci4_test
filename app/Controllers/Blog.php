<?php

namespace App\Controllers;


class Blog extends BaseController
{
    public function post(string $slug) {
        $model = new \App\Models\Blog();
        $data['post'] = $model->getPosts($slug);

        return view('blog/post', $data);
    }

    public function create() {
        helper(['form', 'text']);

        $model = new \App\Models\Blog();

        if($this->request->getMethod() == 'POST') {
            //form submission has happened
            $rules = [
                "title" => 'required|min_length[6]|max_length[255]|trim',
                "body" => 'required|trim',
            ];

            if(!$this->validate($rules)) {//Validates the request against the provided set of rules
                // failed validation
                $data['validation'] = $this->validator;
                return view('blog/create', $data);
            } else {
                $rawTitle = $this->request->getPost('title');
                $cleanTitle = convert_accented_characters($rawTitle); 
                $permalink = url_title($cleanTitle, '-', true);

                $sendData = [
                    "title" => filter_var($rawTitle, FILTER_SANITIZE_SPECIAL_CHARS),
                    "body" => filter_var($this->request->getPost('body'), FILTER_SANITIZE_SPECIAL_CHARS),
                    "slug" => filter_var($permalink, FILTER_SANITIZE_SPECIAL_CHARS),                    
                    "author_id" => session()->get('id'),
                ];

                $model->save($sendData);
                session()->setFlashdata("success", 'Post Successfully Created');

                return redirect()->to($permalink);//Show submitted article
            }
        }

        return view('blog/create');
    }
}
