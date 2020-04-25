<div class="container-fluid">
    <?php 
    $myTemplates = [
        'inputContainer' => '<div class="form-group">{{content}}</div>',
    ];
    $this->Form->setTemplates($myTemplates);
    ?>
    <?= $this->Form->create('User', ['id' => 'UserLoginForm']); ?>
    <?= $this->Form->control('username',
                        ['class' => 'form-control form-control-sm',
                            'placeholder' => 'Enter username ...',
                            'autocomplete' => 'off',
                            'label'=>['text'=>'Username',
                                    'for' => 'username',
                                    'class'=>'col-form-label']
                        ]); ?>
    <?= $this->Form->control('password',
                        ['class' => 'form-control form-control-sm',
                            'placeholder' => 'Enter password ...',
                            'type' => 'password',
                            'label'=>['text'=>'Password',
                                    'for' => 'password',
                                    'class'=>'col-form-label']]);?>
    <?= $this->Form->button(__('Login'),['class'=>'login_user auth-btn form-control btn btn-secondary mt-3']); ?>
    <?= $this->Form->end(); ?>
</div>