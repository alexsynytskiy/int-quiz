<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $points string */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block profile clearfix">
    <div class="profile-header clearfix">
        <div class="logo"></div>
        <div class="profile-navigation">
            <a href="<?= \yii\helpers\Url::to(['/site/logout']) ?>" class="link-additional">
                <div class="link-icon">
                    <div class="exit"></div>
                </div>
                Вихід
            </a>
            <a href="#" class="link-additional">
                <div class="link-icon">
                    <div class="rules"></div>
                </div>
                Правила
            </a>
            <a href="#" class="link-additional">
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
    <div class="separator-space"></div>
    <div class="statistics">
        <div class="statistics-wrapper clearfix">
            <div class="nick"><?= $name ?></div>
            <div class="smarts">
                <div class="icon"></div>
                <?= $points ?> smarts
            </div>
        </div>
    </div>

    <div class="questions">
        <div class="block clearfix">
            <div class="left-part">
                <div class="title">Назва блоку з питаннями 1</div>
                <div class="sub-title">Короткий опис блоку з питаннями для заклику до дії.</div>
            </div>
            <div class="right-part">
                <div class="numbers">
                    <div class="number correct">1</div>
                    <div class="number wrong">2</div>
                </div>
                <div class="start enabled">
                    <div class="start-title">Старт</div>
                    <div class="time">10 хв</div>
                </div>
            </div>
        </div>
    </div>
</div>