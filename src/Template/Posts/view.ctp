<?php if(empty($data)):?>
    <span class='container'><h2>Post Deleted</h2></span>
<?php else:?>
<?php
    $content = $data->content;
    $postId = $data->id;
    $postAgo = $data->post_ago;
    $sharePic = $data->user->profile_image;
    $fullName = $data->user->full_name;
    $myPost = $data->user_id === $myId ? true : false;
    
    $likedBefore = $this->System->likedBefore($postId, $myId, 'Likes');
    $likeHrefAction = $likedBefore ? 'delete' : 'add';

    $isLiked = $this->System->postReaction($postId, $myId, 'Likes');
    $isCommented = $this->System->postReaction($postId, $myId, 'Comments');
    $isShared = $this->System->postReaction($postId, $myId, 'Posts');
    
    $shareCount = $this->System->reactionCount($postId, 'Posts');
    $commentCount = $this->System->reactionCount($postId, 'Comments');
    $likeCount = $this->System->reactionCount($postId, 'Likes');

    $classListener = $data->deleted ? 'restore_post fas fa-recycle' : 'delete_post fa fa-trash';
    $title = $data->deleted ? 'Restore' : 'Delete';
?>
<div class='container border mt-5'>
    <div class='row p-3'>
        <div class="col-sm-2 p-2">
            <img src='<?=$sharePic;?>'>
        </div>
        <div class="post-details col-sm-10">
            <div class="row">
                <?php if($myPost):?>
                <div class='system-action-buttons col-sm-12'>
                    <button class='ml-2'>
                        <span href='<?=$this->Url->build(['controller' => 'posts', 'action' => 'edit', $postId])?>' class='edit_post fa fa-edit float-right' data-toggle='tooltip' data-placement='top' title='Edit' type='button'></span> 
                    </button>
                    <button class=''>
                        <span href='<?=$this->Url->build(['controller' => 'posts', 'action' => 'delete', $postId])?>' class='<?=$classListener?> float-right' data-toggle='tooltip' data-placement='top' title='<?=$title?>' type='button'></span> 
                    </button>
                </div>
                <?php endif;?>
                <div class="post-user"><a href='<?=$this->Url->build(['controller' => 'users', 'action' => 'profile', $data->user_id])?>'>
                    <?=$fullName?>
                </a></div>
                <div class="post-ago">
                    <?=$postAgo?>
                </div>
                <div class='post-content col-sm-12'>
                    <p>
                        <?=h($content)?>
                    </p>
                </div>
                <?php if($data->image):?>
                    <div class='post-image col-sm-12 mb-2'>
                        <img src="/<?=$data->image?>">
                    </div>
                <?php endif;?>
                <?php
                if($data->post_id) {
                    $sharedPost =  $this->System->getSharedPost($data->post_id);
                    if($sharedPost) {
                        $sharedFullName =  $sharedPost->user->full_name;
                        $sharedProfile = $sharedPost->user->profile_image;
                        $sharedPostAgo = $sharedPost->post_ago;
                        $sharedContent = h($sharedPost->content);
                        
                        $sharePost = "<div class='share-post border p-3'>";
                       
                        $sharePost .= "   <div class='row'>
                                            <div class='post-img col-sm-2'>";
                        $sharePost .=     "<img src='$sharedProfile'>";
                        $sharePost .= "   </div>";
    
                        $sharePost .= "<div class='post-details col-sm-10'>
                                            <div class='row'>
                                                <div class='post-user'><a href='".$this->Url->build(['controller' => 'users', 'action' => 'profile', 'user_id' => $sharedPost->user->id])."'>"
                                                    .$sharedFullName.
                                                "</a></div>
                                                <div class='post-ago'>
                                                    $sharedPostAgo
                                                </div>
                                                <div class='post-content col-sm-12'>
                                                    <p>".$sharedContent. "<p>
                                                </div>";
                                if($sharedPost->image) {
                                    $sharePost .="<div class='sharedpost-image col-sm-12'>
                                                    <img src='/".$sharedPost->image."'>
                                                </div>";
                                }
                        $sharePost .=       "</div>
                                            </div>
                                        </div>
                                    </div>";
                    } else {
                        $sharePost = "<div class='share-post border p-2'>";
                        $sharePost .= "<span class='container'><h2>Post Deleted</h2></span>";
                        $sharePost .= "</div>";
                    }
                    
                    echo $sharePost;
                }
                ?>
            </div>
        </div>
    </div>
    <div class='post-buttons border-top'>
        <div class='row'>
            <button href='<?=$this->Url->build(['controller' => 'comments', 'action' => 'add', $postId])?>' class='comment_post col-sm-4'>
                <span class='<?= ($isCommented ? 'fas' : 'far') ?> fa-comment' data-toggle='tooltip' data-placement='top' title='Comment'> <?= (!empty($commentCount) ? $commentCount : '') ?></span>
            </button>
            <button href='<?=$this->Url->build(['controller' => 'likes', 'action' => $likeHrefAction, $postId])?>' class='like_post col-sm-4'>
                <span class='<?= ($isLiked ? 'fas' : 'far') ?> fa-heart' data-toggle='tooltip' data-placement='top' title='Like'> <?= (!empty($likeCount) ? $likeCount : '') ?></span>
            </button>
            <button href='<?=$this->Url->build(['controller' => 'posts', 'action' => 'share', $postId])?>' class='share_post col-sm-4'>
                <span class='<?= ($isShared ? 'fas' : 'far') ?> fa-share-square' data-toggle='tooltip' data-placement='top' title='Share'> <?= (!empty($shareCount) ? $shareCount : '') ?></span>
            </button>
        </div>
    </div>
