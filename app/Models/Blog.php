<?php
namespace App\Models;

use CodeIgniter\Model;

/**
* Model used to manage blog posts
* Further model instructions on https://codeigniter.com/user_guide/models/model.html
**/
class Blog extends Model {
    protected $table = 'posts';
    protected $allowedFields = ['title', 'body', 'slug', 'author_id'];

    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Called during initialization. For custom initialization logic
     */
    protected function initialize()
    {
        
    }

    protected function beforeInsert(array $data) {//Runs before an insert to the table

        return $data;
    }

    protected function beforeUpdate(array $data) {//Runs before an update to the table
        
        return $data;
    }
    
    public function permalink(string $rawTitle, $id = null) {
        $cleanTitle = convert_accented_characters($rawTitle); 
        $permalink = url_title($cleanTitle, '-', true);

        //Ensure uniqueness
        $link = $permalink;
        $unique = false;
        $i = 0;
        while(!$unique) {
            $query  = $this->where('slug', $link);

            if(!empty($id)) {
                $query->where('id !=', $id);
            }
                        
            $posts = $query->findAll();

            if(empty($posts)) {
                $unique = true;
            } else {
                $i++;
                $link = $permalink.'-'.$i;
            }
        }

        return $link;
    }

    public function getPosts($slug = null) {
        $this->builder()
            ->select('posts.*, CONCAT(users.firstname, " ", users.lastname) AS author_name')                    
            ->join('users', 'posts.author_id = users.id', 'left');//LEFT JOIN

        $authorModel = new User();

        if(empty($slug)) {
            $pager = service('pager');
            $output = [];
            /*
                * instead of findAll() paginate provides the page results, without the need to adjust 
                limit, offset and counting all the actual results to compute page limits through ceil(totalRes/pageMax)
            */
            $rows = $this->orderBy('id', 'DESC')
                            ->paginate(5);
            
            $output['pager'] = $this->pager;
            $output['rows'] = $rows;

            return $output;
        }

        $row = $this->asArray()
                    ->where(['slug' => $slug])
                    ->first();

        return $row;
    }
}