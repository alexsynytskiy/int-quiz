<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $points string */
/* @var $blockQuestion \app\models\Question */

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

    <div class="question" data-id="<?= $blockQuestion->id ?>">
        <?= $blockQuestion->text ?>
    </div>

    <div class="answers clearfix">
        <?php foreach ($blockQuestion->answers as $answer): ?>
            <div class="answer" data-id="<?= $answer->id ?>">
                <div class="text">
                    <?= $answer->text ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?= \yii\helpers\Html::submitButton('Підтвердити', ['class' => 'link-button', 'id' => 'submit-answer']) ?>
</div>

<?php
$pageOptions = \yii\helpers\Json::encode([
    'questionId' => $blockQuestion->id,
    'checkAnswerUrl' => '/quiz/answer-check/',
]);

$this->registerJs('AnswerPage(' . $pageOptions . ')');
?>