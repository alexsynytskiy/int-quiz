<?php

/* @var $this yii\web\View */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block rules clearfix" style="<?= \Yii::$app->siteUser->isGuest ? 'padding: 64px;' : 'padding: 20px;' ?>">
    <div class="logo logo-big"></div>
    <div class="step-title"><?= 'Святкування Дня компанії' ?></div>

    <div class="text-info-block">
        Упс! Щось пішло не так..
    </div>
</div>