<div class='card-body card-block mt-3'>
    <label for='nf-description' class='form-control-label'>Are you sure you wish to <?= $post->deleted ? "restore" : "delete" ?> this post?</label>
</div>
<?= $this->Form->create($post); ?>
<?php
    $label = $post->deleted ? "Restore" : "Delete";
    $classListener = $post->deleted ? "restore_post" : "delete_post";
    $value = $post->deleted ? 0 : 1;
    
    echo $this->Form->control('id', array(
                            'label' => false,
                            'type' => 'hidden',
                            'id' => 'id'
    ));

    echo $this->Form->control('deleted', array(
                            'label' => false,
                            'id' => 'deleted',
                            'type' => 'hidden',
                            'value' => $value,
                            'class' => 'mb-3 form-control '
    ));
?>
<?= $this->Form->button($label,
                        ['class' => $classListener.' btn btn-primary mt-3',
                        'type' => 'submit',
                        'style' => 'float: right'])?>
<?= $this->Form->end(); ?>