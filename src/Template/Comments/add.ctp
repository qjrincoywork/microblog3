<?php
    $content = $data['content'];
    $postId = $data['id'];
    $postAgo = $data['post_ago'];
    $profPic = $data['user']->profile_image;
    $userId = $myId;
    $fullName = $data['user']->full_name;
?>
<div class="container mt-3">
    <div class='container border p-3 mb-2'>
        <div class='row'>
            <div class="col-sm-2">
                <img src='<?=$profPic;?>'>
            </div>
            <div class="post-details col-sm-10">
                <div class="row">
                    <div class="post-user">
                        <?=$fullName?>
                    </div>
                    <div class="post-ago">
                        <?=$postAgo?>
                    </div>
                    <div class='post-content col-sm-12'>
                        <p>
                            <?=h($content)?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php 
        $myTemplates = [
            'legend' => false,
            'inputContainer' => '<div class="form-group">{{content}}</div>',
            'inputContainerError' => '<div class="input {{type}}{{required}} error">{{content}}{{error}}</div>',
            'error' => '<span class="help-block">{{content}}</span>',
        ];
        $this->Form->setTemplates($myTemplates);
    ?>
    <?= $this->Form->create($comment); ?>
    <?php
        echo $this->Form->control('content', array(
                                'id' => 'content',
                                'type' => 'text',
                                'label' => false,
                                'class' => 'mb-3 form-control ',
                                'placeholder' => "Comment here..."
        ));
        
        echo $this->Form->hidden('id', [
                                'id' => 'id'
        ]);
        
        echo $this->Form->control('post_id', [
            'label' => false,
            'type' => 'hidden',
            'value' => $postId,
            'id' => 'post_id'
        ]);
    ?>
    
    <?= $this->Form->button('add comment',
                            ['class' => 'comment_post btn btn-primary',
                            // 'label' => 'edit post',
                            // 'div' => 'form-group mt-3',
                            'type' => 'submit',
                            'style' => 'float: right'])?>
    <?= $this->Form->end(); ?>
</div>
