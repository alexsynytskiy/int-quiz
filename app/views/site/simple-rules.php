<?php

/* @var $this yii\web\View */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block rules profile clearfix">
    <?= $this->render('/_blocks/profile-header') ?>

    <div class="separator-space"></div>

    <div class="step-title"><?= 'Правила гри' ?></div>

    <div class="text-info-block" style="margin-bottom: 30px;">
        Шоб дізнатись деталі місії «Intellias.The Sixth Element» тобі треба дати відповіді на 6
        запитань. Тематика запитань – діяльність Intellias та особливості інших  космічних світів.
        6 запитань поділені на блоки по 2 запитання. По проходженні блоку ти отримаєш інформацію.
        <div class="margin-text">А кожна правильна відповідь створює тобі капітал – ти отримуєш один смарт.
            Смарти є валютою для купівлі сувенірів і цінних призів. Стан рахунку смартів відображений у верхньому
            правому куті сторінки.</div>
        <div class="margin-text">Блоки запитань активуються раз на тиждень і доступні лише 7 днів.</div>
        <div class="margin-text">Коли ти відкрив блок, то маєш 10 хвилин на обидва запитання. Для зручності
            біля блоків працює таймер зворотного відліку. Тож читай швидко і будь уважним.</div>
    </div>

    <a href="<?= \yii\helpers\Url::to(['/profile']) ?>">
        <?= \yii\helpers\Html::submitButton('Назад до гри', [
            'class' => 'link-button',
            'id' => 'rules-read-agreement',
            ]) ?>
    </a>
</div>
