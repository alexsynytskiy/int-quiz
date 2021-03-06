<?php

/* @var $this yii\web\View */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block rules profile clearfix">
    <?= $this->render('/_blocks/profile-header') ?>

    <div class="separator-space"></div>

    <div class="step-subtitle" style="margin-bottom: 30px;    font-family: 'Play', sans-serif;"><?= 'Техпідтримка' ?></div>
    <div class="help-info">
        Якщо виникли будь-які проблеми з системою - надійшли нам своє запитання чи опис проблеми на одну з вказаних
        адрес з темою <div class="bold">Гра до Дня компанії, техпідтримка</div>.
        <br>Ми зв’яжемось з тобою скайпом протягом доби щодо цієї проблеми.
        <br>Спробуй зайти пізніше.
        <br><a href="mailto:kkovalchat@intellias.com"><div class="bold">kkovalchat@intellias.com</div></a>
        <br><a href="mailto:kkozlova@intellias.com"><div class="bold">kkozlova@intellias.com</div></a>
    </div>

    <a href="<?= \yii\helpers\Url::to(['/profile']) ?>">
        <?= \yii\helpers\Html::submitButton('Назад до гри', [
            'class' => 'link-button'
        ]) ?>
    </a>
</div>