</div>
<?php
    if($comments) {
        $comment = '';
        foreach ($comments as $val) {
            $myComment = $val->user_id === $myId ? true : false;
            $commenter = $val->user->full_name;
            $commenterImg = $val->user->profile_image;
            $commentAgo = $val->comment_ago;
            $commentId = $val->id;
            $commentClassListener = $val->deleted ? 'restore_comment fas fa-recycle' : 'delete_comment fa fa-trash';
            $commentTitle = $val->deleted ? 'Restore' : 'Delete';
            
            $commentButtons = '';
            
            if($myComment) {
                $commentButtons .= "<div class='system-action-buttons col-sm-12'>
                                        <button class='ml-2'>
                                            <span href='".$this->Url->build(['controller' => 'comments', 'action' => 'edit', $commentId])."' class='edit_comment fa fa-edit' data-toggle='tooltip' data-placement='top' title='Edit' type='button'></span> 
                                        </button>
                                        <button class=''>
                                            <span href='".$this->Url->build(['controller' => 'comments', 'action' => 'delete', $commentId])."' class='$commentClassListener' data-toggle='tooltip' data-placement='top' title='$commentTitle' type='button'></span> 
                                        </button>
                                    </div>";
            }
            
            $comment .= "<div class='container comments border'>";
            $userProfile = "<div class='row p-2'>";
            $userProfile .= "    <div class='p-2 m-2 col-sm-1'>
                                    <img src=' $commenterImg 'class='mx-auto'>
                                </div>";
            $userProfile .= "   <div class='row p-2 col-sm-11'>";
            $userProfile .= $commentButtons;
            $userProfile .= "       <div class='post-user'><a href='".$this->Url->build(['controller' => 'users', 'action' => 'profile', $val->user_id])."'>
                                    $commenter
                                    </a></div>
                                    <div class='post-ago'>
                                        $commentAgo
                                    </div>
                                    ";
            $comment .= $userProfile;
            $commentContent =       "<div class='post-content col-sm-12'>
                                        <p>";
            $commentContent .=           $val['content'];
            $commentContent .=         "</p>
                                     </div>";
            $commentContent .=   "</div>";
            $comment .= $commentContent;
            $comment .=     "</div>
                         </div>";
        }
        echo $comment;
        
        $paginator = $this->Paginator;
        $paginator->setTemplates([
            'number' => '<b><a class="pl-3" href="{{url}}"> {{text}} </a></b>',
            'nextActive' => '<a class="fa fa-arrow-right pl-3" title="next" href="{{url}}"> {{text}} </a>',
            'prevActive' => '<a class="fa fa-arrow-left pl-3" title="previous" href="{{url}}"> {{text}} </a>',
            'first' => '<a class="fa fa-fast-backward pl-3" title="first" href="{{url}}"> {{text}} </a>',
            'last' => '<a class="fa fa-fast-forward pl-3" title="last" href="{{url}}"> {{text}} </a>',
            'current' => '<b><a class="text-secondary pl-3" title="current" href="{{url}}"> {{text}} </a></b>',
        ]);

        echo "<nav class='paging'>";
        echo $paginator->First('');
        echo "  ";
        
        if($paginator->hasPrev()) {
            echo $paginator->prev('');
        }
        echo "  ";
        
        echo $paginator->numbers(['modulus' => 2]);
        echo "  ";
        
        if($paginator->hasNext()) {
            echo $paginator->next("");
        }
        echo "  ";

        echo $paginator->last('');
        echo "</nav>";
    }
?>
<?php endif;?>