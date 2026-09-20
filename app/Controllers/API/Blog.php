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

}
