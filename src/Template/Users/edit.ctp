<div class="card-body card-block mt-2">
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
    <?= $this->Form->create($user); ?>
    <?php
        $options = ['' => 'Select Gender...', 0 => 'Female', 1 => 'Male'];
        echo $this->Form->controls([
                'username' => [
                    'placeholder' => "Enter username ...", 
                    'required' => false,
                    'id' => 'username',
                    'label'=>['text'=>'Username',
                            'for' => 'username',
                            'class'=>'col-form-label'],
                    'class' => ($this->Form->isFieldError('username')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                ],
                'email' => [
                    'placeholder' => "Enter email ...", 
                    'required' => false,
                    'id' => 'email',
                    'label'=>['text'=>'Email',
                            'for' => 'email',
                            'class'=>'col-form-label'],
                    'class' => ($this->Form->isFieldError('email')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                ],
                'gender' => [
                    'required' => false,
                    'options' => $options,
                    'id' => 'gender',
                    'label'=>['text'=>'Gender',
                            'for' => 'gender',
                            'class'=>'col-form-label'],
                    'class' => ($this->Form->isFieldError('gender')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                ],
                'first_name' => [
                    'placeholder' => "Enter first name ...",
                    'required' => false,
                    'id' => 'first_name',
                    'label'=>['text'=>'First name',
                                'for' => 'first_name',
                                'class'=>'col-form-label'],
                    'class' => ($this->Form->isFieldError('first_name')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                ],
                'last_name' => [
                    'placeholder' => "Enter last name ...",
                    'required' => false,
                    'id' => 'last_name',
                    'label'=>['text'=>'Last Name',
                                'for' => 'last_name',
                                'class'=>'col-form-label'],
                    'class' => ($this->Form->isFieldError('last_name')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                ],
                'middle_name' => [
                    'placeholder' => "Enter middle Name ...",
                    'required' => false,
                    'id' => 'middle_name',
                    'label'=>['text'=>'Middle Name',
                                'for' => 'middle_name',
                                'class'=>'col-form-label'],
                    'class' => ($this->Form->isFieldError('middle_name')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                ],
                'suffix' => [
                    'placeholder' => "Enter suffix ...",
                    'id' => 'suffix',
                    'required' => false,
                    'label'=>['text'=>'Suffix',
                                'for' => 'suffix',
                                'class'=>'col-form-label'],
                    'class' => ($this->Form->isFieldError('suffix')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                ],
            ]);
        ?>
    
    <?= $this->Form->button("edit profile", ['class'=>'edit_profile btn btn-primary form-control mt-3']); ?>
    <?= $this->Form->end(); ?>
</div>