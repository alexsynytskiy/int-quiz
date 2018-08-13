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
        <a href="#" class="link-additional">
            <div class="link-icon">
                <div class="help"></div>
            </div>
            Help
        </a>
    </div>
</div>
