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
                            'label'=>['text'=>'Username',
                                      'for' => 'username',
                                      'class'=>'col-form-label'],
                            'class' => ($this->Form->isFieldError('username')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                        ],
                        'password' => [
                            'placeholder' => "Enter password ...", 
                            'required' => false,
                            'label'=>['text'=>'Password',
                                      'for' => 'password',
                                      'class'=>'col-form-label'],
                            'class' => ($this->Form->isFieldError('password')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                        ],
                        'confirm_password' => [
                            'type'=>'password', 
                            'required' => false,
                            'placeholder' => "Confirm Password ...",
                            'label'=>['text'=>'Confirm Password',
                                      'for' => 'confirm_password',
                                      'class'=>'col-form-label'],
                            'class' => ($this->Form->isFieldError('confirm_password')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                        ],
                        'gender' => [
                            'required' => false,
                            'options' => $options,
                            'label'=>['text'=>'Gender',
                                      'for' => 'gender',
                                      'class'=>'col-form-label'],
                            'class' => ($this->Form->isFieldError('gender')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                        ],
                    ]);
                    // echo $this->Form->control('User.username',[
                    //                         'class' => ($this->Form->isFieldError('username')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm',
                    //                         'placeholder' => 'Enter username ...',
                    //                         'id' => 'username',
                    //                         'label'=>['text'=>'Username',
                    //                                 'for' => 'username',
                    //                                 'class'=>'col-form-label']
                    // ]);
                    // echo $this->Form->control('User.password',
                    //                     ['class' => 'form-control form-control-sm',
                    //                     'placeholder' => 'Enter password ...',
                    //                     'id' => 'password',
                    //                         'label'=>['text'=>'Password',
                    //                                 'for' => 'password',
                    //                                 'class'=>'col-form-label']]);
                    // echo $this->Form->control('User.confirm_password',
                    //                     ['class' => 'form-control form-control-sm',
                    //                     'type'=>'password',
                    //                     'placeholder' => 'Enter Confirm Password ...',
                    //                     'id' => 'confirm_password',
                    //                         'label'=>['text'=>'Confirm Password',
                    //                                 'for' => 'confirm_password',
                    //                                 'class'=>'col-form-label']]);
                    // echo $this->Form->control('UserProfile.gender',
                    //                         ['options' => $options,
                    //                         'id' => 'gender',
                    //                             'class' => 'form-control form-control-sm',
                    //                             'label'=>['text'=>'Gender',
                    //                                 'for' => 'gender',
                    //                                 'class'=>'col-form-label']]
                    //                     );
                    ?>
                </div>
                
                <div class="col-md-6">
                <?php    
                
                echo $this->Form->controls([
                    'first_name' => [
                        'placeholder' => "Enter first name ...",
                        'required' => false,
                        'label'=>['text'=>'First name',
                                  'for' => 'first_name',
                                  'class'=>'col-form-label'],
                        'class' => ($this->Form->isFieldError('first_name')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                    ],
                    'last_name' => [
                        'placeholder' => "Enter last name ...",
                        'required' => false,
                        'label'=>['text'=>'Last Name',
                                  'for' => 'last_name',
                                  'class'=>'col-form-label'],
                        'class' => ($this->Form->isFieldError('last_name')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                    ],
                    'middle_name' => [
                        'placeholder' => "Enter middle Name ...",
                        'required' => false,
                        'label'=>['text'=>'Middle Name',
                                  'for' => 'middle_name',
                                  'class'=>'col-form-label'],
                        'class' => ($this->Form->isFieldError('middle_name')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                    ],
                    'suffix' => [
                        'placeholder' => "Enter suffix ...",
                        'required' => false,
                        'label'=>['text'=>'Suffix',
                                  'for' => 'suffix',
                                  'class'=>'col-form-label'],
                        'class' => ($this->Form->isFieldError('suffix')) ? 'form-control form-control-sm is-invalid' : 'form-control form-control-sm'
                    ],
                ]);
                /* echo $this->Form->control('UserProfile.first_name',
                                        ['class' => 'form-control form-control-sm',
                                        'placeholder' => 'Enter first name ...',
                                        'id' => 'first_name',
                                            'label'=>['text'=>'First Name',
                                                    'for' => 'first_name',
                                                    'class'=>'col-form-label']]);
                    echo $this->Form->control('UserProfile.last_name',
                                        ['class' => 'form-control form-control-sm',
                                        'placeholder' => 'Enter last name ...',
                                        'id' => 'last_name',
                                            'label'=>['text'=>'Last name',
                                                    'for' => 'last_name',
                                                    'class'=>'col-form-label']]);
                    echo $this->Form->control('UserProfile.middle_name',
                                        ['class' => 'form-control form-control-sm',
                                        'placeholder' => 'Enter middle name ...',
                                        'id' => 'middle_name',
                                            'label'=>['text'=>'Middle Name',
                                                    'for' => 'middle_name',
                                                    'class'=>'col-form-label']]);
                    echo $this->Form->control('UserProfile.suffix',
                                        ['class' => 'form-control form-control-sm',
                                        'placeholder' => 'Enter suffix ...',
                                        'id' => 'suffix',
                                            'label'=>['text'=>'Suffix',
                                                    'for' => 'suffix',
                                                    'class'=>'col-form-label']]); */
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