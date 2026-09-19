<?php

namespace App\Controllers;

use App\Models\Blog as BlogModel;
class Pages extends BaseController
{
    public function index() {
        $blogModel = new BlogModel();
        $data['news'] = $blogModel->getPosts();

        return view('pages/home', $data);
    }


    public function show($page = 'home') {
        $data = [];
        $blogModel = new BlogModel();

        //Check if page exists
        $locator = service('locator');
        $viewName = 'pages/'.$page;

        // Locate the file inside the "Views" directory context
        $viewPath = $locator->locateFile($viewName, 'Views', 'php');        

        if ($viewPath) {
            if($page == 'home') {
                $data['news'] = $blogModel->getPosts();
            }

            //The view exist
            return view($viewName, $data);
        }
        
        //Try a blog post as fallback
        $post = $blogModel->getPosts($page);
        if(!empty($post)) {
            $data['post'] = $post;
            return view('blog/post', $data);
        }        
        
        // Throw a 404
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        
    }
}