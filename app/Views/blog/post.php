<?= $this->extend("_layouts/default") ?>
<?php if(isset($post) && !empty($post)) : ?>
<?= $this->section("pageTitle") ?><?= esc($post['title']) ?><?php $this->endSection() ?>
<?= $this->section("pageContent") ?>

<?php if(session()->get('success')): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <?= session()->get('success') ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php //dd($post); ?>
<section>        
    <div class="container">
        <h1><?= esc($post['title']) ?></h1>
        <div class="details" style="font-size: 14px;">
            Posted on: <?= date('d-M-Y', strtotime($post['created_at'])) ?><?php 
            if(isset($post['author_name']) && !empty($post['author_name'])) {
                print ' by '. esc($post['author_name']);
            }
            ?>
        </div>
        <div class="post">
            <?= $post['body'] ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
<?php endif; ?>