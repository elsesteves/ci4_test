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
            <?php if(isset($news) && !empty($news)) : ?>
                <?php foreach($news as $newsItem) : ?>
                    <h3><a href="<?= base_url(["post", $newsItem['slug']]) ?>"><?= $newsItem['title'] ?></a></h3>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">There are no posts yet</p>
            <?php endif; ?>
        </div>
    </section>
</div>
    
<?= $this->endSection() ?>