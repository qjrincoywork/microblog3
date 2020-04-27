<?php
    $content = $post->content;
    $postAgo = $post->post_ago;
    $profPic = $post->user->profile_image;
    $userId = $myId;
    $postImage = !empty($post->image) ? "/".$post->image : '';
    $fullName = $post->user->full_name;
?>
<?php 
    $myTemplates = [
        'legend' => false,
        'inputContainer' => '<div class="form-group">{{content}}</div>',
        'inputContainerError' => '<div class="input {{type}}{{required}} error">{{content}}{{error}}</div>',
        'error' => '<span class="help-block">{{content}}</span>',
    ];
    $this->Form->setTemplates($myTemplates);
?>
<div class="container mt-3">
    <?= $this->Form->create($post); ?>
    <?php
        echo $this->Form->control('content', array(
                                'id' => 'content',
                                'type' => 'text',
                                'label' => false,
                                'class' => 'mb-3 form-control ',
                                'placeholder' => "Edit Content..."
        ));
        
        echo $this->Form->hidden('id', [
                                'id' => 'id'
        ]);
    ?>
    
    <?= $this->Form->control('image',
                        ['class' => 'image_input form-control',
                        'id' => 'image',
                        'type' => 'file',
                        "accept" => ".jpeg, .jpg, .png, .gif",
                        'style' => 'display: none;',
                        'label' => false]);?>
                        
    <div class="preview-image form-group">
        <label for="image" class="form-control-label"></label>
        <img class="img-upload" src="<?=$postImage?>">
    </div>
    
    <div class='container border p-3 mt-2'>
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
    <button class="edit_preview_image far fa-image" data-toggle='tooltip' data-placement='top' title='change image' style="float: left; font-size: 30px; color: #4c82a3;">
    </button>
        <?= $this->Form->button('Edit Post',
                                ['class' => 'edit_post btn btn-primary mt-3',
                                // 'label' => 'edit post',
                                // 'div' => 'form-group mt-3',
                                'type' => 'submit',
                                'style' => 'float: right'])?>
    <?= $this->Form->end(); ?>
</div>
