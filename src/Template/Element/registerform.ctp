<div class="card">
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
        <div class="card-body card-block">
            <div class="bd-note rounded">
                <p><strong>NOTE:</strong> Please type your <strong>Password</strong> having 8 characters with At least 1 uppercase letter, lowercase  letters, numbers and 1 special character</p>
            </div>
            <?= $this->Form->create($user); ?>
            <div class="row">
                <div class="col-md-6">
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
                        'password' => [
                            'placeholder' => "Enter password ...", 
                            'required' => false,
                            'id' => 'password',
                            'label'=>['text'=>'Password',
                                      'for' => 'password',
                                      'class'=>'col-form-label'],
                            'class' => ($this->Form->isFieldError('password')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                        ],
                        'confirm_password' => [
                            'type'=>'password', 
                            'required' => false,
                            'id' => 'confirm_password',
                            'placeholder' => "Confirm Password ...",
                            'label'=>['text'=>'Confirm Password',
                                      'for' => 'confirm_password',
                                      'class'=>'col-form-label'],
                            'class' => ($this->Form->isFieldError('confirm_password')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                        ],
                        'gender' => [
                            'required' => false,
                            'id' => 'gender',
                            'options' => $options,
                            'label'=>['text'=>'Gender',
                                      'for' => 'gender',
                                      'class'=>'col-form-label'],
                            'class' => ($this->Form->isFieldError('gender')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                        ],
                    ]);
                    ?>
                </div>
                
                <div class="col-md-6">
                <?php    
                
                echo $this->Form->controls([
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
                        'required' => false,
                        'id' => 'suffix',
                        'label'=>['text'=>'Suffix',
                                  'for' => 'suffix',
                                  'class'=>'col-form-label'],
                        'class' => ($this->Form->isFieldError('suffix')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                    ],
                ]);
                ?>
                </div>
                
                <div class="col-md-12">
                <?php 
                    echo $this->Form->control('email',
                                        ['class' => ($this->Form->isFieldError('email')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm',
                                        'required' => false,
                                        'placeholder' => 'Enter email ...',
                                        'id' => 'email',
                                            'label'=>['text'=>'Email',
                                                    'for' => 'email',
                                                    'class'=>'col-form-label']]);
                ?>
                </div>
                <div class="col-md-12">
                <?= $this->Form->button("Register",['class'=>'register_use auth-btn btn btn-secondary form-control mt-3']); ?>
                <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>