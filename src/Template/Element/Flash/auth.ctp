<?php
    if(!isset($params['escape']) || $params['escape'] !== false) {
        $message = h($message);
    }
?>
<div class="alert alert-warning mt-2" id="flashMessage" onclick="this.classList.add('hidden');"><?= $message ?></div>