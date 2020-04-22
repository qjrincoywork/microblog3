<div class="border mt-3">
    <div class="post-form-container container pt-3">
        <?php 
            $myTemplates = [
                'legend' => false,
                'inputContainer' => '<div class="form-group">{{content}}</div>',
                // 'input' => '<input type="{{type}}" class="form-control form-control-sm is-invalid" name="{{name}}"{{attrs}}/>',
                'inputContainerError' => '<div class="input {{type}}{{required}} error">{{content}}{{error}}</div>',
                'error' => '<span class="help-block">{{content}}</span>',
            ];
            $this->Form->setTemplates($myTemplates);
        ?>
        <?= $this->Form->create('Post', ['url' => ['controller' => 'posts', 'action' => 'add']]); ?>
        <?php
            echo $this->Form->control('content', [
                                    'label' => false,
                                    'required' => false,
                                    'id' => 'content',
                                    'class' => ($this->Form->isFieldError('content')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm',
                                    'placeholder' => "What's happening?...",
                                    'type' => 'text'
            ]);
        ?>
        <div class="preview-image border mt-2" id="preview-image">
            
        </div>
        <?= $this->Form->control('image',
                            ['label' => false,
                            'class' => 'add_image_input',
                            "accept" => ".jpeg, .jpg, .png, .gif",
                            'id' => 'image',
                            'style' => 'display: none;',
                            'type' => 'file']);?>
                            
        <button class="preview_image far fa-image mt-3" data-toggle='tooltip' data-placement='top' title='add image' style="float: left; font-size: 30px; color: #4c82a3;">
        </button>
        <?= $this->Form->button('Post',
                                ['class' => 'post_content btn btn-primary mt-3',
                                'label' => false,
                                'type' => 'submit',
                                'style' => 'float: right']);?>
        <?= $this->Form->end();?>
    </div>
</div>