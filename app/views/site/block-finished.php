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
    <?= $this->render('/_blocks/profile-header') ?>

    <div class="separator-space"></div>

    <div class="image-group">
        <img src="/app/media/img/<?= $group->hash ?>.svg">
    </div>

    <div class="congrads">
        Вітаємо з проходженням <?= $group->id ?>-го блоку запитань!
    </div>

    <div class="new-info about-event">
        <?= $group->completed_data ?>
    </div>

    <?php if($wrongAnswers): ?>
        <div class="new-info about-event">
            <div class="block-finished-sub-title">Тепер ти знаеш що:</div>
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