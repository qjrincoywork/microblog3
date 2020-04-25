<div class="container mt-3">
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
            'id' => 'post_id'
        ]);
    ?>
    
    <?= $this->Form->button('edit',
                            ['class' => 'edit_comment btn btn-primary',
                            'type' => 'submit',
                            'style' => 'float: right'])?>
    <?= $this->Form->end(); ?>
</div>
