<?= $this->extend("_layouts/default") ?>

<?= $this->section("pageTitle") ?>Create Post<?php $this->endSection() ?>
<?= $this->section("pageContent") ?>

<div class="container">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 pt-3 mt-5 pb-3 bg-white form-wrapper">
            <h3>Create a new post</h3>
            <hr>
            <?php if(isset($validation)) : ?>
                <div class="col-12">
                    <div class="alert alert-danger" role="alert">
                        <?= $validation->listErrors() ?>
                    </div>
                </div>
            <?php endif; ?>

            <?= form_open('blog/create') ?>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?= set_value('title') ?>">
                </div>

                <div class="form-group">
                    <label for="body">Body</label>
                    <textarea class="form-control" name="body" id="body" rows="12"><?= set_value('body') ?></textarea>
                </div>

                <div class="form-group mt-2 text-end">
                    <button class="btn btn-primary" type="submit">Create</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<?=  $this->endSection() ?>