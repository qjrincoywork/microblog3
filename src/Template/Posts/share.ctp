<?php
    $postId = $data->post_id ? $data->post_id : $data->id;
    $content = $data->content;
    $postAgo = $data->post_ago;
    $profPic = $data->user->profile_image;
    $fullName = $data->user->full_name;
?>
<div class="container p-3">
    <?= $this->Form->create($post); ?>
    <?php
        echo $this->Form->control('content', [
                                'label' => false,
                                'type' => 'text',
                                'id' => 'content',
                                'class' => 'mb-3 form-control ',
                                'placeholder' => "Content here..."
        ]);
        echo $this->Form->control('post_id', [
                                'label' => false,
                                'type' => 'hidden',
                                'value' => $postId,
                                'id' => 'post_id'
        ]);
    ?>
    <div class='container border p-3'>
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
    <?= $this->Form->button('share',
                            ['class' => 'share_post btn btn-primary mt-3',
                            'type' => 'submit',
                            'style' => 'float: right'])?>
    <?= $this->Form->end(); ?>
</div>