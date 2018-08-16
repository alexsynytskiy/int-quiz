<?php ?>

<div class="profile-header clearfix">
    <div class="logo"></div>
    <div class="profile-navigation">
        <a href="<?= \yii\helpers\Url::to(['/site/logout']) ?>" class="link-additional">
            <div class="link-icon">
                <div class="exit"></div>
            </div>
            Вихід
        </a>
        <a href='mailto:kkovalchat@intellias.com,kkozlova@intellias.com?subject=Гра до Дня компанії, техпідтримка' class="link-additional">
            <div class="link-icon">
                <div class="help"></div>
            </div>
            Техпідтримка
        </a>
        <a href="<?= \yii\helpers\Url::to(['/site/game-rules']) ?>" class="link-additional">
            <div class="link-icon">
                <div class="rules"></div>
            </div>
            Правила
        </a>
        <a href="<?= \yii\helpers\Url::to(['/site/event-info']) ?>" class="link-additional">
            <div class="link-icon">
                <div class="about"></div>
            </div>
            Про подію
        </a>
    </div>
</div>
