<?php

namespace App\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class BlogTest extends CIUnitTestCase
{
    // essential trait for testing DB
    use DatabaseTestTrait;

    protected $migrate = true;//migrations run before tests
    // protected $seed = 'TestPostSeeder';//seeder to prepopulate data
    protected $refresh = true;//Ensures DB and services refresh between tests

    private Blog $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new Blog();
    }

    public function testInsertPostSuccess(): void
    {
        $data = [
            'title'     => 'Post Dummy Title',
            'body'      => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'author_id' => env('ADMIN_ID'),
        ];

        $id = $this->model->insert($data);

        $this->assertIsNumeric($id);
        
        //CI4 assertion: check if record exists in DB
        $this->seeInDatabase('posts', [
            'id'    => $id,
            'title' => 'Post Dummy Title'
        ]);
    }

    public function testInsertPostFailsNoTitle(): void
    {
        $data = [
            'body'      => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'author_id' => env('ADMIN_ID')
        ];

        $result = $this->model->insert($data);

        $this->assertFalse($result);

        $errors = $this->model->errors();
        $this->assertArrayHasKey('title', $errors);
        $this->assertStringContainsString("Title is a required field", $errors['title']);
    }

    public function testInsertPostFailsTitleTooShort(): void
    {
        $data = [
            'title' => 'dhn',
            'body'      => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'author_id' => env('ADMIN_ID')
        ];

        $result = $this->model->insert($data);

        $this->assertFalse($result);

        $errors = $this->model->errors();
        $this->assertArrayHasKey('title', $errors);
    }

    public function testInsertPostFailsNoAuthor(): void
    {
        $data = [
            'title' => 'Lorem Ipsum',
            'body'      => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        ];

        $result = $this->model->insert($data);

        $this->assertFalse($result);

        $errors = $this->model->errors();
        $this->assertArrayHasKey('title', $errors);
        $this->assertStringContainsString("Title is a required field", $errors['title']);
    }


    public function testGetPublishedPostsByUser(): void
    {
        // Prepara massa de dados
        $this->model->insert([
            'title' => 'Test 01', 'body' => '...', 'author_id' => env('ADMIN_ID')
        ]);

        // Executa método customizado do teu model
        $posts = $this->model->getPosts(["author_id" => env('ADMIN_ID')]);

        $lastPost = $posts['rows'][0];//ORDER BY id DESC

        $this->assertEquals('Test 01', $lastPost['title']);
    }

}