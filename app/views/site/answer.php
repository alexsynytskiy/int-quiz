<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $points string */
/* @var $blockQuestion \app\models\Question */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;

$userAnswer = \app\models\UserAnswer::findOne([
    'question_id' => $blockQuestion->id,
    'user_id' => \Yii::$app->siteUser->identity->id,
    'answer_id' => null,
]);
?>

<div class="steps-block profile answer-block clearfix">
    <?= $this->render('/_blocks/profile-header') ?>

    <div class="separator-space"></div>

    <div id="question-wrapper">
        <?= $this->render('/_blocks/question-body', ['blockQuestion' => $blockQuestion]) ?>
    </div>
</div>

<?php
$pageOptions = \yii\helpers\Json::encode([
    'questionId' => $blockQuestion->id,
    'checkAnswerUrl' => '/quiz/answer-check/',
    'expiringAt' => strtotime($userAnswer->started_at) + \app\models\Question::TIME_FOR_ANSWER,
]);

$this->registerJs('AnswerPage(' . $pageOptions . ')');
?>