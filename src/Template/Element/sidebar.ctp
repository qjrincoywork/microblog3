<aside class="menu-sidebar d-none d-lg-block border">
    <div class="menu-sidebar__content js-scrollbar1">
        <div class="image microblogLogo">
            <a href="<?= $this->Url->build('/users/home')?>">
                <img src='<?=$systemLogo?>'/>
            </a>
        </div>
        <nav class="navbar-sidebar" id="navbar-sidebar">
            <ul class="list-unstyled navbar__list">
                <li <?= $this->request->getAttribute("here") == '/users/home' ? 'class="active"' : '' ?>>
                    <a href="<?= $this->Url->build(['controller'=>'users', 'action'=>'home'])?>">
                        <i class="fas fa-home"></i> Home </a>
                </li>
                <li <?= $this->request->getAttribute("here") == "/users/profile/$myId" ? 'class="active"' : '' ?>>
                    <a class="js-arrow" href="<?= $this->Url->build(['controller'=>'users',
                                                                    'action'=>'profile', $myId])?>">
                        <i class="fas fa-address-card"></i> Profile </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>