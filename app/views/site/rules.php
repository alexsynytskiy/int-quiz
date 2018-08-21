<?php

/* @var $this yii\web\View */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block rules main-rules clearfix">
    <div class="logo logo-big"></div>
    <div class="step-title"><?= 'Святкування Дня компанії' ?></div>

    <div class="text-info-block">
        08 вересня 2018 року, початок о 15:00
        <div class="margin-text">Львів: Стадіон «Юність», Парк культури та відпочинку ім. Б. Хмельницького</div>
        <div class="margin-text">Київ: База відпочинку «Труханів», Труханів острів, вул. Паркова дорога, 12</div>
    </div>

    <div class="separator-space"></div>
    <div class="step-title"><?= 'Про подію' ?></div>

    <div class="text-info-block">
        Вітаємо!
        <div class="margin-text">Восьмого вересня на Землі зберуться створіння різних світів: від Флостон Передайз до
        Клендату, від далекої-далекої галактики до Кібертрона. Їх усіх приведе до нас особлива
            місія – пошук і активація незнаного «шостого елементу», важливого для розвитку Всесвіту.</div>
        <div class="margin-text">Секретну інформацію шукай у грі.</div>
    </div>

    <div class="separator-space"></div>
    <div class="step-title"><?= 'Правила гри' ?></div>

    <div class="text-info-block">
        Щоб дізнатись деталі місії «Intellias.The Sixth Element» тобі потрібно дати відповіді на 6 запитань.
        Тематика питань: факти про Intellias та особливості космічних світів.
        <div class="margin-text">6 запитань поділені на 3 блоки по 2 запитання. Після проходження кожного блоку ти отримаєш частину необхідної
        для проходження місії інформації. А ще за кожну правильну відповідь тобі нараховується один смарт. Стан
        рахунку смартів відображений у профілі користувача, і буде переведений на твій рахунок протягом
            місяця після проходження всієї гри.</div>
        <div class="margin-text">Блоки запитань активуються раз на тиждень і доступні лише 5 днів. Коли ти відкрив блок, то маєш 10 хвилин на
        обидва запитання. Для зручності біля блоків працює таймер зворотного відліку. Відкрити блок можна лише раз.
                Тож читай швидко, використовуй Інтернет та будь уважним.</div>
        <div class="margin-text">Успіхів!</div>
    </div>

    <div class="separator-space"></div>

    <div class="text-info-block text-info-block-bold">
        З правилами Гри ознайомлений. Натискаючи кнопку «Далі» я погоджуюсь з тим, що маю лише 10 хвилин на відповіді і,
        якщо не встигаю за відведений час, то другого шансу заповнити цей тижневий блок не маю.
    </div>

    <?= !\Yii::$app->siteUser->identity->agreement_read ?
        \yii\helpers\Html::submitButton('Далі', ['class' => 'link-button', 'id' => 'rules-read-agreement']) :
        '' ?>

</div>

<?php
$pageOptions = \yii\helpers\Json::encode([
    'acceptAgreementUrl' => '/quiz/agreement/',
]);

$this->registerJs('RulesPage(' . $pageOptions . ')');
?>