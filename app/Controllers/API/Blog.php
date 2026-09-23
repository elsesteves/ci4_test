<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;

class Blog extends ResourceController
{

    protected $modelName = 'App\Models\Blog';//model gets linked automatically
    protected $format    = 'json';

    // GET /api/blog
    /**
     * List all blog posts
     */
    public function index()
    {
        $data['posts'] = $this->model
                    ->select('posts.*, CONCAT(users.firstname, " ", users.lastname) AS author_name')                    
                    ->join('users', 'posts.author_id = users.id', 'left')//LEFT JOIN
                    ->orderBy('id', 'DESC')
                    ->paginate(5);

        $pager = $this->model->pager;

        $data['pagination'] = [
            "results_total" => $pager->getTotal(),
            "pages_total" => $pager->getPageCount(),
        ];
        
        // Retorna status 200 OK com o JSON dos posts
        return $this->respond($data, 200);
    }

    // GET /api/blog/{id}
    /**
     * Shows a post with the provided id
     */
    public function show($id = null)
    {
        $post = $this->model->find($id);

        if (!$post) {
            return $this->failNotFound('Post não encontrado.');
        }

        return $this->respond($post, 200);
    }


    /**
     * Create a new post
     */
    public function create()
    {    
        $errors = [];

        helper(['form', 'text']);

        $author_id = $this->request->user_id;

        $data = [
            "title" => $this->request->getVar('title'),
            "body" => $this->request->getVar('body'),
            "author_id" => $author_id,
        ];

        $rules = [
            "title" => 'required|min_length[6]|max_length[255]|trim',
            "body" => 'required|trim',
        ];

        if(!$this->validate($rules)) {//Validates the request against the provided set of rules
            // failed validation
            $errors = $this->validator->getErrors();
        }

        if(!empty($errors)) {
            $output = [
                "status" => 400,
                "errors" => $errors,
            ];
            return $this->respond($output, 400);
        }

        $data['title'] = filter_var($data['title'], FILTER_SANITIZE_SPECIAL_CHARS);
        
        $sanitizer = new \App\Libraries\HtmlSanitizer();
        $data['body'] = $sanitizer->purify($data['body']);

        $id = $this->model->insert($data, true);
        $post = $this->model->find($id);

        return $this->respond($post, 200);
    }


    public function edit($id = null)
    {        
        $errors = [];

        helper(['form', 'text']);

        $author_id = $this->request->user_id;

        $post = $this->model->where([
            'id' => $id,
            'author_id' => $author_id
        ])->first();

        if(empty($post)) {
            $errors['unauthorized'] = 'The author selected post does not match the user';

            $output = [
                "status" => 401,
                "errors" => $errors,
            ];
            return $this->respond($output, 401);
        }

        $data = [
            "title" => $this->request->getVar('title'),
            "body" => $this->request->getVar('body'),
            "author_id" => $author_id,
        ];


        $rules = [
            "title" => 'required|min_length[6]|max_length[255]|trim',
            "body" => 'required|trim',
        ];

        if(!$this->validate($rules)) {//Validates the request against the provided set of rules
            // failed validation
            $errors = $this->validator->getErrors();
        }

        if(!empty($errors)) {
            $output = [
                "status" => 400,
                "errors" => $errors,
            ];
            return $this->respond($output, 400);
        }

        $data['title'] = filter_var($data['title'], FILTER_SANITIZE_SPECIAL_CHARS);
        
        $sanitizer = new \App\Libraries\HtmlSanitizer();
        $data['body'] = $sanitizer->purify($data['body']);

        $data['id'] = $id;
        $this->model->save($data);

        $post = $this->model->find($id);

        return $this->respond($post, 200);
    }

}
