<?= $this->extend("_layouts/default") ?>

<?= $this->section("pageTitle") ?>Login<?php $this->endSection() ?>
<?= $this->section("pageContent") ?>

<div class="container">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 pt-3 mt-5 pb-3 bg-white form-wrapper">
            <h3>Login</h3>
            <hr>
            <?php if(session()->get('success')): ?>
                <div class="alert alert-success" role="alert">
                    <?= session()->get('success') ?>
                </div>
            <?php endif; ?>
            <?php if(isset($validation)) : ?>
                <div class="col-12">
                    <div class="alert alert-danger" role="alert">
                        <?= $validation->listErrors() ?>
                    </div>
                </div>
            <?php endif; ?>
            <form class="" action="<?= base_url('login') ?>" method="POST">
                <div class="form-group">
                    <label for="email">Email address</label>                        
                    <input type="text" class="form-control" name="email" id="email" value="<?=  set_value('email') ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>                        
                    <input type="password" class="form-control" name="password" id="password" value="">
                </div>
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <button class="btn btn-primary" type="submit">Login</button>
                    </div>
                    <div class="col-12 col-sm-8 text-end">
                        <a href="<?= base_url('register') ?>">Don't have an account yet?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>