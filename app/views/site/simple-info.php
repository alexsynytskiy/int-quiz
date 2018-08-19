<?php

/* @var $this yii\web\View */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block rules profile clearfix">
    <?= $this->render('/_blocks/profile-header') ?>

    <div class="separator-space"></div>

    <div class="step-title"><?= 'Про подію' ?></div>

    <div class="text-info-block" style="margin-bottom: 30px;">
        Вітаємо!
        <div class="margin-text">Восьмого вересня на Землі зберуться створіння різних світів: від Флостон Передайз до
            Клендату, від далекої-далекої галактики до Кібертрона. Їх усіх приведе до нас особлива
            місія – пошук і активація незнаного «шостого елементу», важливого для розвитку Всесвіту.</div>
        <div class="margin-text">Секретну інформацію шукай у грі.</div>
    </div>

    <a href="<?= \yii\helpers\Url::to(['/profile']) ?>">
        <?= \yii\helpers\Html::submitButton('Назад до гри', [
            'class' => 'link-button',
            'id' => 'rules-read-agreement',
        ]) ?>
    </a>
</div>