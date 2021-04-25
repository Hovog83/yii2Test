<?php

/** @var String $first_name */
/** @var String $last_name */
/** @var int $workshops_id */
/* @var $user common\models\User */
?>
<div class="verify-email">
    Hello <?= $first_name ?> <?= $last_name ?>,
    <p> You have subscribed to the #<?= $workshops_id ?> workshop.</p>
</div>
