<?php
    if(!empty($data)) {
        $user = "<div class='container pt-4'>";
        foreach ($data as $following) {
            $fullname = $following->full_name;
            $profilePic = $this->System->getUserPic($following->id);
            $joined = $following->joined;
            $me = $myId === $following->id ? true : false;
            
            $user .= "<div class='container-fluid border'>";

            $user .= "<div class='row'>";
            $user .=         "<div class='p-0 m-2 col-sm-1'>
                                    <img src='".$profilePic."'class='mx-auto'>
                                </div>
                                <div class='row col-sm-11'>
                                    <div class='post-user col-sm-6 mt-3'><a href='".$this->Url->build(['controller' => 'users', 'action' => 'profile', $following->id])."'>
                                    $fullname
                                    </a></div>";
                        if(!$me){
                            $isFollowing = $this->System->isFollowing($myId, $following->id);
                            $hadFollowed = $this->System->hadFollowed($myId, $following->id);
                            $hrefAction = $hadFollowed ? 'unfollow' : 'follow';

                            $btnTitle = $isFollowing ? 'Unfollow' : 'Follow';
                            $btnClass = $isFollowing ? 'unfollow_user btn-danger' : 'follow_user btn-primary';
            $user .=  "<div id='buttons-container' class='follow-button col-sm-5 mt-3'>
                            <button href='".$this->Url->build(['controller' => 'users', 'action' => $hrefAction, $following->id])."' type='button' class='".$btnClass." btn-sm'>".$btnTitle."</button>
                        </div>";
                        }
            $user .=       "<div class='post-content mb-3 col-sm-12'>
                                        <span>
                                        <h5 class='text-secondary'><i class='far fa-calendar-alt'></i> Joined $joined</h5>
                                    </span>
                                    </div>";
            $user .=      "</div>";
            $user .=    "</div>";
            $user .= "</div>";
        }
        $user .=     "</div>";
        echo $user;

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
    } else {
        echo "<span class='container'><h2>".$message."</h2></span>";
    }
?>