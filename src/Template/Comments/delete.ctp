<div class='card-body card-block mt-3'>
    <label for='nf-description' class='form-control-label'>Are you sure you want to delete this comment?, 
        This can't be undone and it will be removed from your profile and the timeline of any accounts.</label>
</div>
<?= $this->Form->create($comment); ?>
<?php    
    echo $this->Form->control('id', array(
                            'label' => false,
                            'type' => 'hidden',
                            'id' => 'id'
    ));
?>
<?= $this->Form->button('remove',
                        ['class' => 'delete_comment btn btn-danger mt-2',
                        'type' => 'submit',
                        'style' => 'float: right'])?>
<?= $this->Form->end(); ?>