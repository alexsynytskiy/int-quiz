<?php

use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= $this->context->getReturnUrl(['/admin/' . $module]) ?>">
            <?= Yii::t('easyii', 'List') ?>
        </a>
    </li>
</ul>