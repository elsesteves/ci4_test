<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{

    protected $modelName = 'App\Models\User';//model gets linked automatically
    protected $format    = 'json';

    /**
     * Gets the data from signed user profile
     */
    public function profile()
    {
        // Captura o ID do utilizador que o Filtro JWT validou e injetou na request
        $userId = $this->request->user_id;//request is a shared instance

        // Procura APENAS os dados desse utilizador específico
        $user = $this->model->find($userId);

        if (!$user) {
            return $this->failNotFound('User not found', 401);
        }

        // Remove dados sensíveis antes de enviar o JSON de resposta
        unset($user['password']);

        return $this->respond($user, 200);
    }
}
