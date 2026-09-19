<?= $this->extend("_layouts/default") ?>

<?= $this->section("pageTitle") ?><?= lang('Errors.pageNotFound') ?><?= $this->endSection() ?>
<?= $this->section("pageContent") ?>

    <style>
        
    </style>

    <div class="wrap">
        <h1>404</h1>

        <p>
            <?= lang('Errors.sorryCannotFind') ?>
        </p>
    </div>

<?= $this->endSection() ?>