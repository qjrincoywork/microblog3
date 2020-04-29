<?= $this->element('postform'); ?>
<?php
    $article = '';
    if(isset($data)) {
        foreach ($data as $key => $value) {
            $gender = $value->user->gender;
            $profilePic = $value->user->profile_image;
            $postAgo = $value->post_ago;
            $postId = $value->id;
            $postUserId = $value->user_id;
            $postFullName = $value->user->full_name;
            
            $likedBefore = $this->System->likedBefore($postId, $myId, 'Likes');
            $likeHrefAction = $likedBefore ? 'delete' : 'add';

            $isLiked = $this->System->postReaction($postId, $myId, 'Likes');
            $isShared = $this->System->postReaction($postId, $myId, 'Posts');
            $isCommented = $this->System->postReaction($postId, $myId, 'Comments');
            
            $likeCount = $this->System->reactionCount($postId, 'Likes');
            $commentCount = $this->System->reactionCount($postId, 'Comments');
            $shareCount = $this->System->reactionCount($postId, 'Posts');
            
            $article .= "<div class='post-container border'>";
            $article .= "   <div class='row'>
                                <div class='post-img col-sm-2'>";
            $article .=     "<img src='$profilePic'>";
            $article .= "   </div>";
    
            $article .= "<div class='post-details col-sm-10'>
                            <div class='row'>";
            $article .=         "<div class='post-user'><a href='".$this->Url->build(['controller' => 'users', 'action' => 'profile', $postUserId])."'>"
                                    .$postFullName.
                                "</a></div>
                                <div class='post-ago'>
                                    $postAgo
                                </div>
                                <div class='post-content col-sm-12'>
                                    <p>".h($value['content'], false). "<p>
                                </div>";
                                if($value['image']) {
                                    $article .=  "<div class='post-image col-sm-12 mb-2'>
                                                    <img src='/".$value['image']."'>
                                                </div>";
                                }
                                
                        if($value->post_id) {
                            $sharedPost =  $this->System->getSharedPost($value->post_id);
                            if($sharedPost) {
                                $sharedProfile = $sharedPost->user->profile_image;
                                $sharedFullName =  $sharedPost->user->full_name;
                                $sharedPostAgo = $sharedPost->post_ago;
                                $sharedContent = h($sharedPost->content);
                                
                                $sharePost = "<div class='share-post border p-3 m-2'>";
                                $sharePost .= "   <div class='row'>
                                                    <div class='post-img col-sm-2'>";
                                $sharePost .=     "<img src='$sharedProfile'>";
                                $sharePost .= "   </div>";
        
                                $sharePost .= "<div class='post-details col-sm-10'>
                                                    <div class='row'>
                                                        <div class='post-user'><a href='".$this->Url->build(['controller' => 'users', 'action' => 'profile', $sharedPost->user_id])."'>"
                                                            .$sharedFullName.
                                                        "</a></div>
                                                        <div class='post-ago'>
                                                            $sharedPostAgo
                                                        </div>
                                                        <div class='post-content col-sm-12'>
                                                            <p>".$sharedContent. "<p>
                                                        </div>";
                                                        if($sharedPost->image) {
                                                            $sharePost .=  "<div class='sharedpost-image col-sm-12 mb-2'>
                                                                            <img src='/".$sharedPost->image."'>
                                                                        </div>";
                                                        }
                                $sharePost .=       "</div>
                                                </div>
                                            </div>
                                        </div>";
                            } else {
                                $sharePost = "<div class='share-post border p-3 m-2'>";
                                $sharePost .= "<span><h4> Post Deleted </h4></span>";
                                $sharePost .= "</div>";
                            }
                            $article .= $sharePost;
                        }
    
            $article  .=  "</div>
                        </div>
                    </div>";
            $buttons = "<div class='post-buttons border-top'>
                            <div class='row'>
                                <button href='".$this->Url->build(['controller' => 'comments', 'action' => 'add', $postId])."' class='comment_post col-sm-3'>
                                    <span class='" . ($isCommented ? 'fas' : 'far') ." fa-comment' data-toggle='tooltip' data-placement='top' title='Comment'> ". (!empty($commentCount) ? $commentCount : '')."</span>
                                </button>
                                <button href='".$this->Url->build(['controller' => 'likes', 'action' => $likeHrefAction, $postId])."' class='like_post col-sm-3'>
                                    <span class='" . ($isLiked ? 'fas' : 'far') ." fa-heart' data-toggle='tooltip' data-placement='top' title='Like'> ". (!empty($likeCount) ? $likeCount : '') ."</span>
                                </button>
                                <button href='".$this->Url->build(['controller' => 'posts', 'action' => 'share', $postId])."' class='share_post col-sm-3'>
                                    <span class='" . ($isShared ? 'fas' : 'far') ." fa-share-square' data-toggle='tooltip' data-placement='top' title='Share'> ". (!empty($shareCount) ? $shareCount : '')  ."</span>
                                </button>
                                <a href='".$this->Url->build(['controller' => 'posts', 'action' => 'view', $postId])."' class='col-sm-3'>
                                    <span class='fa fa-eye' data-toggle='tooltip' data-placement='top' title='View post'></span>
                                </a>
                            </div>
                        </div>";
            $article .= $buttons;
            $article .= "</div>";
        }
        
        $paginator = $this->Paginator;
        $paginator->setTemplates([
            'number' => '<b><a class="pl-3" href="{{url}}"> {{text}} </a></b>',
            'nextActive' => '<a class="fa fa-arrow-right pl-3" title="next" href="{{url}}"> {{text}} </a>',
            'prevActive' => '<a class="fa fa-arrow-left pl-3" title="previous" href="{{url}}"> {{text}} </a>',
            'first' => '<a class="fa fa-fast-backward pl-3" title="first" href="{{url}}"> {{text}} </a>',
            'last' => '<a class="fa fa-fast-forward pl-3" title="last" href="{{url}}"> {{text}} </a>',
            'current' => '<b><a class="text-secondary pl-3" title="current" href="{{url}}"> {{text}} </a></b>',
        ]);
        echo $article;
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