<?php

/* @var $this yii\web\View */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block rules clearfix">
    <div class="logo"></div>
    <div class="step-title"><?= 'День народження компанії' ?></div>

    <div class="text-info-block">
        08 вересня 2018 року, початок о 14:00
        <div class="margin-text">Львів: Стадіон «Юність», Парк культури та відпочинку ім. Б. Хмельницького</div>
        <div class="margin-text">Київ: База відпочинку «Труханов», Труханів острів, вул. Паркова дорога, 12</div>
    </div>

    <div class="separator-space"></div>
    <div class="step-title"><?= 'Про подію' ?></div>

    <div class="text-info-block">
        Вітаємо!
        <div class="margin-text">Восьмого вересня на Землі зберуться створіння різних світів: від Асгарду до Клендату, від
        далекої-далекої галактики до Кібертрона. Їх усіх приведе до нас особлива місія – пошук і
            активація деякого «шостого елементу», важливого для розвитку Всесвіту.</div>
        <div class="margin-text">Щоб взяти участь у події, необхідно прийняти образ представника певної космічної раси. Для людей
            є особливий дрес-код – блискуче вбрання.</div>
        <div class="margin-text">Додаткова секретна інформація – у онлайн-грі.</div>
    </div>

    <div class="separator-space"></div>
    <div class="step-title"><?= 'Правила гри' ?></div>

    <div class="text-info-block">
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

    <div class="separator-space"></div>

    <div class="text-info-block text-info-block-bold">
        З правилами Гри ознайомлений. Натискаючи кнопку «Далі» я погоджуюсь з тим, що маю лише 10 хвилин
        на відповіді і, якщо не встигаю за відведений час, то другого шансу заповнити цей тижневий
        блок не маю.
    </div>

    <?= \yii\helpers\Html::submitButton('Далі', ['class' => 'link-button', 'id' => 'rules-read-agreement']) ?>

</div>

<?php
$pageOptions = \yii\helpers\Json::encode([
    'acceptAgreementUrl' => '/quiz/agreement/',
]);

$this->registerJs('RulesPage(' . $pageOptions . ')');
?>