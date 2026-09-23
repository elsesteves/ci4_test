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
    protected $beforeValidate = ['validationRulesAdjust'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'title'     => 'required',
        'body'      => 'required',
        'author_id' => 'required',
    ];


    protected $validationMessages = [
        'title' => [
            'required' => 'Title is a required field',
        ],
        'body' => [
            'required' => 'The post requires a body',
        ],
        'author_id' => [
            'required' => 'The post requires an author',
        ],
    ];

    /**
     * Called during initialization. For custom initialization logic
     */
    protected function initialize()
    {
        
    }

    protected function validationRulesAdjust(array $data) {
        if (isset($data['id'])) {
            $this->validationRules['title']     = isset($data['data']['title']) ? 'required|min_length[6]|max_length[255]' : '';
            $this->validationRules['body']      = isset($data['data']['body']) ? 'required' : '';
            $this->validationRules['author_id'] = isset($data['data']['body']) ? 'required|is_natural_no_zero' : '';
        } else {
            $this->validationRules['title']     = 'required|min_length[6]';
            $this->validationRules['body']      = 'required';
            $this->validationRules['author_id'] = 'required|is_natural_no_zero';
        }

        return $data;
    }

    protected function beforeInsert(array $data) {//Runs before an insert to the table
        $data['data']['slug'] = $this->permalink($data['data']['title']);

        return $data;
    }

    protected function beforeUpdate(array $data) {//Runs before an update to the table
        helper(['form']);

        if(isset($data['data']['title']) && !empty($data['data']['title'])) {
            $data['data']['slug'] = $this->permalink($data['data']['title'], $data['id']);
        }
        
        return $data;
    }
    
    public function permalink(string $rawTitle, $id = null) {
        helper(['text', 'url']);

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

    public function getPosts(array $options = []) {
        $this->builder()
            ->select('posts.*, CONCAT(users.firstname, " ", users.lastname) AS author_name')                    
            ->join('users', 'posts.author_id = users.id', 'left');//LEFT JOIN

        if(isset($options['slug'])) {
            $row = $this->asArray()
                    ->where(['slug' => $options['slug']])
                    ->first();

            return $row;
        }

        if(isset($options['author_id'])) {
            $this->where('author_id', $options['author_id']);
        }      

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
}