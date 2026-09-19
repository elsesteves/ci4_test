<?php
namespace App\Models;

use CodeIgniter\Model;

class User extends Model {
    protected $table = 'users';
    protected $allowedFields = ['firstname', 'lastname', 'email', 'password', 'created_at', 'updated_at'];

    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];


    protected function beforeInsert(array $data) {//Runs before an insert to the table
        $data = $this->passwordHash($data);

        return $data;
    }

    protected function beforeUpdate(array $data) {//Runs before an update to the table
        $data = $this->passwordHash($data);
        
        return $data;
    }

    public function getUser(int $id) {
        return $this->asArray()
                    ->where(['id' => $id])
                    ->first();
    }

    protected function passwordHash(array $data) {
        if(isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }
}