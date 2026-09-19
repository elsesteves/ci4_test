<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;

class Blog extends ResourceController
{

    protected $modelName = 'App\Models\Blog';//model gets linked automatically
    protected $format    = 'json';

    // GET /api/blog (Listar todos os posts)
    /**
     * List all blog posts
     */
    public function index()
    {
        $posts = $this->model->findAll();
        
        // Retorna status 200 OK com o JSON dos posts
        return $this->respond($posts, 200);
    }

    // GET /api/blog/{id} (Mostrar um post específico)
    public function show($id = null)
    {
        $post = $this->model->find($id);

        if (!$post) {
            return $this->failNotFound('Post não encontrado.');
        }

        return $this->respond($post, 200);
    }

    // POST /api/blog (Criar um novo post)
    public function create()
    {
        // Obtém os dados do corpo da requisição JSON ou POST nativo
        $data = $this->request->getPost() ?? $this->request->getJSON(true);

        if (!$this->model->insert($data)) {
            // Retorna os erros de validação do Model automaticamente
            return $this->failValidationErrors($this->model->errors());
        }

        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Post criado com sucesso!',
            'id'      => $this->model->getInsertID()
        ]);
    }
}
