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
        Щоб дізнатись деталі місії «Intellias.The Sixth Element» тобі потрібно дати відповіді на 6 запитань.
        Тематика питань: факти про Intellias та особливості космічних світів.
        <div class="margin-text">6 запитань поділені на 3 блоки по 2 запитання. Після проходження кожного блоку ти отримаєш частину необхідної
            для проходження місії інформації. А ще за кожну правильну відповідь тобі нараховується один смарт. Стан
            рахунку смартів відображений у верхньому правому куті сторінки і буде переведений на твій рахунок протягом
            місяця після проходження всієї гри.</div>
        <div class="margin-text">Блоки запитань активуються раз на тиждень і доступні лише 5 днів. Коли ти відкрив блок, то маєш 10 хвилин на
            обидва запитання. Для зручності біля блоків працює таймер зворотного відліку. Відкрити блок можна лише раз.
            Тож читай швидко, використовуй Інтернет та будь уважним.</div>
        <div class="margin-text">Успіхів!</div>
    </div>

    <a href="<?= \yii\helpers\Url::to(['/profile']) ?>">
        <?= \yii\helpers\Html::submitButton('Назад до гри', [
            'class' => 'link-button',
            'id' => 'rules-read-agreement',
            ]) ?>
    </a>
</div>
