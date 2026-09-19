<?php

namespace App\Controllers;


class Blog extends BaseController
{
    public function post(string $slug) {
        $model = new \App\Models\Blog();
        $data['post'] = $model->getPosts($slug);

        return view('blog/post', $data);
    }

    public function myPosts() {
        $model = new \App\Models\Blog();
        $data['posts'] = $model->where('author_id', session()->get('id'))->findAll();

        return view('blog/authed', $data);
    }

    public function edit(int $id) {
        helper(['form', 'text']);
        $data = [];

        $model = new \App\Models\Blog();
        $post = $model->where([
            'id' => $id,
            'author_id' => session()->get('id')
        ])->first();

        if(!empty($post)) {
            $data['post'] = $post;
            $data['route'] = "blog/edit/".$id;
        }

        if($this->request->getMethod() == 'POST') {
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
                $permalink = $model->permalink($rawTitle, $id);

                $rawBody = $this->request->getPost('body');
                $sanitizer = new \App\Libraries\HtmlSanitizer();
                $cleanBody = $sanitizer->purify($rawBody);

                $sendData = [
                    "id" => $id,
                    "title" => filter_var($rawTitle, FILTER_SANITIZE_SPECIAL_CHARS),
                    "body" => $cleanBody,
                    "slug" => filter_var($permalink, FILTER_SANITIZE_SPECIAL_CHARS),
                ];

                $model->save($sendData);
                session()->setFlashdata("success", 'Post Successfully Updated');

                return redirect()->to($permalink);//Show submitted article
            }
        }

        return view('blog/create', $data);
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
                $permalink = $model->permalink($rawTitle);

                $rawBody = $this->request->getPost('body');
                $sanitizer = new \App\Libraries\HtmlSanitizer();
                $cleanBody = $sanitizer->purify($rawBody);

                $sendData = [
                    "title" => filter_var($rawTitle, FILTER_SANITIZE_SPECIAL_CHARS),
                    "body" => $cleanBody,
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
