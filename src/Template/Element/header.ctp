<?php
    $here = $this->request->getAttribute("here");
    $myPic = $this->System->getUserPic($myId);
    $myFullName = $this->System->getFullNameById($myId);
    $email = $this->request->getSession()->read('Auth.User.email');
?>
<header class="header-desktop">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="header-wrap">
                <div class="search">
                  <?php
                  
                    echo $this->Form->control('search', array(
                      'href' => $this->Url->build('/users/search'),
                      'label' => false,
                      'class' => 'form-control ml-sm-2',
                      'type' => 'search',
                      'placeholder' => 'Search...',
                      'required' => true,
                      'aria-label' => 'Search'
                    ));
                  ?>
                </div>
                <div class="form-header">
                </div>
                <div class="header-button">
                    <div id="date" class="col">
                    </div>
                    <div id="time" class="col" style="width: 150px;"></div>
                    <div class="noti-wrap">
                        <div class="noti__item js-item-menu">
                            <div class="notifi-dropdown js-dropdown">
                                <div class="notifi__item">
                                    <div class="bg-c2 img-cir img-40">
                                        <i class="zmdi zmdi-account-box"></i>
                                    </div>
                                </div>
                                <div class="notifi__item">
                                    <div class="bg-c3 img-cir img-40">
                                        <i class="zmdi zmdi-file-text"></i>
                                    </div>
                                </div>
                                <div class="notifi__footer">
                                    <a href="#">All notifications</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="account-wrap">
                        <div class="account-item clearfix js-item-menu">
                            <div class="content">
                                <a class="js-acc-btn" href="#"><?= $myFullName?></a>
                            </div>
                            <div class="account-dropdown js-dropdown">
                                <div class="info clearfix">
                                    <div class="image">
                                    <?php $myPic = ($here == "/users/changePassword/id:$myId" ? "/".$myPic : $myPic) ?>
                                        <img src="<?=$myPic?>"/>
                                    </div>
                                    <div class="content">
                                        <h5 class="name">
                                            <a href="#"><?= $myFullName?></a>
                                        </h5>
                                        <span class="email"><?= $email ?></span>
                                    </div>
                                </div>
                                <div class="account-dropdown__body">
                                    <div class="account-dropdown__item">
                                        <a href="<?= $this->Url->build(['controller' => 'users', 'action' => 'changePassword'])?>">
                                            <i class="fas fa-key"></i> Change Password
                                        </a>
                                        <a href="<?= $this->Url->build('/users/logout')?>">
                                            <i class="zmdi zmdi-power"></i>Logout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>