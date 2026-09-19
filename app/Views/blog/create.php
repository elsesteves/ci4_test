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

            <?php
                if(!isset($route) || empty($route)) {
                    $route = 'blog/create';
                }
            ?>
            <?= form_open($route, ['id' => 'blogForm', 'onsubmit' => 'return saveBlogPost()']) ?>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?= esc(set_value('title', $post['title'] ?? '')) ?>">
                </div>

                <div class="form-group">
                    <label for="editor">Body</label>
                    <input type="hidden" name="body" id="body" value="<?= set_value('body') ?>">
                    <div id="editor" style="min-height: 320px;"><?= $post['body'] ?? '' ?></div>
                </div>

                <div class="form-group mt-2 text-end">
                    <button class="btn btn-primary" type="submit">Create</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<?=  $this->endSection() ?>

<?= $this->section("pageStyles") ?>
<!-- Include stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
<?=  $this->endSection() ?>

<?= $this->section("pageScripts") ?>
<!-- Include the Quill library -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

<!-- Initialize Quill editor -->
<script>
  const quill = new Quill('#editor', {
    theme: 'snow'
  });
</script>

<script>
    function saveBlogPost() {
        // Obter o HTML gerado pelo Quill
        // Nota: quill.getSemanticHTML() é o método oficial e mais limpo no Quill v2
        const html = quill.getSemanticHTML();

        console.log('blog post', html);
        body.value = html;
        
        // Atribuir o HTML ao nosso input escondido
        //document.getElementById('blogContent').value = html;

        return true;
    }
</script>
<?=  $this->endSection() ?>