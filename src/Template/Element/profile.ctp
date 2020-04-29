<?php if(!empty($profile)):?>
    <?php
        $id = $myId;
        $userId = $profile->id;
        $myProfile = $userId === $id ? true : false;
        $editPicHref = $this->Url->build(['controller' => 'users', 'action' => 'editPicture']);
        $picClass = $myProfile ? 'update_picture' : '';
        $editPicHref = $myProfile ? "href='".$editPicHref."'" : '';
        
        $joined = $profile->joined;
        $myPic = $profile->profile_image;
        $myFullName = $profile->full_name;
    ?>
    <div class="container-fluid border mt-3">
        <div class="row">
            <div class="wrapper col-sm-3 user-profile-pic">
                <img type='button' src='<?=$myPic?>'class="mx-auto p-2">
                <?php if($myProfile):?>
                <div class="overlay">
                    <a href="#" class="icon" title='Change Picture'>
                        <i class="<?=$picClass?> fas fa-camera" <?=$editPicHref?>></i>
                    </a>
                </div>
                <?php endif;?>
            </div>
            <div class="col-sm-9 user-profile-details">
                <?php
                    if($myProfile){
                        $button = "<div class='follow-button col-sm-12 mt-3'>
                                        <button href='".$this->Url->build(['controller' => 'users', 'action' => 'edit'])."' type='button' class='edit_profile btn-sm btn-outline-primary'>Edit profile</button>
                                </div>";
                    } else {
                        $isFollowing = $this->System->isFollowing($id, $userId);
                        $hadFollowed = $this->System->hadFollowed($id, $userId);
                        $hrefAction = $hadFollowed ? 'unfollow' : 'follow';
                        $btnTitle = $isFollowing ? 'Unfollow' : 'Follow';
                        $btnClass = $isFollowing ? 'unfollow_user btn-outline-danger' : 'follow_user btn-outline-primary';
                        
                        $button = "<div class='follow-button col-sm-12 mt-3'>
                                        <button href='".$this->Url->build(['controller' => 'users', 'action' => $hrefAction, $userId])."' type='button' class='".$btnClass." btn-sm'>".$btnTitle."</button>
                                    </div>";
                    }
                    echo $button;
                ?>    
                <div class="row">
                    <div class="col-sm-12 profile-fullname">
                        <h3><?=$myFullName?></h3>
                    </div>
                    <div class="col-sm-12 row m-2">
                        <div class="date-joined m-2">
                            <h5 class="text-secondary"><i class="far fa-calendar-alt"></i> Joined <?= $joined ?></h5>
                        </div>
                        <div class="email m-2">
                            <h5 class="text-secondary"><i class="fas fa-at"></i> <?= $profile->email ?></h5>
                        </div>
                    </div>
                    <div class="col-sm-12 row">
                        <div class="following p-2">
                            <button href='<?=$this->Url->build(['controller' => 'users', 'action' => 'following', 'user_id' => $userId])?>' class="get_follow btn-sm btn-outline-primary">Following</button>
                        </div>
                        <div class="followers ml-5 p-2">
                            <button href='<?=$this->Url->build(['controller' => 'users', 'action' => 'following', 'following_id' => $userId])?>' class="get_follow btn-sm btn-outline-primary">Followers</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>