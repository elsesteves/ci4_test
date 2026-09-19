<?= $this->extend("_layouts/default") ?>

<?= $this->section("pageTitle") ?>User Profile<?php $this->endSection() ?>
<?= $this->section("pageContent") ?>

<div class="container">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 pt-3 mt-5 pb-3 bg-white form-wrapper">
            <h3>Profile</h3>
            <hr>
            <?php if(session()->get('success')): ?>
                <div class="alert alert-success" role="alert">
                    <?= session()->get('success') ?>
                </div>
            <?php endif; ?>
            <form class="" action="<?= base_url('profile') ?>" method="POST">
                <?= csrf_field() ?> 
                <div class="row">
                    <div class="col-12 col-sm-6 form-group">
                        <label for="firstname">First Name</label>                        
                        <input type="text" class="form-control" name="firstname" id="firstname" value="<?= esc(set_value('firstname', $user['firstname'] ?? '')) ?>">
                    </div>
                    
                    <div class="col-12 col-sm-6 form-group">
                        <label for="lastname">Last Name</label>                        
                        <input type="text" class="form-control" name="lastname" id="lastname" value="<?= esc(set_value('lastname', $user['lastname'] ?? '')) ?>">
                    </div>
                
                    <div class="form-group">
                        <label for="email">Email address</label>                        
                        <input type="text" class="form-control" name="email" id="email" value="<?= $user['email'] ?? '' ?>" disabled>
                    </div>
                    <div class="col-12 col-sm-6 form-group">
                        <label for="password">Password</label>                        
                        <input type="password" class="form-control" name="password" id="password" value="">
                    </div>
                    <div class="col-12 col-sm-6 form-group">
                        <label for="password_confirm">Confirm Password</label>                        
                        <input type="password" class="form-control" name="password_confirm" id="password_confirm" value="">
                    </div>

                    <?php if(isset($validation)) : ?>
                    <div class="col-12 mt-2">
                        <div class="alert alert-danger" role="alert">
                            <?= $validation->listErrors() ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="row mt-2">
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>