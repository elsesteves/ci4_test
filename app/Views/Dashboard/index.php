<?= $this->extend("_layouts/default") ?>

<?= $this->section("pageTitle") ?>Dashboard<?php $this->endSection() ?>
<?= $this->section("pageContent") ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Hello, <?= esc(session()->get('firstname')) ?></h1>
        </div>
    </div>
</div>

<?php $this->endSection() ?>