<?php
    if(!isset($params['escape']) || $params['escape'] !== false) {
        $message = h($message);
    }
?>
<div class="alert alert-warning" id="flashMessage" onclick="this.classList.add('hidden');"><?= $message ?></div>