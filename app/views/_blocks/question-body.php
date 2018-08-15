<?php
/* @var $this yii\web\View */
/* @var $blockQuestion \app\models\Question */

$countAnswered = 2 - $blockQuestion->emptyQuestionsCount;
?>

<div class="question clearfix" data-id="<?= $blockQuestion->id ?>" data-group-id="<?= $blockQuestion->group_id ?>">
    <?php if($blockQuestion->image): ?>
        <div class="question-text">
            <?= $blockQuestion->text ?>
        </div>
        <div class="image">
            <img src="<?= $blockQuestion->image ?>">
        </div>
    <?php else: ?>
        <?= $blockQuestion->text ?>
    <?php endif; ?>
</div>

<div class="timer">
    <div class="timer-wrapper clearfix">
        <div class="timer-icon"></div>
        <div id="time-value"></div>
    </div>
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

<?= \yii\helpers\Html::submitButton('Підтвердити', [
    'class' => 'link-button',
    'id' => 'submit-answer',
]) ?>

<div class="pointer-question">
    <div class="pointer-question-wrapper clearfix">
        <?php for($i = 0; $i < $countAnswered; $i++): ?>
            <div class="item answered-point"></div>
        <?php endfor; ?>

        <?php for($i = 0; $i < 2 - $countAnswered; $i++): ?>
            <div class="item unanswered-point"></div>
        <?php endfor; ?>
    </div>
</div>
