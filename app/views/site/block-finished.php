<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $points string */
/* @var $wrongAnswers array */
/* @var $group \app\models\QuestionGroup */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block profile answer-block clearfix">
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

    <div class="image-group">
        <img src="/app/media/img/<?= $group->hash ?>.png">
    </div>

    <div class="congrads">
        Вітаємо з проходженням <?= $group->id ?>-го блоку запитань!
    </div>

    <?php if($wrongAnswers): ?>
        <div class="new-info">
            Тепер ти знаеш що: <br>
            <?php foreach ($wrongAnswers as $answer): ?>
                <?= $answer ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <a href="<?= \yii\helpers\Url::to(['/profile']) ?>">
        <?= \yii\helpers\Html::submitButton('Далі', [
            'class' => 'link-button',
        ]) ?>
    </a>
</div>