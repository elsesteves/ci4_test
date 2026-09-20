<?= $this->extend("_layouts/default") ?>

<?= $this->section("pageTitle") ?>My Posts<?php $this->endSection() ?>

<?= $this->section("pageContent") ?>

<div class="container">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 pt-3 mt-5 pb-3 bg-white form-wrapper">
            <h3>My Posts <a class="btn btn-primary pull-end" href="<?= base_url('blog/create') ?>" 
                style="float: right; background: #dd4814; border-color: #dd4814; color: #fff; font-size: 14px; font-weight: 500;"
                >New Post</a></h3>
            <hr>
            <?= $pager->links(); ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($posts) && !empty($posts)) : ?>
                        <?php foreach($posts as $post) : ?>
                        <tr>
                            <th scope="row"><?= $post['id'] ?></th>
                            <td><?= $post['title'] ?></td>
                            <td><?= date('d-M-Y', strtotime($post['created_at'])) ?></td>
                            <td>
                                <a href="<?= base_url("post/".$post['slug']) ?>">View</a>
                                <a href="<?= base_url("blog/edit/".$post['id']) ?>">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?=  $this->endSection() ?>