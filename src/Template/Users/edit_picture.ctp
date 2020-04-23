<div class="card-body mt-2">
    <?= $this->Form->create($user, ['enctype' => 'multipart/form-data']); ?>
                            
    <?= $this->Form->control('image',
                        ['class' => 'form-control',
                        'id' => 'image',
                        'type' => 'file',
                            'label'=>['text'=>'Upload Image',
                                    'for' => 'image',
                                    'class'=>'col-form-label']]);?>

    <?= $this->Form->button("update picture", ['type' => 'button',
                                                'class'=>'update_picture btn btn-primary form-control mt-5',
                                                'style' => 'float: right']); ?>
    <?= $this->Form->end(); ?>
</div>