<?= $this->extend("_layouts/default") ?>

<?= $this->section("pageTitle") ?>Home<?php $this->endSection() ?>
<?= $this->section("pageContent") ?>

<div class="container pt-5 pb-3">
    <div class="p-5 mb-4 bg-body-tertiary rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">CI4 Blog</h1> 
            <p class="col-md-8 fs-4">Trying out codeigniter for building web apps</p> 
            <a class="btn btn-primary btn-lg" href="#">Check it out</a> 
        </div> 
    </div>

    <div class="row align-items-md-stretch"> 
        <div class="col-md-6"> 
            <div class="h-100 p-5 text-bg-dark rounded-3"> 
                <h2>Change the background</h2> 
                <p>Swap the background-color utility and add a `.text-*` color utility to mix up the jumbotron look. Then, mix and match with additional component themes and more.</p> 
                <button class="btn btn-outline-light" type="button">Example button</button> 
            </div> 
        </div> 
        <div class="col-md-6"> 
            <div class="h-100 p-5 bg-body-tertiary border rounded-3" 
                style="background: #6f091b !important; border-color: #930b3d !important; color: #e5e5e5;"> 
                <h2>Add borders</h2> 
                <p>Or, keep it light and add a border for some added definition to the boundaries of your content. Be sure to look under the hood at the source HTML here as we've adjusted the alignment and sizing of both column's content for equal-height.</p> 
                <button class="btn btn-outline-secondary" type="button">Example button</button> 
            </div> 
        </div> 
    </div>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-12 pt-3 mt-5 pb-3 bg-white form-wrapper">
                    <h3>Recent Posts</h3>
                    <hr>
                    <?php
                        if(isset($news['pager'])) {
                            // Display the default CI pagination from CodeIgniter
                            print $news['pager']->links();
                        }
                    ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Title</th>
                                <th scope="col">Author</th>
                                <th scope="col">Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($news['rows']) && !empty($news['rows'])) : ?>
                                <?php foreach($news['rows'] as $post) : ?>
                                <tr>
                                    <th scope="row"><?= $post['id'] ?></th>
                                    <td><?= esc($post['title']) ?></td>
                                    <td><?= esc($post['author_name']) ?></td>
                                    <td><?= date('d-M-Y', strtotime($post['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= base_url("post/".$post['slug']) ?>">View</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center"></p>
                                <tr>
                                    <td colspan="5">There are no posts yet</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
    
<?= $this->endSection() ?>