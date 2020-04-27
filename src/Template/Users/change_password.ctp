<div class="card-body card-block offset-md-3 mt-4">
    <div class="row">
        <div class="bd-note rounded">
            <p><strong>NOTE:</strong> Please type the <strong>Passwords</strong> having 8 characters with At least 1 uppercase letter, lowercase  letters, numbers and 1 special character</p>
        </div>
        <div class="col-md-8 offset-md-2">
        <?php 
        $myTemplates = [
            'legend' => false,
            'inputContainer' => '<div class="form-group">{{content}}</div>',
            'inputContainerError' => '<div class="input {{type}}{{required}} error">{{content}}{{error}}</div>',
            'error' => '<span class="help-block">{{content}}</span>',
        ];
        $this->Form->setTemplates($myTemplates);
        ?>
            <?= $this->Form->create($user); ?>
            <?php
                echo $this->Form->control('old_password',
                                    ['class' => 'form-control form-control-sm',
                                    'type' => 'password',
                                    'placeholder' => 'Enter Old Password ...',
                                    'id' => 'old_password',
                                        'label'=>['text'=>'Old Password',
                                                'for' => 'old_password',
                                                'class'=>'col-form-label']]);
                echo $this->Form->control('password',
                                        ['class' => 'form-control form-control-sm',
                                        'type' => 'password',
                                        'placeholder' => 'Enter New Password ...',
                                        'id' => 'password',
                                            'label'=>['text'=>'New Password',
                                                    'for' => 'password',
                                                    'class'=>'col-form-label']]);
                echo $this->Form->control('confirm_password',
                                    ['class' => 'form-control form-control-sm',
                                    'type' => 'password',
                                    'placeholder' => 'Enter Confirm Password ...',
                                    'id' => 'confirm_password',
                                        'label'=>['text'=>'Confirm Password',
                                                'for' => 'confirm_password',
                                                'class'=>'col-form-label']]);
            ?>
            <?= $this->Form->button("Change Password", ['class'=>'change_password btn btn-primary form-control mt-3']); ?>
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>