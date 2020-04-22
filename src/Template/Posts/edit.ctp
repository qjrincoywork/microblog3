<?php
    pr($post);
    die('view');
    $content = $this->data['Post']['content'];
    $id = $this->data['Post']['id'];
    $postAgo = $this->data['Post']['post_ago'];
    $profPic = $this->data['UserProfile']['image'];
    $userId = $this->Session->read('Auth.User')['id'];
    $postImage = !empty($this->data['Post']['image']) ? "/".$this->data['Post']['image'] : '';
    $fullName = $this->System->getFullNameById($userId);
?>
<div class="posts form large-9 medium-8 columns content">
    <?= $this->Form->create($post) ?>
    <fieldset>
        <legend><?= __('Edit Post') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('content');
            echo $this->Form->control('image');
            echo $this->Form->control('post_id');
            echo $this->Form->control('deleted');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<div class="container p-3">
    <?= $this->Form->create('Post',
                            ['url' => ['controller' =>'posts', 'action' => 'edit']],
                            ['inputDefaults'=> ['div' => 'form-group']]); ?>
    <?php
        echo $this->Form->input('content', array(
                                'id' => 'content',
                                'label' => false,
                                'class' => 'mb-3 form-control ',
                                'placeholder' => "Edit Content..."
        ));
        
        echo $this->Form->hidden('id', array(
                                'label' => false,
                                'id' => 'id'
        ));
    ?>
    
    <?= $this->Form->input('image',
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
    <?= $this->Form->end(['label' => 'edit post',
                            'class' => 'edit_post btn btn-primary',
                            'div' => 'form-group mt-3',
                            'type' => 'submit',
                            'style' => 'float: right']); ?>
</div>
