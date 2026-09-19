    <?php
        $uri = service('uri');
    ?>

    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url() ?>">
            <img src="<?= base_url("assets/img/ci.svg") ?>" style="margin: 8px; max-width: 20px; max-height: 20px;"> CI4 Test</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Project
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= base_url('') ?>">Home</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= base_url('about') ?>">About Us</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Blog
                </a>
                <ul class="dropdown-menu">
                    <?php if(session()->get('isLoggedIn')) : ?>
                    <li><a class="dropdown-item" href="<?= base_url('blog/create') ?>">Create Blog Post</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item" href="<?= base_url('about') ?>">About Us</a></li>
                </ul>
            </li>
            <?php if(session()->get('isLoggedIn')) : ?>
            <li class="nav-item">
                <a class="nav-link <?= ($uri->getSegment(1)) == 'dashboard' ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">Dashboard</a>
            </li>
            <?php endif; ?>
            <!--
            <li class="nav-item">
            <a class="nav-link disabled" aria-disabled="true">Disabled</a>
            </li>
            -->
        </ul>
        <!--
        <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
        -->
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <?php if(session()->get('isLoggedIn')) : ?>
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?= esc(session()->get("firstname")) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="nav-item">
                        <a class="nav-link <?= ($uri->getSegment(1)) == 'profile' ? 'active' : '' ?>" href="<?= base_url('profile') ?>">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($uri->getSegment(1)) == 'logout' ? 'active' : '' ?>" href="<?= base_url('logout') ?>">Logout</a>
                    </li>
                </ul>
            </li>
            <?php else: ?>
            <li class="nav-item">
                <a class="nav-link <?= in_array($uri->getSegment(1), ['login']) ? 'active' : '' ?>" aria-current="page" href="<?= base_url('login') ?>">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $uri->getSegment(1) == 'register' ? 'active' : '' ?>" href="<?= base_url('register') ?>">Register</a>
            </li>
            <?php endif; ?>
        </ul>
        </div>
    </div>
    </nav>

